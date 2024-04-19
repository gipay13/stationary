<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_kategori',
        'id_supplier',
        'kode',
        'nama'
    ];

    public function category()
    {
        return $this->hasOne(Categories::class, 'id', 'id_kategori');
    }

    public function supplier()
    {
        return $this->hasOne(Suppliers::class, 'id', 'id_supplier');
    }
}
