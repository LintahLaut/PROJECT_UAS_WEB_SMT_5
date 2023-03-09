<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'id_pembeli',
        'id_produk',
        'tanggal_pembelian',
        'total_harga',
    ];
}
