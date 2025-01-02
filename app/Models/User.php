<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Primary key yang digunakan di tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';  // Set primary key menjadi 'user_id'

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true; // Jika primary key bersifat auto-increment

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'int'; // Tipe data dari primary key

    /**
     * Nama tabel (opsional jika nama tabel sesuai konvensi Laravel).
     *
     * @var string
     */
    protected $table = 'users'; // Pastikan nama tabel sesuai

    /**
     * Attributes yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Tambahkan 'role' jika digunakan untuk login
    ];

    /**
     * Attributes yang harus disembunyikan untuk serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attributes yang harus di-cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel 10+ mendukung hashed password secara otomatis
    ];
}
