<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $table = 'statuses';

    // Estado de reportes
    public const OPEN = 1;
    public const IN_PROGRESS = 2;
    public const CLOSED = 3;

    // Estado de activos
    public const AVAILABLE = 4;
    public const ON_MAINTENANCE = 5;
    public const DAMAGED = 6;


    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }
}
