<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Http\Requests\Asset\StoreAssetTrackingRequest;
use App\Http\Requests\Asset\UpdateAssetTrackingRequest;
use App\Http\Requests\Asset\CloseAssetTrackingRequest;
use App\Models\Maintenance;
use App\Models\MaintenanceReport;
use App\Models\MaintenanceReportLog;
use App\Models\Asset;
use App\Models\Status;
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

            $maintenanceReport->update(['status_id' => Status::IN_PROGRESS]); // Estado: En Proceso, estoy suponiendo que es 2, ahi lo cambias PENELITI

            /**
             * Actualizar el estado del bien a "En Mantenimiento"
             */
            $asset = $maintenanceReport->asset;
            $asset->update(['status_id' => Status::ON_MAINTENANCE]);
            MaintenanceReportLog::create([
                'maintenance_report_id' => $maintenanceReport->id,
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

        try {
            $asset = $report->asset;
            if (!$asset) {
                Log::warning("Intento de actualizar seguimiento sin activo asociado", [
                    'report_id' => $report->id,
                    'user_id' => Auth::id(),
                ]);

                return response()->json([
                    'ok' => false,
                    'message' => 'No se encontró un activo asociado a este reporte.'
                ], 404);
            }

            // 🔹 Actualizar estado del activo si se proporciona
            if (isset($requestData['asset_status_id'])) {
                $asset->update(['status_id' => $requestData['asset_status_id']]);
            }

            // 🔹 Actualizar estado del reporte si se proporciona
            if (isset($requestData['status_id'])) {
                $report->update(['status_id' => $requestData['status_id']]);
            }

            // 🔹 Registrar acción en el log
            MaintenanceReportLog::create([
                'maintenance_report_id' => $report->id,
                'personnel_id' => Auth::user()->personnel_id,
                'action' => 'Seguimiento actualizado.',
                'comment' => $requestData['comment'] ?? 'Se realizó una nueva acción sobre el seguimiento.',
            ]);

            $response['ok'] = true;
            $response['message'] = 'Seguimiento actualizado exitosamente.';
            $statusCode = 200;
        } catch (\Exception $e) {
            $response['message'] = 'Error al procesar la solicitud.';
            Log::error('Error al actualizar seguimiento: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            $statusCode = 500;
        }

        return response()->json($response, $statusCode);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CloseAssetTrackingRequest $request, MaintenanceReport $report)
    {
        $response = ['ok' => false, 'message' => 'Error inesperado al cerrar el seguimiento.'];
        $statusCode = 500;
        $requestData = $request->validated();
        try{
            $maintenance = $report->maintenance()->activeMaintenance()->first();
            if (!$maintenance) {
                return response()->json([
                    'ok' => false,
                    'message' => 'No se encontró un seguimiento activo para este reporte.'
                ], 404);
            }

            /**
             * Actualizar el estado del bien al proporcionado
             */
            $asset = $report->asset; 
            $asset->update(['status_id' => $requestData['asset_status_id']]); // Actualizar estado del activo al proporcionado
            
            $report->update([
                'status_id' => Status::CLOSED, // Estado: Cerrado, estoy suponiendo que es 3, ahi lo cambias PENELITI
                'closed_at' => now(), 
                'observation' => $requestData['observation'] ?? null
            ]); 

            $maintenance->update([
                'end_date' => now(),
                'work_done' => $requestData['work_done'] ?? null,
            ]);

            MaintenanceReportLog::create([
                'maintenance_report_id' => $report->id,
                'personnel_id' => Auth::user()->personnel_id,
                'action' => 'Seguimiento cerrado.',
                'comment' => 'El seguimiento del reporte ha sido cerrado por el personal encargado.',
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
