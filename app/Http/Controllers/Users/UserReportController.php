<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintenanceReport;
use Illuminate\Support\Facades\Auth;

class UserReportController extends Controller
{
    public function index()
    {
        return view('user.user_reports');
    }

    public function userReportsApi(Request $request)
    {
        try {
            $personnelId = Auth::user()->personnel_id;

            $reports = MaintenanceReport::where('reported_by', $personnelId)
                ->with([
                    'asset.brand',
                    'asset.category',
                    'asset.status',
                    'status',
                    'logs.personnel', 
                    'reporter'
                ])
                ->orderBy('reported_at', 'desc')
                ->get();

            // Formatear los datos para incluir información completa del asset
            $formattedReports = $reports->map(function ($report) {
                $asset = $report->asset;
                $assetData = null;
                
                if ($asset) {
                    $assetData = [
                        'id' => $asset->id,
                        'inventory_number' => $asset->inventory_number,
                        'model' => $asset->model,
                        'serial_number' => $asset->serial_number,
                        'cpu' => $asset->cpu,
                        'speed' => $asset->speed,
                        'memory' => $asset->memory,
                        'storage' => $asset->storage,
                        'description' => $asset->description,
                        'type' => $asset->type,
                        'brand_name' => $asset->brand->name ?? 'N/A',
                        'category_name' => $asset->category->name ?? 'N/A',
                        'status_name' => $asset->status_name,
                        'asset_name' => $asset->asset_name, 
                        'is_active' => $asset->is_active,
                    ];
                }

                // Formatear los logs para incluir información del personal
                $formattedLogs = $report->logs->map(function ($log) {
                    return [
                        'id' => $log->id,
                        'action' => $log->action,
                        'comment' => $log->comment,
                        'created_at' => $log->created_at,
                        'updated_at' => $log->updated_at,
                        'personnel' => $log->personnel ? [
                            'id' => $log->personnel->id,
                            'name' => $log->personnel->name,
                            'last_name' => $log->personnel->last_name,
                            'email' => $log->personnel->email,
                        ] : null
                    ];
                });

                return [
                    'id' => $report->id,
                    'folio' => $report->folio,
                    'asset' => $assetData,
                    'status' => $report->status,
                    'description' => $report->description,
                    'reported_at' => $report->reported_at,
                    'closed_at' => $report->closed_at,
                    'created_at' => $report->created_at,
                    'updated_at' => $report->updated_at,
                    'logs' => $formattedLogs,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedReports
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los reportes: ' . $e->getMessage()
            ], 500);
        }
    }
}
