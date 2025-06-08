<?php

namespace App\Http\Controllers;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller {
    public function index() {
        $articles = Article::all();
        if (request()->wantsJson()) {
            return response()->json($articles);
        }
        return view('admin.articles.index', compact('articles'));
    }

    public function create() {
        return view('admin.articles.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:nutrition,exercise,health',
        ]);
        $article = Article::create($validated);
        if ($request->wantsJson()) {
            return response()->json($article, 201);
        }
        return redirect()->route('articles.index')->with('success', 'Article created!');
    }

    public function edit($id) {
        $article = Article::findOrFail($id);
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, $id) {
        $article = Article::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:nutrition,exercise,health',
        ]);
        $article->update($validated);
        if ($request->wantsJson()) {
            return response()->json($article);
        }
        return redirect()->route('articles.index')->with('success', 'Article updated!');
    }

    public function destroy($id) {
        $article = Article::findOrFail($id);
        $article->delete();
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Article deleted'], 200);
        }
        return redirect()->route('articles.index')->with('success', 'Article deleted!');
    }
}