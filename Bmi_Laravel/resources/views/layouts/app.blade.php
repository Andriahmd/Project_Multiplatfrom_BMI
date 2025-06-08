<!DOCTYPE html>
<html>
<head>
    <title>BMI Admin - {{ $title ?? 'Dashboard' }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div>
        <h1>Admin Dashboard</h1>
        @if (auth()->user()->role === 'admin')
            <nav>
                <a href="{{ route('bmi_records.index') }}">BMI Records</a> |
                <a href="{{ route('articles.index') }}">Articles</a> |
                <a href="{{ route('logout') }}">Logout</a>
            </nav>
        @endif
        @yield('content')
    </div>
</body>
</html>