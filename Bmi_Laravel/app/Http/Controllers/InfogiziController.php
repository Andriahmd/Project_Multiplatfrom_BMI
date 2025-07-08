<?php

namespace App\Http\Controllers;

use App\Models\Infogizi;
use Illuminate\Http\Request;

class InfogiziController extends Controller
{
    public function index()
{
    $infogizi = Infogizi::all();

    return response()->json([
        'status' => true,
        'message' => 'Data info gizi berhasil diambil.',
        'data' => $infogizi
    ], 200);
}


    public function store(Request $request)
    {
        $validated = $request->validate([
            'calories' => 'required|numeric',
            'proteins' => 'required|numeric',
            'fat' => 'required|numeric',
            'carbohydrate' => 'required|numeric',
            'name' => 'required|string|max:255',
            'image' => 'required|string|max:1024',
        ]);

        $infogizi = Infogizi::create($validated);
        return response()->json($infogizi, 201);
    }

    public function show($id)
{
    $infogizi = Infogizi::find($id);

    if (!$infogizi) {
        return response()->json([
            'status' => false,
            'message' => 'Data info gizi tidak ditemukan.'
        ], 404);
    }

    return response()->json([
        'status' => true,
        'message' => 'Detail info gizi berhasil diambil.',
        'data' => $infogizi
    ], 200);
}

    public function update(Request $request, $id)
    {
        $infogizi = Infogizi::findOrFail($id);

        $validated = $request->validate([
            'calories' => 'numeric',
            'proteins' => 'numeric',
            'fat' => 'numeric',
            'carbohydrate' => 'numeric',
            'name' => 'string|max:255',
            'image' => 'string|max:1024',
        ]);

        $infogizi->update($validated);
        return response()->json($infogizi);
    }

    public function destroy($id)
    {
        $infogizi = Infogizi::findOrFail($id);
        $infogizi->delete();
        return response()->json(null, 204);
    }
}