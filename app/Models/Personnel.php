<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Personnel extends Model
{
    use HasFactory;

    protected $table = 'personnel';

    protected $fillable = [
        'name',
        'last_name',
        'middle_name',
        'phone',
        'email',
        'is_active',
        'area_id'
    ];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }
    /**
     * Deactivate the personnel and their associated user account.
     */
    public function deactivate()
    {
        if (!$this->is_active) return;

        $this->update(['is_active' => false]);

        if ($this->user) {
            $this->user->update(['is_active' => false]);
        }
    }

    /**
     * Verifies if the personnel is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Scopes for querying personnel with specific relationships.
     */
    public function scopeWithArea($query)
    {
        return $query->with('area');
    }

    public function scopeWithUser($query)
    {
        return $query->with('user');
    }

    public function scopeExcludeCurrent($query)
    {
        $user = Auth::user();
        if ($user && $user->personnel_id) {
            return $query->where('id', '!=', $user->personnel_id);
        }
        return $query;
    }


    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function assignedAssets()
    {
        return $this->hasMany(PersonnelAsset::class, 'assigner_id');
    }
    public function receivedAssets()
    {
        return $this->hasMany(PersonnelAsset::class, 'receiver_id');
    }

    public function syncWithUser(): void
    {
        if (!$this->user) return;

        $updateData = [];

        // Solo actualizar si cambió el estado activo
        if ($this->isDirty('is_active')) {
            $updateData['is_active'] = $this->is_active;
        }

        // Solo actualizar si cambió el área
        if ($this->isDirty('area_id')) {
            $updateData['area_id'] = $this->area_id;
        }

        if (!empty($updateData)) {
            $this->user->update($updateData);
        }
    }

    public function syncDestroyWithUser(): void
    {
        if (!$this->user) return;

         $this->user->update([
            'is_active' => false,
            'area_id' => null,
        ]);
    }

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($personnel) {
            $personnel->syncWithUser();
        });
        // Al eliminar un personal
        static::deleting(function ($personnel) {
            $personnel->syncDestroyWithUser();
        });
    }

}
