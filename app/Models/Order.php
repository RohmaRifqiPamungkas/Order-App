<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasUuids;

    protected $fillable = [
        'nama_pemesan',
        'nomor_wa',
        'email',
        'nama_produk',
        'jumlah',
        'status',
    ];
}
