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
        'nomor_pengajuan',
        'id_user',
        'id_produk',
        'id_supervisor',
        'keterangan',
        'id_status'
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
