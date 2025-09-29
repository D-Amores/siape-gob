<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_number',
        'model',
        'serial_number',
        'cpu',
        'speed',
        'memory',
        'storage',
        'description',
        'brand_id',
        'category_id',
    ];

}
