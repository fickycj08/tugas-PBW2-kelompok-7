<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Transaction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
</head>

<body class="transaction-bg">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="transaction-card p-4">
            <!-- Tombol Kembali -->
            <a href="{{ route('dashboard') }}" class="btn transaction-back-btn">
                <i class="ph-arrow-left"></i> Kembali
            </a>

            <!-- Judul Halaman -->
            <h2 class="transaction-title text-center">New Transaction</h2>

            <!-- Form -->
            <form action="{{ route('transactions.store') }}" method="POST" class="transaction-form">
                @csrf
                <div class="row g-3">
                    <!-- Pilih Pelanggan -->
                    <div class="col-md-6">
                        <label for="customer_id" class="form-label">Nama Pelanggan</label>
                        <select name="customer_id" id="customer_id" class="form-select searchable-select" required>
                            <option value="" disabled selected>Pilih Pelanggan</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->customer_id }}" data-phone="{{ $customer->phone_number }}">
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Berat -->
                    <div class="col-md-6 input-group">
                        <input type="number" name="weight" id="weight" class="form-control" placeholder="Berat" min="0.1" step="0.1" required>
                        <span class="input-group-text">Kg</span>
                    </div>

                    <!-- No Telepon -->
                    <div class="col-md-6">
                        <input type="text" id="phone_number" class="form-control" placeholder="No Telepon" disabled>
                    </div>

                    <!-- Jenis Layanan -->
                    <div class="col-md-6">
                        <label for="service_type" class="form-label">Jenis Layanan</label>
                        <select name="service_type" id="service_type" class="form-select" required>
                            <option value="cuci_saja">Cuci Saja</option>
                            <option value="cuci_setrika">Cuci + Setrika</option>
                            <option value="express">Express</option>
                        </select>
                    </div>

                    <!-- Tanggal Diterima -->
                    <div class="col-md-6">
                        <label for="received_at" class="form-label">Tanggal Diterima</label>
                        <input type="date" id="received_at" class="form-control" value="{{ now()->format('Y-m-d') }}" disabled>
                    </div>

                    <!-- Status Pembayaran -->
                    <div class="col-md-6">
                        <label for="payment_status" class="form-label">Status Pembayaran</label>
                        <select name="payment_status" id="payment_status" class="form-select">
                            <option value="pending">Pending</option>
                            <option value="success">Lunas</option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary transaction-submit-btn">Lanjutkan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            // Inisialisasi Select2
            $('.searchable-select').select2({
                placeholder: "Pilih Pelanggan",
                allowClear: true,
                width: '100%'
            });

            // Isi otomatis nomor telepon berdasarkan pilihan pelanggan
            $('#customer_id').on('change', function () {
                const selectedOption = $(this).find(':selected');
                const phoneNumber = selectedOption.data('phone');
                $('#phone_number').val(phoneNumber || '');
            });
        });
    </script>
</body>

</html>
