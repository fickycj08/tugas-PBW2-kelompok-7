<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet"> <!-- Tambahkan custom CSS -->
</head>

<body class="history-bg">
    <div class="container mt-5">
        <!-- Tombol Kembali -->
        <div class="d-flex justify-content-start mb-4">
            <a href="{{ route('dashboard') }}" class="btn history-back-btn">
                <i class="ph-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        <!-- Judul Halaman -->
        <h2 class="history-title">History Transaksi</h2>

        <!-- Filter -->
        <div class="history-filters mb-4">
        <form method="GET" action="{{ route('transactions.history') }}" class="d-flex gap-3">
                <div>
                    <label for="date" class="form-label">Tanggal</label>
                    <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div>
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="diterima" {{ request('status') === 'diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="pickup_rejected" {{ request('status') === 'pickup_rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div>
                    <label for="payment_status" class="form-label">Status Pembayaran</label>
                    <select name="payment_status" id="payment_status" class="form-select">
                        <option value="">Semua</option>
                        <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="success" {{ request('payment_status') === 'success' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                <div class="d-flex align-items-end">
                    <button type="submit" class="btn btn-primary history-filter-btn">Filter</button>
                </div>
            </form>
        </div>

        <!-- Tabel History -->
        <div class="card history-card">
            <div class="card-body">
                <table class="table history-table">
                <thead>
    <tr>
        <th>ID Transaksi</th>
        <th>Nama Pelanggan</th>
        <th>Jenis Layanan</th>
        <th>Berat (kg)</th>
        <th>Harga</th>
        <th>Status</th>
        <th>Pembayaran</th>
        <th>Tanggal Selesai</th>
        <th>Aksi</th> <!-- Kolom baru -->
    </tr>
</thead>
<tbody>
    @forelse($transactions as $transaction)
        <tr>
            <td>{{ $transaction->transaction_id }}</td>
            <td>{{ $transaction->customer->name ?? '-' }}</td>
            <td>{{ ucfirst(str_replace('_', ' ', $transaction->service_type)) }}</td>
            <td>{{ $transaction->weight ?? '-' }}</td>
            <td>Rp {{ number_format($transaction->price, 0, ',', '.') }}</td>
            <td>{{ ucfirst(str_replace('_', ' ', $transaction->status)) }}</td>
            <td>{{ ucfirst(str_replace('_', ' ', $transaction->payment_status)) }}</td>
            <td>{{ $transaction->finished_at ? $transaction->finished_at->format('d M Y H:i') : '-' }}</td>
            <td> <!-- Tambahkan tombol di sini -->
                <a href="{{ route('transactions.edit-status', $transaction->transaction_id) }}" class="btn btn-warning btn-sm">
                    Edit Status
                </a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="9" class="text-center">Tidak ada data transaksi.</td>
        </tr>
    @endforelse
</tbody>

                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
