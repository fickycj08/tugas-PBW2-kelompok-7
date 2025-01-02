<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Transaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_id';

    protected $fillable = [
        'customer_id',
        'user_id',
        'service_type',
        'weight',
        'price',
        'payment_status',
        'service_duration',
        'received_at',
        'estimated_finish_at',
        'finished_at',
        'status',
        'unit_type',
        'payment_method',
        'address', // Tambahkan ini
        'rejection_reason', // Tambahkan ini
    ];

    /**
     * Cast attributes to specific data types.
     */
    protected $casts = [
        'received_at' => 'datetime',
        'estimated_finish_at' => 'datetime',
        'finished_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke model Customer.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Relasi ke model User (Kasir/Admin yang melakukan transaksi).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke model Voucher.
     */
    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', 'voucher_id');
    }
}
