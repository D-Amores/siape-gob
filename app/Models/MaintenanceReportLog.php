<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceReportLog extends Model
{
    protected $fillable = [
        'maintenance_report_id',
        'personnel_id',
        'action',
        'comment',
    ];

    public function maintenanceReport()
    {
        return $this->belongsTo(MaintenanceReport::class);
    }

    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }
}
