<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stationaries extends Model
{
    use HasFactory;

    public const DIAJUKAN = 1;
    public const DITERIMA = 2;
    public const DITOLAK = 3;

    protected $fillable = [
        'id_user',
        'id_departemen',
        'kode',
        'id_produk',
        'keterangan',
        'id_status',
        'catatan',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

    public function product()
    {
        return $this->hasOne(Products::class, 'id', 'id_produk');
    }
}
