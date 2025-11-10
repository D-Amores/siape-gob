<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Http\Requests\Asset\StoreAssetTrackingRequest;
use App\Http\Requests\Asset\UpdateAssetTrackingRequest;
use App\Models\Maintenance;
use App\Models\MaintenanceReport;
use App\Models\MaintenanceReportLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mpdf\Tag\Main;
use Mpdf\Tag\U;

class AssetTrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('assets.tracking.index');
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
    public function store(StoreAssetTrackingRequest $request)
    {
        $response = ['ok' => false, 'message' => 'Error inesperado al crear el seguimiento.'];
        $statusCode = 500;
        $requestData = $request->validated();
        $maintenanceReport = MaintenanceReport::find($requestData['maintenance_report_id']);

        try{
            $requestData['start_date'] = now();
            Maintenance::create($requestData);

            $maintenanceReport->update(['status_id' => 2]); // Estado: En Proceso, estoy suponiendo que es 2, ahi lo cambias PENELITI

            MaintenanceReportLog::create([
                'maintenance_report_id' => $requestData['maintenance_report_id'],
                'personnel_id' => $requestData['performed_by'],
                'action' => 'Seguimiento iniciado.',
                'comment' => 'El seguimiento del reporte ha sido iniciado por el personal.',
            ]);
            $response['ok'] = true;
            $response['message'] = 'Seguimiento creado exitosamente.';
            $statusCode = 201;
        }catch(\Exception $e){
            $response['message'] = 'Error al procesar la solicitud.';
            Log::error('Error al procesar la solicitud de seguimiento de activo: ' . $e->getMessage());
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
    public function update(UpdateAssetTrackingRequest $request, MaintenanceReport $report)
    {
        $response = ['ok' => false, 'message' => 'Error inesperado al actualizar el seguimiento.'];
        $statusCode = 500;
        $requestData = $request->validated();

        try{
            
            $response['ok'] = true;
            $response['message'] = 'Seguimiento actualizado exitosamente.';
            $statusCode = 200;
        }catch(\Exception $e){
            $response['message'] = 'Error al procesar la solicitud.';
            Log::error('Error al procesar la solicitud de actualización de seguimiento de activo: ' . $e->getMessage());
            $statusCode = 500;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maintenance $maintenance)
    {
        //
    }
}
