@extends('layouts.app')
@section('content')
    <h2>Daftar Artikel</h2>
    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif
    <a href="{{ route('articles.create') }}">Tambah Artikel</a>
    <table border="1">
        <tr>
            <th>Judul</th>
            <th>Kategori</th>
            <th>Aksi</th>
        </tr>
        @foreach ($articles as $article)
            <tr>
                <td>{{ $article->title }}</td>
                <td>{{ $article->category }}</td>
                <td>
                    <a href="{{ route('articles.edit', $article->id) }}">Edit</a>
                    <form action="{{ route('articles.destroy', $article->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
@endsection