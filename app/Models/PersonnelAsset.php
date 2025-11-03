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

    public function scopeAcceptedWithRelations($query)
    {
        return $query->with([
            'asset.brand',
            'asset.category',
            'assigner.area',
            'receiver.area'
        ])
        ->whereNotNull('confirmation_date')
        ->whereNull('unassignment_date')
        ->orderBy('confirmation_date', 'desc');
    }

}
