<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->hasMany(PersonalAsset::class, 'assigner_id');
    }
    public function receivedAssets()
    {
        return $this->hasMany(PersonalAsset::class, 'receiver_id');
    }
}
