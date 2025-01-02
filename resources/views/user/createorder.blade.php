<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css"
        rel="stylesheet">
</head>

<body class="user-create-order-body">
    <!-- Hero Section as Background -->
    <div class="user-create-order-hero">
        <section class="container text-center costume-section">
            <h1 class="display-4">I'm Ready To Go To</h1>
            <h1 class="display-4">Your <span class="display-5">Wardrobe!</span></h1>
            <section class="loginp">
                <a href="#" class="btn btn-dark mt-4 custom-login-btn" data-bs-toggle="modal"
                    data-bs-target="#loginModal">LOG IN -></a>
            </section>
        </section>

        <!-- Section with Bootstrap Cards -->
        <section class="container d-flex justify-content-around">
            <div class="card bg-dark text-white text-center custome-shape1" style="width: 18rem;">
                <i class="ph-handshake ph1"></i>
                <div class="card-body">
                    <h5 class="card-title">Pakaian Diterima</h5>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <i class="ph-arrow-fat-line-right ph-arrow"></i>
            </div>

            <div class="card bg-dark text-white text-center custome-shape1" style="width: 18rem;">
                <i class="ph-hourglass ph1"></i>
                <div class="card-body">
                    <h5 class="card-title">Pakaian Diproses</h5>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <i class="ph-arrow-fat-line-right ph-arrow"></i>
            </div>

            <div class="card bg-dark text-white text-center custome-shape1" style="width: 18rem;">
                <i class="ph-t-shirt ph1"></i>
                <div class="card-body">
                    <h5 class="card-title">Pakaian Selesai</h5>
                </div>
            </div>
        </section>
    </div>
    <!-- Overlay -->
    <div class="user-create-order-overlay"></div>

    <!-- Order Form -->
    <div class="user-create-order-form-container">
        <div class="card user-create-order-card" style="max-width: 400px;">
            <div class="card-body user-create-order-card-body">
                <a href="{{ route('user.dashboard') }}"
                    class="btn btn-link text-decoration-none mb-3 user-create-order-back">
                    <i class="ph ph-arrow-left"></i> Back
                </a>
                <h4 class="text-center user-create-order-title">New Transaction</h4>
                <form action="{{ route('user.orders.store') }}" method="POST">
                    @csrf
                    <div class="mb-3 user-create-order-field">
                        <label for="customer_name" class="form-label user-create-order-label">Nama Pelanggan</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ph-user-circle"></i></span>
                            <input type="text" name="customer_name" id="customer_name"
                                class="form-control user-create-order-input" placeholder="Nama Pelanggan" required>
                        </div>
                    </div>

                    <div class="mb-3 user-create-order-field">
                        <label for="phone_number" class="form-label user-create-order-label">No Telepon</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ph-phone"></i></span>
                            <input type="text" name="phone_number" id="phone_number"
                                class="form-control user-create-order-input" placeholder="No Telepon" required>
                        </div>
                    </div>

                    <div class="mb-3 user-create-order-field">
                        <label for="address" class="form-label user-create-order-label">Alamat Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ph-map-pin"></i></span>
                            <input type="text" name="address" id="address" class="form-control user-create-order-input"
                                placeholder="Alamat Lengkap" required>
                        </div>
                    </div>

                    <div class="mb-3 user-create-order-field">
                        <label for="service_type" class="form-label user-create-order-label">Jenis Layanan</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ph-folder"></i></span>
                            <select name="service_type" id="service_type" class="form-select user-create-order-select"
                                required>
                                <option value="">Pilih Jenis Layanan</option>
                                <option value="cuci_saja">Cuci Saja</option>
                                <option value="cuci_setrika">Cuci dan Setrika</option>
                                <option value="express">Express (1 Hari)</option>
                            </select>

                        </div>
                    </div>

                    <div class="mb-3 user-create-order-field">
    <label for="voucher_code" class="form-label user-create-order-label">Kode Voucher</label>
    <div class="input-group">
        <span class="input-group-text"><i class="ph-ticket"></i></span>
        <input type="text" name="voucher_code" id="voucher_code" class="form-control user-create-order-input" placeholder="Masukkan Kode Voucher">
    </div>
</div>


                    <button type="submit" class="btn btn-primary w-100 user-create-order-submit">Lanjut
                        Pembayaran</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/phosphor-icons"></script>
</body>

</html>