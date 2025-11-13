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
        'description',
    ];

    protected $table = 'statuses';

    // Estado de activos
    public const AVAILABLE = 2;
    public const ON_MAINTENANCE = 3;
    public const DAMAGED = 4;

    // Estado de reportes
    public const OPEN = 5;
    public const IN_PROGRESS = 6;
    public const FINALIZING = 7;
    public const CLOSED = 8;



    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }
}
