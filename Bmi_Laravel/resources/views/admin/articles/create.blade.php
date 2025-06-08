@extends('layouts.app')
@section('content')
    <h2>Tambah Artikel</h2>
    <form method="POST" action="{{ route('articles.store') }}">
        @csrf
        <div>
            <label>Judul:</label>
            <input type="text" name="title" required>
        </div>
        <div>
            <label>Konten:</label>
            <textarea name="content" required></textarea>
        </div>
        <div>
            <label>Kategori:</label>
            <select name="category" required>
                <option value="nutrition">Nutrisi</option>
                <option value="exercise">Olahraga</option>
                <option value="health">Kesehatan</option>
            </select>
        </div>
        <button type="submit">Simpan</button>
    </form>
@endsection