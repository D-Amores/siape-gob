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
        //'observation',
        'reported_at',
        'closed_at',
    ];

    protected $casts = [
        'reported_at' => 'datetime',
        'closed_at' => 'datetime',
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

    public function maintenance()
    {
        return $this->hasOne(Maintenance::class);
    }

    public function logs()
    {
        return $this->hasMany(MaintenanceReportLog::class);
    }

    public function scopeAllReports($query)
    {
        return $query->with(['asset', 'status', 'logs']);
    }

    public function scopeOpenReports($query)
    {
        return $query->with(['asset', 'status', 'logs'])
            ->whereNull('closed_at') // reporte no cerrado
            ->whereDoesntHave('maintenance', function ($q) {
                $q->whereNull('end_date'); // sin seguimientos activos
            });
    }

    public function scopeReportsWithTracking($query)
    {
        return $query->with(['asset', 'status', 'logs'])
            ->whereHas('maintenance', function ($q) {
                $q->whereNull('end_date'); // tiene seguimiento activo
            });
    }

    public function scopeClosedReports($query)
    {
        return $query->with(['asset', 'status', 'logs'])
                    ->whereNotNull('closed_at'); // Assuming 'closed_at' being not null means the report is closed
    }
}
