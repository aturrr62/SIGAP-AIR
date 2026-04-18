<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZonaWilayah extends Model
{
    use HasFactory;

    protected $table = 'zona_wilayah';

    protected $fillable = [
        'nama_zona',
        'kode_zona',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
