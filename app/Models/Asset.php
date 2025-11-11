<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
        'type',
        'brand_id',
        'category_id',
        'is_active',
        'status_id',
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

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
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
            $sub->select('asset_id')
            ->from('personnel_assets')
            ->whereNull('unassignment_date');
        })
        ->whereNotIn('id', function ($sub) {
            $sub->select('asset_id')->from('personnel_assets_pending');
        })
        ->where('is_active', true);
    }

    protected $appends = ['asset_name', 'status_name'];

    protected function assetName(): Attribute
    {
        return Attribute::get(function () {
            if ($this->model && $this->inventory_number) {
                return $this->model . '-' . $this->inventory_number;
            }
            return '—';
        });
    }

    protected function statusName(): Attribute
    {
        return Attribute::get(function () {
            return $this->status->name ?? 'Desconocido';
        });
    }
}
