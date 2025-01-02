<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Voucher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>

<body>
<body class="voucher-bg">
    <div class="container mt-5">
        <!-- Tombol Kembali -->
        <div class="mb-3">
            <a href="{{ route('dashboard') }}" class="btn voucher-btn">Kembali ke Dashboard</a>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="voucher-title">Kelola Voucher</h2>
            <a href="{{ route('vouchers.create') }}" class="btn voucher-btn">Buat Voucher</a>
        </div>

        @if(session('success'))
            <div class="voucher-alert">{{ session('success') }}</div>
        @endif

        <div class="card voucher-card">
            <div class="card-body">
                <table class="table voucher-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Voucher</th>
                            <th>Diskon (%)</th>
                            <th>Digunakan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vouchers as $index => $voucher) 
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $voucher->code }}</td>
                                <td>{{ $voucher->discount }}%</td>
                                <td>{{ $voucher->used ?? 0 }}x</td>
                                <td>{{ now()->between($voucher->valid_from, $voucher->valid_until) ? 'Aktif' : 'Nonaktif' }}</td>
                                <td>
                                    <a href="{{ route('vouchers.edit', $voucher->voucher_id) }}" class="btn voucher-btn btn-sm">Edit</a>
                                    <form action="{{ route('vouchers.destroy', $voucher->voucher_id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn voucher-btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus voucher ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada voucher yang tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
