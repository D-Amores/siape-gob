<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonnelAssetPending extends Model
{
    use HasFactory;

    protected $table = 'personnel_assets_pending';

    protected $fillable = [
        'assignment_date',
        'confirmation_date',
        'asset_id',
        'assigner_id',
        'receiver_id',
    ];

    protected $casts = [
        'assignment_date' => 'date',
        'confirmation_date' => 'date',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function assigner()
    {
        return $this->belongsTo(Personnel::class, 'assigner_id');
    }

    public function receiver()
    {
        return $this->belongsTo(Personnel::class, 'receiver_id');
    }
}
