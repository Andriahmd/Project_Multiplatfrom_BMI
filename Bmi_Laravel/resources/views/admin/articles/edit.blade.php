@extends('layouts.app')
@section('content')
    <h2>Edit Artikel</h2>
    <form method="POST" action="{{ route('articles.update', $article->id) }}">
        @csrf
        @method('PUT')
        <div>
            <label>Judul:</label>
            <input type="text" name="title" value="{{ $article->title }}" required>
        </div>
        <div>
            <label>Konten:</label>
            <textarea name="content" required>{{ $article->content }}</textarea>
        </div>
        <div>
            <label>Kategori:</label>
            <select name="category" required>
                <option value="nutrition" {{ $article->category == 'nutrition' ? 'selected' : '' }}>Nutrisi</option>
                <option value="exercise" {{ $article->category == 'exercise' ? 'selected' : '' }}>Olahraga</option>
                <option value="health" {{ $article->category == 'health' ? 'selected' : '' }}>Kesehatan</option>
            </select>
        </div>
        <button type="submit">Update</button>
    </form>
    <a href="{{ route('articles.index') }}">Kembali</a>
@endsection