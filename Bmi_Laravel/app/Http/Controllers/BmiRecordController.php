<?php
namespace App\Http\Controllers;
use App\Models\BmiRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BmiRecordController extends Controller {
    public function index() {
        $records = BmiRecord::with('user', 'recommendations')->get();
        return view('admin.bmi_records.index', compact('records'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'weight' => 'required|numeric|min:0',
        'height' => 'required|numeric|min:0',
    ]);

    // Ambil user dari token (Sanctum)
    $user = Auth::user();

    $bmi = $validated['weight'] / ($validated['height'] * $validated['height']);
    $category = $this->categorizeBmi($bmi);

    $record = BmiRecord::create([
        'user_id' => $user->id,
        'weight' => $validated['weight'],
        'height' => $validated['height'],
        'bmi' => $bmi,
        'category' => $category,
        'recorded_at' => now(),
    ]);

    $this->generateRecommendations($record);

    if ($request->wantsJson()) {
        return response()->json($record, 201);
    }

    return redirect()->route('bmi_records.index')->with('success', 'BMI record created!');
}

    public function show($id) {
        $record = BmiRecord::with('user', 'recommendations')->findOrFail($id);
        // if (request()->wantsJson()) {
        //     return response()->json($record);
        // }
        return response()->json($record);
    }

    private function categorizeBmi($bmi) {
        if ($bmi < 18.5) return 'underweight';
        if ($bmi < 25) return 'Ideal';
        if ($bmi < 30) return 'overweight';
        return 'obese';
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
        foreach ($recommendations as $rec) {
            $record->recommendations()->create($rec);
        }
    }
}