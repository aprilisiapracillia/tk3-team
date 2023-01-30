<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = "barang";
    protected $primaryKey = "id";
    protected $fillable = [
        'nama',
        'deskripsi',
        'jenis',
        'stok',
        'harga_beli',
        'harga_jual',
        'gambar',
        'created_by',
    ];
}
