<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // Tambahkan ini

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user->toArray()], 201); // Pastikan toArray() dipanggil
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email atau password yang anda masukan tidak valid'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user->toArray()], 200); // Pastikan toArray() dipanggil
    }

    // Metode baru: Update Profil (Nama, Email, Foto)
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        Log::info('Update Profile Request Data: ', $request->all());
        Log::info('Update Profile Request Files: ', ['files' => $request->allFiles()]);

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Mengubah 'file' menjadi 'image' untuk validasi lebih ketat
        ]);

        if ($validator->fails()) {
            Log::error('Update Profile Validation failed: ', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $hasChanges = false;

        // Update nama
        if ($request->filled('name') && $user->name !== $request->name) {
            $user->name = $request->name;
            $hasChanges = true;
            Log::info("Name updated to: " . $request->name);
        }

        // Update email
        if ($request->filled('email') && $user->email !== $request->email) {
            $user->email = $request->email;
            $hasChanges = true;
            Log::info("Email updated to: " . $request->email);
        }

        // Update foto
        if ($request->hasFile('foto')) {
            try {
                $foto = $request->file('foto');
                $fotoName = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                $fotoPath = $foto->storeAs('foto', $fotoName, 'public'); // Simpan di storage/app/public/foto

                if ($fotoPath) {
                    // Hapus foto lama jika ada
                    if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                        Storage::disk('public')->delete($user->foto);
                        Log::info('Old photo deleted from storage: ' . $user->foto);
                    }
                    $user->foto = $fotoPath;
                    $hasChanges = true;
                    Log::info('New photo uploaded and path set to: ' . $fotoPath);
                } else {
                    Log::error('Failed to store file, no path returned for photo');
                    return response()->json(['success' => false, 'message' => 'Gagal menyimpan foto profil'], 500);
                }
            } catch (\Exception $e) {
                Log::error('Upload foto error: ' . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan foto', 'error' => $e->getMessage()], 500);
            }
        }

        if ($hasChanges) {
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui.',
                'data' => $user->toArray()
            ], 200); // 200 OK untuk update
        }

        return response()->json([
            'success' => true,
            'message' => 'Tidak ada perubahan yang dilakukan.',
            'data' => $user->toArray()
        ], 200);
    }

    // Metode baru: Ubah Password
    public function changePassword(Request $request)
    {
        $user = $request->user();

        Log::info('Change Password Request Data: ', $request->all());

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed', // 'confirmed' akan mencari field new_password_confirmation
        ]);

        if ($validator->fails()) {
            Log::error('Change Password Validation failed: ', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verifikasi password lama
        if (!Hash::check($request->current_password, $user->password)) {
            Log::warning('Failed password change attempt for user: ' . $user->email);
            return response()->json([
                'success' => false,
                'message' => 'Password lama tidak cocok.',
                'errors' => ['current_password' => ['Password lama yang Anda masukkan salah.']]
            ], 401);
        }

        // Update password baru
        $user->password = Hash::make($request->new_password);
        $user->save();

        Log::info('Password changed successfully for user: ' . $user->email);
        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui.',
            'data' => $user->toArray() // Kembalikan data user yang diperbarui (optional, bisa juga hanya pesan sukses)
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}