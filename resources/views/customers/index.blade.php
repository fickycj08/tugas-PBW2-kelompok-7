<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelanggan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet"> <!-- Tambahkan custom CSS -->
</head>

<body class="customer-bg">
    <div class="container mt-5">
        <!-- Page Title -->
        <h2 class="customer-title">Data Pelanggan</h2>

        <!-- Tombol Tambah Pelanggan -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('customers.create') }}" class="btn customer-btn">Tambah Pelanggan</a>
            <a href="{{ route('dashboard') }}" class="btn voucher-btn">Kembali ke Dashboard</a>
        </div>

        <!-- Alert -->
        @if(session('success'))
            <div class="customer-alert">{{ session('success') }}</div>
        @endif

        <!-- Data Pelanggan -->
        <div class="card customer-card">
            <div class="card-body">
                <table class="table customer-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>No Telepon</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $index => $customer)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->phone_number }}</td>
                                <td>{{ $customer->address }}</td>
                                <td>
                                    <a href="{{ route('customers.edit', $customer->customer_id) }}" class="btn customer-btn btn-sm">Edit</a>
                                    <form action="{{ route('customers.destroy', $customer->customer_id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn customer-btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pelanggan ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data pelanggan.</td>
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
