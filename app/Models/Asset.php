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
        'is_active',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function personnelAssets()
    {
        return $this->hasMany(PersonnelAsset::class);
    }

    /**
     * Check if the asset is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    /**
     * Scope para obtener solo los assets pendientes asignados.
     */
    public function scopeAssigned($query)
    {
        return $query->whereHas('personnelAssets', function ($q) {
            $q->whereNull('confirmation_date');
        });
    }

    /**
     * Scope para obtener IDs de assets ya asignados.
     */
    public function scopeAssignedAssetIds($query)
    {
        return \App\Models\PersonnelAssetPending::whereNull('confirmation_date')
            ->pluck('asset_id')
            ->toArray();
    }

    /**
     * Scope para obtener solo los assets que NO están asignados (disponibles)
     */
    public function scopeAvailable($query)
    {
        return $query->whereNotIn('id', function ($sub) {
            $sub->select('asset_id')->from('personnel_assets');
        })
        ->whereNotIn('id', function ($sub) {
            $sub->select('asset_id')->from('personnel_assets_pending');
        })
        ->where('is_active', true);
    }
}
