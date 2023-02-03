<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = "pembelian";
    protected $primaryKey = "id";
    protected $fillable = [
        'id_barang',
        'total',
        'is_validate',
        'created_by',
    ];
}
