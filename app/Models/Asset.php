<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /**
     * Scope para el buscador general de DataTables.
     * El scope se encarga de revisar si el valor no está vacío.
     */
    public function scopeSearch(Builder $query, ?string $searchValue): void
    {
        if ($searchValue) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('inventory_number', 'like', "%{$searchValue}%")
                    ->orWhere('model', 'like', "%{$searchValue}%")
                    ->orWhere('serial_number', 'like', "%{$searchValue}%")
                    ->orWhereHas('brand', function ($brandQuery) use ($searchValue) {
                        $brandQuery->where('name', 'like', "%{$searchValue}%");
                    })
                    ->orWhereHas('category', function ($catQuery) use ($searchValue) {
                        $catQuery->where('name', 'like', "%{$searchValue}%");
                    });
            });
        }
    }

    /**
     * Scope para filtrar por nombre de estado (Condición).
     */
    public function scopeFilterByStatus(Builder $query, ?string $statusName): void
    {
        if ($statusName) {
            // Usamos una función de flecha (PHP 7.4+), es más limpio
            $query->whereHas('status', fn($q) => $q->where('name', $statusName));
        }
    }

    /**
     * Scope para filtrar por Activo/Inactivo (Estado).
     */
    public function scopeFilterByState(Builder $query, ?string $state): void
    {
        if ($state) {
            $isActive = $state === 'Activo';
            $query->where('is_active', $isActive);
        }
    }

    /**
     * Scope para filtrar por nombre de categoría.
     */
    public function scopeFilterByCategory(Builder $query, ?string $categoryName): void
    {
        if ($categoryName) {
            $query->whereHas('category', fn($q) => $q->where('name', $categoryName));
        }
    }

    /**
     * Scope para filtrar por nombre de marca.
     */
    public function scopeFilterByBrand(Builder $query, ?string $brandName): void
    {
        if ($brandName) {
            $query->whereHas('brand', fn($q) => $q->where('name', $brandName));
        }
    }
}
