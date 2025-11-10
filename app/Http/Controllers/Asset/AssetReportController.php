<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Http\Requests\Asset\StoreAssetReportRequest;
use App\Models\MaintenanceReport;
use App\Models\MaintenanceReportLog;
use App\Services\Tools;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class AssetReportController extends Controller
{
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
            // Generar folio
            $year = date('Y');
            $reportCount = MaintenanceReport::whereYear('created_at', $year)->count() + 1;
            $folio = Tools::generateFolio('SRV', $reportCount, $year);
            $reportedAt = now();

            // Crear reporte de mantenimiento
            $maintenanceReport = MaintenanceReport::create([
                'folio' => $folio,
                'asset_id' => $requestData['asset_id'],
                'reported_by' => $requestData['reported_by'],
                'status_id' => 1, // Estado inicial: Pendiente, estoy suponiendo que es 1, ahi lo cambias PENELITI
                'description' => $requestData['description'],
                'observation' => $requestData['observation'] ?? null,
                'reported_at' => $reportedAt,
            ]);

            // Crear log del reporte de mantenimiento
            MaintenanceReportLog::create([
                'maintenance_report_id' => $maintenanceReport->id,
                'personnel_id' => $requestData['reported_by'],
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
