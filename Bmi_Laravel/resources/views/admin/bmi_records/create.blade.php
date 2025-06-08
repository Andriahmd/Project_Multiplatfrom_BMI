@extends('layouts.app')
@section('content')
    <h2>Tambah Catatan BMI</h2>
    <form method="POST" action="{{ route('bmi_records.store') }}">
        @csrf
        <div>
            <label>User:</label>
            <select name="user_id" required>
                @foreach (\App\Models\User::all() as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Berat (kg):</label>
            <input type="number" name="weight" step="0.1" required>
        </div>
        <div>
            <label>Tinggi (m):</label>
            <input type="number" name="height" step="0.01" required>
        </div>
        <button type="submit">Simpan</button>
    </form>
@endsection