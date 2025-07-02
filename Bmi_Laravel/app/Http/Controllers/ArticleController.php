<?php

namespace App\Http\Controllers;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::all();
        if (request()->wantsJson()) {
            return response()->json($articles);
        }
        return view('admin.articles.index', compact('articles'));
    }



    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:nutrition,exercise,health',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

          // Pasti ada karena sudah divalidasi "required|image"
    $path = $request->file('image')->store('uploads/articles', 'public');
    $validated['image'] = $path;

        $article = Article::create($validated);
        if ($request->wantsJson()) {
            return response()->json($article, 201);
        }
        return redirect()->route('articles.index')->with('success', 'Article created!');
    }

    // GET /api/articles/{id} - ambil detail artikel
    public function show($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json([
                'success' => false,
                'message' => 'Artikel tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $article
        ]);
    }
}