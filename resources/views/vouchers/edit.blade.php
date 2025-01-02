<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Voucher</title>
    <!-- Link ke Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light custom-navbar">
        <div class="container">
            <a class="navbar-brand" href="#">TELWASH</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link custom-logout text-white" href="#" data-bs-toggle="modal"
                            data-bs-target="#logoutModal">
                            <i class="ph ph-sign-out"></i> Log Out
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5 form-container">
        <h2 class="form-title">Edit Voucher</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('vouchers.update', $voucher->voucher_id) }}" method="POST" class="voucher-form">
            @csrf
            @method('PUT')
            <div class="mb-3 d-flex align-items-center">
                <label for="code" class="form-label me-3" style="width: 150px;">Kode Voucher</label>
                <input type="text" id="code" name="code" class="form-control custom-input" value="{{ old('code', $voucher->code) }}" required>
            </div>

            <div class="mb-3 d-flex align-items-center">
                <label for="discount" class="form-label me-3" style="width: 150px;">Diskon (%)</label>
                <input type="number" id="discount" name="discount" class="form-control custom-input" min="0" max="100" value="{{ old('discount', $voucher->discount) }}" required>
            </div>

            <div class="mb-3 d-flex align-items-center">
                <label for="valid_from" class="form-label me-3" style="width: 150px;">Berlaku Dari</label>
                <input type="date" id="valid_from" name="valid_from" class="form-control custom-input" value="{{ old('valid_from', $voucher->valid_from->format('Y-m-d')) }}" required>
            </div>

            <div class="mb-3 d-flex align-items-center">
                <label for="valid_until" class="form-label me-3" style="width: 150px;">Berlaku Sampai</label>
                <input type="date" id="valid_until" name="valid_until" class="form-control custom-input" value="{{ old('valid_until', $voucher->valid_until->format('Y-m-d')) }}" required>
            </div>

            <!-- Tombol Kembali dan Simpan Voucher -->
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('vouchers.index') }}" class="btn btn-secondary custom-button">Kembali</a>
                <button type="submit" class="btn btn-primary custom-button">Perbarui Voucher</button>
            </div>
        </form>
    </div> 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
