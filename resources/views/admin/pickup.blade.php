<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pickup Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet"> <!-- Custom CSS -->
</head>

<body class="pickup-bg">
    <div class="container mt-5">
        <h2 class="pickup-title">Pickup Pesanan</h2>
  <!-- Tombol Kembali -->
  <div class="mb-3">
            <a href="{{ route('dashboard') }}" class="btn voucher-btn">Kembali ke Dashboard</a>
        </div>
        <!-- Alert -->
        @if(session('success'))
            <div class="alert alert-success pickup-alert">{{ session('success') }}</div>
        @endif

        <!-- Tabel Pesanan Pickup -->
        <div class="card pickup-card">
            <div class="card-body">
                <table class="table pickup-table">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Nama Pelanggan</th>
                            <th>Alamat</th>
                            <th>Jenis Layanan</th>
                            <th>Kode Voucher</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pickupRequests as $order)
                            <tr>
                                <td>{{ $order->transaction_id }}</td>
                                <td>{{ $order->customer->name }}</td>
                                <td>{{ $order->address }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $order->service_type)) }}</td>
                                <td>
                                    @if($order->voucher)
                                        <span class="badge bg-info">{{ $order->voucher->code }}</span>
                                    @else
                                        <span class="text-muted">Tidak Ada</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $order->status === 'pickup_request' ? 'bg-info' : 'bg-success' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <!-- Tombol Terima -->
                                    <button class="btn btn-success btn-sm pickup-accept" data-bs-toggle="modal"
                                        data-bs-target="#acceptModal-{{ $order->transaction_id }}">Terima</button>

                                    <!-- Modal untuk input berat dan voucher -->
                                    <div class="modal fade" id="acceptModal-{{ $order->transaction_id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content pickup-modal">
                                                <form action="{{ route('admin.pickup.accept', $order->transaction_id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header pickup-modal-header">
                                                        <h5>Terima Pesanan Pickup</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label for="weight" class="form-label">Berat Pakaian (kg)</label>
                                                        <input type="number" name="weight" id="weight" class="form-control" min="0.1" step="0.1" required>
                                                        
                                                        <label for="voucher_code" class="form-label mt-3">Kode Voucher (Opsional)</label>
                                                        <input type="text" name="voucher_code" id="voucher_code" class="form-control" value="{{ $order->voucher->code ?? '' }}">
                                                    </div>
                                                    <div class="modal-footer pickup-modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-success pickup-accept">Terima</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tombol Tolak -->
                                    <button class="btn btn-danger btn-sm pickup-reject" data-bs-toggle="modal"
                                        data-bs-target="#rejectModal-{{ $order->transaction_id }}">Tolak</button>

                                    <!-- Modal Tolak -->
                                    <div class="modal fade" id="rejectModal-{{ $order->transaction_id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content pickup-modal">
                                                <form action="{{ route('admin.pickup.reject', $order->transaction_id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header pickup-modal-header">
                                                        <h5>Tolak Pesanan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label for="reason" class="form-label">Alasan Penolakan</label>
                                                        <textarea name="reason" id="reason" class="form-control" rows="3" required></textarea>
                                                    </div>
                                                    <div class="modal-footer pickup-modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger pickup-reject">Tolak</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center empty-row">Tidak ada pesanan pickup.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</html>
