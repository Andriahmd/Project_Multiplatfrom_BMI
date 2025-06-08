@extends('layouts.app')
@section('content')
    <h2>Daftar Catatan BMI</h2>
    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif
    <a href="{{ route('bmi_records.create') }}">Tambah Catatan</a>
    <table border="1">
        <tr>
            <th>User</th>
            <th>Berat (kg)</th>
            <th>Tinggi (m)</th>
            <th>BMI</th>
            <th>Kategori</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>
        @foreach ($records as $record)
            <tr>
                <td>{{ $record->user->name }}</td>
                <td>{{ $record->weight }}</td>
                <td>{{ $record->height }}</td>
                <td>{{ $record->bmi }}</td>
                <td>{{ $record->category }}</td>
                <td>{{ $record->recorded_at }}</td>
                <td><a href="{{ route('bmi_records.show', $record->id) }}">Lihat</a></td>
            </tr>
        @endforeach
    </table>
@endsection