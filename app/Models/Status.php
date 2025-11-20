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

    public function maintenanceReports(): HasMany
    {
        return $this->hasMany(MaintenanceReport::class);
    }

    /**
     * Get maintenance statuses
     */
    public static function maintenanceStatuses()
    {
        return self::whereIn('id', [
            self::OPEN,
            self::IN_PROGRESS,
            self::FINALIZING,
            self::CLOSED,
        ])->get();
    }

    /**
     * Get maintenance statuses without OPEN and CLOSED
     */
    public static function maintenanceStatusesWithoutOpenAndClosed()
    {
        return self::whereIn('id', [
            self::IN_PROGRESS,
            self::FINALIZING,
        ])->get();
    }

    /**
     * Get asset statuses
     */
    public static function assetStatuses()
    {
        return self::whereIn('id', [
            self::AVAILABLE,
            self::ON_MAINTENANCE,
            self::DAMAGED,
        ])->get();
    }

    /**
     * Get asset statuses only ON_MAINTENANCE
     */
    public static function assetStatusesOnlyOnMaintenance()
    {
        return self::whereIn('id', [
            self::ON_MAINTENANCE,
        ])->get();
    }

    /**
     * Get asset statuses without ON_MAINTENANCE
     */
    public static function assetStatusesWithoutOnMaintenance()
    {
        return self::whereIn('id', [
            self::AVAILABLE,
            self::DAMAGED,
        ])->get();
    }
}