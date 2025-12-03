<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonnelAsset extends Model
{
    use HasFactory;

    protected $table = 'personnel_assets';

    protected $fillable = [
        'assignment_date',
        'confirmation_date',
        'path_acceptance_doc',
        'path_respaldo_acceptance',
        'status',
        'unassignment_date',
        'asset_id',
        'assigner_id',
        'receiver_id',
    ];

    protected $casts = [
        'assignment_date' => 'date',
        'confirmation_date' => 'date',
        'unassignment_date' => 'date',
    ];

    /**
     * Get the asset that is assigned.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Get the personnel who assigned the asset.
     */
    public function assigner(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'assigner_id');
    }

    /**
     * Get the personnel who received the asset.
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'receiver_id');
    }

    public function scopeAccepted($query)
    {
        return $query->whereNotNull('confirmation_date')
                    ->whereNull('unassignment_date');
    }

    public function scopeAssignmentsList($query, $personnelId)
    {
        return $query->with([
            'asset.brand',
            'asset.category',
            'asset.status',
            'receiver',
        ])
        ->where('receiver_id', $personnelId);
    }

    public function scopeAcceptedWithRelations($query, $name = null)
    {
        $query->with([
            'asset.brand',
            'asset.category',
            'asset.status',
        ])
        ->whereNotNull('confirmation_date')
        ->whereNull('unassignment_date');

        if(!empty($name) && strlen($name) >= 3){
            $query->whereHas('receiver', function ($q) use ($name) {
                $q->whereRaw("
                    CONCAT(name, ' ', last_name, ' ', COALESCE(middle_name, ''))
                    LIKE ?
                ", [$name . '%']);
            });
        }

        return $query->orderBy('confirmation_date', 'desc');
    }

    public function scopeHistoricWithRelations($query)
    {
        return $query->with([
            'asset.brand',
            'asset.category',
            'asset.status',
        ])
        ->whereNotNull('confirmation_date')
        ->whereNotNull('unassignment_date')
        ->orderBy('unassignment_date', 'desc');
    }

    /**
     * Get doc.
     */
    public function getAcceptanceDocUrlAttribute()
    {
        if (!$this->path_acceptance_doc || trim($this->path_acceptance_doc) === 'pending') {
            return asset('storage/' . ltrim(str_replace('public/', '', $this->path_respaldo_acceptance), '/'));
        }

        return asset('storage/' . ltrim(str_replace('public/', '', $this->path_acceptance_doc), '/'));
    }
}