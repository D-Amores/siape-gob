<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 
        'manager_name'
    ];

    public function personnel()
    {
        return $this->hasMany(Personnel::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
