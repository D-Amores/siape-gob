<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Http\Requests\Asset\StoreAssetReportRequest;
use App\Http\Requests\Asset\ApiAssetReportRequest;
use App\Models\MaintenanceReport;
use App\Models\MaintenanceReportLog;
use App\Models\Status;
use App\Models\Asset;
use App\Services\Tools;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AssetReportController extends Controller
{
    public function reportApi(ApiAssetReportRequest $request)
    {
        $response = ['ok' => false, 'message' => 'Error inesperado al obtener los reportes.'];
        $statusCode = 500;

        try{
            $requestData = $request->validated();

            switch ($requestData['option']) {
                case 'all':
                    $reports = MaintenanceReport::allReports()->get();
                    break;
                case 'open':
                    $reports = MaintenanceReport::openReports()->get();
                    break;
                case 'closed':
                    $reports = MaintenanceReport::closedReports()->get();
                    break;
                case 'tracking':
                    $reports = MaintenanceReport::reportsWithTracking()->get();
                    break;
                default:
                    $reports = [];
                    break;
            }

            $mappedReports = $reports->map(function ($report) {
                return [
                    'folio' => $report->folio,
                    'asset' => $report->asset->asset_name,
                    'description' => $report->description ?? 'Sin descripción',
                    'status' => $report->status->name ?? 'Desconocido',
                    'is_closed' => $report->is_closed,
                    'reported_by' => $report->reporter->full_name ?? '—',
                    'reported_at' => optional($report->reported_at)->format('d/m/Y H:i') ?? '—',
                    'id' => $report->id,
                ];
            });

            $response = [
                'ok' => true,
                'message' => 'Reportes obtenidos correctamente.',
                'data' => $mappedReports
            ];
            $statusCode = 200;
        }catch(\Exception $e){
            Log::error('Error al obtener los reportes de mantenimiento: ' . $e->getMessage());
            $response['message'] = 'Error al obtener los reportes de mantenimiento.';
            $statusCode = 500;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('assets.reports.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssetReportRequest $request)
    {
        $response = ['ok' => false, 'message' => 'Error inesperado al crear el reporte.'];
        $statusCode = 500;
        $requestData = $request->validated();

        try{
            /**
             * Verificar si el activo ya tiene un reporte abierto 
             */
            $asset = Asset::findOrFail($requestData['asset_id']); 
            
            // Usar el método de instancia en lugar del scope
            if($asset->hasOpenMaintenanceReport()){
                $response['message'] = 'El activo ya tiene un reporte de mantenimiento abierto.';
                return response()->json($response, 400);
            }
            
            //$asset->update(['status_id' => 2]); // Actualizar estado del activo a "En Mantenimiento", estoy suponiendo que es 3, ahi lo cambias PENELITI
            // Generar folio
            $year = date('Y');
            $reportCount = MaintenanceReport::whereYear('created_at', $year)->count() + 1;
            $folio = Tools::generateFolio('SRV', $reportCount, $year);
            $reportedAt = now();

            // Crear reporte de mantenimiento
            $maintenanceReport = MaintenanceReport::create([
                'folio' => $folio,
                'asset_id' => $requestData['asset_id'],
                'reported_by' => Auth::user()->personnel_id,
                /**
                 * Actualizar estado del reporte a "Pendiente"
                 */
                'status_id' => Status::OPEN, // Estado inicial: Pendiente, estoy suponiendo que es 1, ahi lo cambias PENELITI
                'description' => $requestData['description'],
                //'observation' => $requestData['observation'] ?? null,
                'reported_at' => $reportedAt,
            ]);

            // Crear log del reporte de mantenimiento
            MaintenanceReportLog::create([
                'maintenance_report_id' => $maintenanceReport->id,
                'personnel_id' => Auth::user()->personnel_id,
                'action' => 'Creación de reporte',
                'comment' => 'Reporte creado con folio '.$folio,
            ]);

            $response['ok'] = true;
            $response['message'] = 'Reporte de mantenimiento creado exitosamente.';
            $response['data'] = $maintenanceReport;
            $statusCode = 201;


        }catch(QueryException $qe){
            Log::error('Error al crear el reporte de mantenimiento: '.$qe->getMessage());
            $response['message'] = 'Error al crear el reporte de mantenimiento.';
            $statusCode = 500;
        }catch(\Exception $e){
            Log::error('Error inesperado al crear el reporte de mantenimiento: '.$e->getMessage());
            $response['message'] = 'Error inesperado al crear el reporte de mantenimiento.';
            $statusCode = 500;
        }
        return response()->json($response, $statusCode);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
