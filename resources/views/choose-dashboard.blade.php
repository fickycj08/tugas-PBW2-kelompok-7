<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container text-center mt-5">
        <h1>Selamat Datang, {{ Auth::user()->name }}</h1>
        <p>Silakan pilih dashboard yang sesuai:</p>
        @if (Auth::user()->role === 'kasir')
            <a href="{{ route('dashboard') }}" class="btn btn-primary">Dashboard Admin</a>
        @elseif (Auth::user()->role === 'user')
            <a href="{{ route('user.dashboard') }}" class="btn btn-success">Dashboard User</a>
        @else
            <p class="text-danger">Role Anda tidak dikenali.</p>
        @endif
    </div>
</body>
</html>
