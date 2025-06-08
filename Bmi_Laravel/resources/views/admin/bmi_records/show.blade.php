@extends('layouts.app')
@section('content')
    <h2>Detail Catatan BMI</h2>
    <p><strong>User:</strong> {{ $record->user->name }}</p>
    <p><strong>Berat:</strong> {{ $record->weight }} kg</p>
    <p><strong>Tinggi:</strong> {{ $record->height }} m</p>
    <p><strong>BMI:</strong> {{ $record->bmi }}</p>
    <p><strong>Kategori:</strong> {{ $record->category }}</p>
    <p><strong>Tanggal:</strong> {{ $record->recorded_at }}</p>
    <h3>Rekomendasi</h3>
    <ul>
        @foreach ($record->recommendations as $rec)
            <li>{{ $rec->type }}: {{ $rec->description }}</li>
        @endforeach
    </ul>
    <a href="{{ route('bmi_records.index') }}">Kembali</a>
@endsection