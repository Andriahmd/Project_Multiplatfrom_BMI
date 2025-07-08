<?php

namespace App\Http\Controllers;

use App\Models\Recommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\RecommendationResource;
class RecommendationController extends Controller
{

// GET: /api/recommendations
public function index()
{
    $recommendations = Recommendation::all();

    return response()->json([
        'success' => true,
        'data' => $recommendations->map(function ($item) {
            return [
                'id' => $item->id,
                'user_id' => $item->user_id,
                'user_name' => $item->user?->name,
                'recommendation_text' => $item->recommendation_text,
                'image_url' => $item->image ? asset('storage/' . $item->image) : null,
                'type' => $item->type,
                'description' => $item->description,
                'created_at' => $item->created_at->toDateTimeString(),
            ];
        }),
    ]);
}

    // POST: /api/recommendations
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'bmi_record_id' => 'nullable|exists:bmi_records,id',
            'recommendation_text' => 'required|string',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['user_id'] = $user->id;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/recommendations', 'public');
        }

        $recommendation = Recommendation::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Rekomendasi berhasil disimpan',
            'data' => $recommendation,
        ], 201);
    }

    // GET: /api/recommendations/{id}
    public function show($id)
    {
        $user = Auth::user();
        $recommendation = Recommendation::find($id);

        if (!$recommendation) {
            return response()->json([
                'success' => false,
                'message' => 'Rekomendasi tidak ditemukan',
            ], 404);
        }

        // Opsional: batasi akses hanya ke data milik user yang sedang login
        if ($recommendation->user_id !== $user->id) {
           
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $recommendation->id,
                'bmi_record_id' => $recommendation->bmi_record_id,
                'recommendation_text' => $recommendation->recommendation_text,
                'type' => $recommendation->type,
                'description' => $recommendation->description,
                'image_url' => $recommendation->image ? asset('storage/' . $recommendation->image) : null,
                'created_at' => $recommendation->created_at,
            ],
        ]);
    }
}