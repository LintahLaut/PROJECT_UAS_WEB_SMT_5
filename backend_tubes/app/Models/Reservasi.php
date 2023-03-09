<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'sesi',
        'id_layanan',
        'nama_customer',
    ];

    public function layanans()
    {
        return $this->belongsTo(Layanan::class,'id_layanan');
    }


}