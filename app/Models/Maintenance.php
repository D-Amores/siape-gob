<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $fillable = [
        'maintenance_report_id',
        'performed_by',
        'start_date',
        'end_date',
        'work_done',
    ];

    public function maintenanceReport()
    {
        return $this->belongsTo(MaintenanceReport::class);
    }

    public function performer()
    {
        return $this->belongsTo(Personnel::class, 'performed_by');
    }

    public function scopeActiveMaintenance($query)
    {
        return $query->whereNull('end_date');
    }
}
