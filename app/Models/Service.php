<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'nama_service',
        'harga'
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}