<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Status Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet"> <!-- Tambahkan custom CSS -->
</head>
<body class="edit-status-bg">
    <div class="edit-status-container">
        <h2>Edit Status Transaksi</h2>
        <form action="{{ route('transactions.update-status', $transaction->transaction_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select" required>
                    <option value="diterima" {{ $transaction->status === 'diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="diproses" {{ $transaction->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ $transaction->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="pickup_rejected" {{ $transaction->status === 'pickup_rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('transactions.history') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>
