<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'name',
        'phone_number',
        'created_at',
        'address',
    ];

    // Relasi ke model Transaction
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'customer_id');
    }
}
