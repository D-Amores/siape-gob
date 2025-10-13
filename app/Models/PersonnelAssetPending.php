<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

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
}
