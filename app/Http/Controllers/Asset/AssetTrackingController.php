<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Http\Requests\Asset\StoreAssetTrackingRequest;
use App\Http\Requests\Asset\UpdateAssetTrackingRequest;
use App\Http\Requests\Asset\CloseAssetTrackingRequest;
use App\Models\Maintenance;
use App\Models\MaintenanceReport;
use App\Models\MaintenanceReportLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
        return view('assets.tracking.create');
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
            $requestData['performed_by'] = Auth::user()->personnel_id;
            Maintenance::create($requestData);

            $maintenanceReport->update(['status_id' => 2]); // Estado: En Proceso, estoy suponiendo que es 2, ahi lo cambias PENELITI

            MaintenanceReportLog::create([
                'maintenance_report_id' => $requestData['maintenance_report_id'],
                'personnel_id' => Auth::user()->personnel_id,
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
            $report->update($requestData['status_id']);
            MaintenanceReportLog::create([
                'maintenance_report_id' => $report->id,
                'personnel_id' => Auth::user()->personnel_id,
                'action' => 'Seguimiento actualizado.',
                'comment' => $requestData['comment'] ?? 'No se proporcionó comentario.',
            ]);
            
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
    public function destroy(CloseAssetTrackingRequest $request, Maintenance $maintenance)
    {
        $response = ['ok' => false, 'message' => 'Error inesperado al cerrar el seguimiento.'];
        $statusCode = 500;
        $requestData = $request->validated();
        $report = MaintenanceReport::find($maintenance->maintenance_report_id);

        try{
            $report->update([
                'status_id' => 3, // Estado: Cerrado, estoy suponiendo que es 3, ahi lo cambias PENELITI
                'end_date' => now(), 
                'observation' => $requestData['observation'] ?? null
            ]); 

            $maintenance->update([
                'end_date' => now(),
                'status_id' => $requestData['status_id'],
                'work_done' => $requestData['work_done'] ?? null,
            ]);

            MaintenanceReportLog::create([
                'maintenance_report_id' => $maintenance->maintenance_report_id,
                'personnel_id' => Auth::user()->personnel_id,
                'action' => 'Seguimiento cerrado.',
                'comment' => $requestData['comment'] ?? 'No se proporcionó comentario.',
            ]);

            $response['ok'] = true;
            $response['message'] = 'Seguimiento cerrado exitosamente.';
            $statusCode = 200;
        }catch(\Exception $e){
            $response['message'] = 'Error al procesar la solicitud.';
            Log::error('Error al procesar la solicitud de cierre de seguimiento de activo: ' . $e->getMessage());
            $statusCode = 500;
        }
        return response()->json($response, $statusCode);
    }
}
