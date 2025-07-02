<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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

        return response()->json(['token' => $token, 'user' => $user], 201);
        
    }





    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email atau password yang anda masukan tidak falid'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user], 200);
    }



public function updateUser(Request $request)
{
    $user = $request->user();

    Log::info('All request data: ', $request->all());
    Log::info('Request files: ', ['files' => $request->allFiles()]);
    Log::info('Request method: ', ['method' => $request->method()]);
    Log::info('Request headers: ', $request->header());

    // Validasi request
    $validator = Validator::make($request->all(), [
        'name' => 'nullable|string|max:255',
        'email' => 'nullable|email|unique:users,email,' . $user->id,
        'password' => 'nullable|string|min:8',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    if ($validator->fails()) {
        Log::error('Validation failed: ', $validator->errors()->toArray());
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()
        ], 422);
    }

    $hasChanges = false;

    // Proses upload file jika ada
    if ($request->hasFile('foto')) {
        try {
            $foto = $request->file('foto');
            $fotoName = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
            $fotoPath = $foto->storeAs('foto', $fotoName, 'public');

            // Hapus foto lama jika ada
            if ($user->foto) {
                $oldPath = storage_path('app/public/' . $user->foto);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                    Log::info('Old photo deleted: ' . $oldPath);
                }
            }

            $user->foto = $fotoPath;
            $hasChanges = true;

            Log::info('New photo uploaded to: ' . $fotoPath);
        } catch (\Exception $e) {
            Log::error('Upload foto error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan foto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Update data lainnya
    if ($request->filled('name') && $user->name !== $request->name) {
        $user->name = $request->name;
        $hasChanges = true;
    }

    if ($request->filled('email') && $user->email !== $request->email) {
        $user->email = $request->email;
        $hasChanges = true;
    }

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
        $hasChanges = true;
    }

    if ($hasChanges) {
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'foto' => $user->foto ? asset('storage/' . $user->foto) : null,
                'updated_at' => $user->updated_at,
            ]
        ], 200);
    }

    return response()->json([
        'success' => true,
        'message' => 'Tidak ada perubahan yang dilakukan.',
        'data' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'foto' => $user->foto ? asset('storage/' . $user->foto) : null,
            'updated_at' => $user->updated_at,
        ]
    ], 200);
}

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}