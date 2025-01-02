<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    // Tentukan primary key
    protected $primaryKey = 'voucher_id';

    // Tambahkan kolom yang bisa diisi (fillable)
    protected $fillable = [
        'code',
        'discount',
        'valid_from',
        'valid_until',
    ];

    // Casting kolom tanggal
    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
    ];
}
