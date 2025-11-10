<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceReport extends Model
{
    protected $fillable = [
        'folio',
        'asset_id',
        'reported_by',
        'status_id',
        'description',
        'observation',
        'reported_at',
        'closed_at',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function reporter()
    {
        return $this->belongsTo(Personnel::class, 'reported_by');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }
}
