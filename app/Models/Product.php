<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    protected $fillable = [
        'nama_produk',
        'harga',
        'stok',
        'gambar'
    ];

    protected $appends = ['gambar_url'];

public function getGambarUrlAttribute()
{
    return $this->gambar
        ? asset('storage/' . $this->gambar)
        : null;
}
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}