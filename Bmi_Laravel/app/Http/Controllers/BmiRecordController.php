<?php
namespace App\Http\Controllers;
use App\Models\BmiRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Recommendation;

class BmiRecordController extends Controller {
    public function index() {
        $records = BmiRecord::with('user', 'recommendations')->get();
        return view('admin.bmi_records.index', compact('records'));
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'weight' => 'required|numeric|min:0.1',
        'height' => 'required|numeric|min:0.1',
        'age' => 'required|integer|min:1',
        'gender' => 'required|string|in:male,female',
    ]);

    $user = Auth::user();

    // Hitung BMI
    $bmi = $validated['weight'] / pow($validated['height'] / 100, 2);
    $category = $this->categorizeBmi($bmi);

    // Hitung BMR
    $bmr = $this->calculateBmr(
        $validated['weight'],
        $validated['height'],
        $validated['age'],
        $validated['gender']
    );

    $record = BmiRecord::create([
        'user_id' => $user->id,
        'weight' => $validated['weight'],
        'height' => $validated['height'],
        'age' => $validated['age'],
        'gender' => $validated['gender'],
        'bmi' => $bmi,
        'category' => $category,
        'bmr' => $bmr,
        'recorded_at' => now(),
    ]);
   
    
    return response()->json([
        'message' => 'BMI and BMR record created successfully.',
        'data' => $record,
    ], 201);
}


    // function get all data bmi
    public function indexx(Request $request)
    {
        $records = BmiRecord::with('user', 'recommendations')
            ->when(Auth::check(), function ($query) {
                // Jika user login, filter berdasarkan user_id
                return $query->where('user_id', Auth::id());
            })
            ->orderBy('recorded_at', 'desc')
            ->get();

        if ($request->wantsJson()) {
            return response()->json($records);
        }

        return view('bmi_records.index', compact('records'));
    }

    public function show($id) {
        $record = BmiRecord::with('user', 'recommendations')->findOrFail($id);

        // Tambahkan otorisasi: pastikan pengguna hanya bisa melihat catatannya sendiri
        if (Auth::check() && $record->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($record);
    }

    private function categorizeBmi($bmi) {
        if ($bmi < 18.5) return 'underweight';
        if ($bmi < 25) return 'Ideal';
        if ($bmi < 30) return 'overweight';
        return 'obese';
    }

    private function calculateBmr($weight, $height, $age, $gender) {
        // Rumus Mifflin-St Jeor
        // Tinggi dalam cm, berat dalam kg, usia dalam tahun
        if ($gender === 'male') {
            return (10 * $weight) + (6.25 * $height) - (5 * $age) + 5;
        } elseif ($gender === 'female') {
            return (10 * $weight) + (6.25 * $height) - (5 * $age) - 161;
        }
        return 0; // Default jika gender tidak valid
    }

    private function generateRecommendations(BmiRecord $record) {
        $recommendations = [];
        if ($record->category == 'underweight') {
            $recommendations[] = ['type' => 'food', 'description' => 'Konsumsi makanan tinggi protein seperti telur, ayam, dan kacang-kacangan.'];
            $recommendations[] = ['type' => 'exercise', 'description' => 'Coba latihan kekuatan ringan 2-3 kali seminggu.'];
        } elseif ($record->category == 'obese' || $record->category == 'overweight') {
            $recommendations[] = ['type' => 'food', 'description' => 'Ikuti diet seimbang dengan protein tanpa lemak, sayuran, dan rendah karbo.'];
            $recommendations[] = ['type' => 'exercise', 'description' => 'Lakukan kardio 30-45 menit (misal, jogging, bersepeda) 4-5 kali seminggu.'];
        }

        // Tambahkan rekomendasi BMR (opsional, tergantung kebutuhan)
        if ($record->bmr > 0) {
            $recommendations[] = ['type' => 'info', 'description' => 'BMR Anda adalah ' . round($record->bmr) . ' kalori. Ini adalah perkiraan kalori yang dibutuhkan tubuh Anda saat istirahat. Sesuaikan asupan kalori Anda berdasarkan tingkat aktivitas harian.'];
        }

        foreach ($recommendations as $rec) {
            // Pastikan model Recommendation sudah ada dan di-import
            // Jika Recommendation adalah model terpisah, pastikan relasinya sudah benar di BmiRecord
            $record->recommendations()->create($rec);
        }
    }
}