<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/payment.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>

<body class="payment-page">
    <div class="payment-container">
        <div class="payment-card">
            <div class="payment-header">
                <h2>Pembayaran</h2>
                <p>Pastikan data pembayaran Anda sudah benar</p>
            </div>
            <div class="payment-body">
    <p><strong>Transaksi ID:</strong> {{ $transaction->transaction_id }}</p>
    @if($transaction->voucher)
        <p><strong>Kode Voucher:</strong> {{ $transaction->voucher->code }}</p>
        <p><strong>Diskon:</strong> {{ $transaction->voucher->discount }}%</p>
    @endif
    <p><strong>Total Harga:</strong> Rp {{ number_format($transaction->price, 0, ',', '.') }}</p>
    <p><strong>Saldo Akun Anda:</strong> Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}</p>

    <!-- Form Pembayaran -->
    <form action="{{ route('user.payment.process', $transaction->transaction_id) }}" method="POST">
        @csrf
        <!-- Pilih Metode Pembayaran -->
        <div class="payment-field">
            <label for="payment_method" class="payment-label">Metode Pembayaran</label>
            <select name="payment_method" id="payment_method" class="payment-select" required>
                <option value="">Pilih Metode</option>
                <option value="qris">QRIS</option>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="e_wallet">E-Wallet</option>
                @if(Auth::user()->balance >= $transaction->price)
                    <option value="balance">Saldo Akun</option>
                @endif
            </select>
        </div>

        <!-- Submit -->
        <button type="submit" class="payment-button">Bayar Sekarang</button>
    </form>
</div>

        </div>
    </div>
</body>

</html>