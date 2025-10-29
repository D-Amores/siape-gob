<?php

namespace App\Http\Controllers\AcceptAssignments;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PersonnelAssetPending;
use App\Http\Requests\User\AcceptAssignmentsApiRequest;
use App\Models\PersonnelAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class AcceptAssignmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user.accept_assignments');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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

    /**
     * API para obtener las asignaciones pendientes del usuario autenticado.
     */
    public function pendingAssignmentsApi()
    {
        try {
            $user = Auth::user();

            if (!$user || !$user->personnel_id) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Usuario no válido o sin personal asignado.'
                ], 403);
            }

            $assignments = PersonnelAssetPending::with(['asset', 'assigner', 'receiver'])
                ->where('receiver_id', $user->personnel_id)
                ->orderBy('assignment_date', 'desc')
                ->get();

            $data = $assignments->map(function ($item) {
                return [
                    'id' => $item->id,
                    'asset_name' => $item->asset->model ?? 'Sin nombre',
                    'assigner_name' => $item->assigner->name ?? 'Desconocido',
                    'receiver_name' => $item->receiver->name ?? 'Desconocido',
                    'assignment_date' => $item->assignment_date ? $item->assignment_date->format('Y-m-d') : 'Sin fecha',
                ];
            });

            return response()->json([
                'ok' => true,
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al obtener asignaciones pendientes.',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }

    public function acceptAssignmentApi(AcceptAssignmentsApiRequest $request)
    {
        try {
            $user = Auth::user();

            if (!$user || !$user->personnel_id) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Usuario no válido o sin personal asignado.'
                ], 403);
            }

            $pending = PersonnelAssetPending::find($request->id);

            if (!$pending) {
                return response()->json([
                    'ok' => false,
                    'message' => 'La asignación pendiente no existe.'
                ], 404);
            }

            if ($pending->receiver_id !== $user->personnel_id) {
                return response()->json([
                    'ok' => false,
                    'message' => 'No tienes permiso para aceptar esta asignación.'
                ], 403);
            }

            /** @var \App\Models\PersonnelAsset|null $personnelAsset */
            $personnelAsset = null;

            DB::transaction(function () use ($pending, &$personnelAsset) {
                // Crear registro en PersonnelAsset sin path de PDF
                $personnelAsset = PersonnelAsset::create([
                    'assignment_date' => $pending->assignment_date,
                    'confirmation_date' => Carbon::now(),
                    'asset_id' => $pending->asset_id,
                    'assigner_id' => $pending->assigner_id,
                    'receiver_id' => $pending->receiver_id,
                    'path_acceptance_doc' => 'pending',
                ]);

                // Eliminar registro pendiente
                $pending->delete();
            });

            // Generar PDF usando el FormatoController
            $pdfUrl = route('assignment.pdf.download', $personnelAsset->id);

            return response()->json([
                'ok' => true,
                'message' => 'Asignación aceptada correctamente.',
                'pdfUrl' => $pdfUrl
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error al aceptar asignación: ' . $e->getMessage());
            return response()->json([
                'ok' => false,
                'message' => 'Error al aceptar la asignación.',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }

    public function downloadAssignmentPdf($id)
    {
        try {
            $user = Auth::user();
            $personnelAsset = PersonnelAsset::with(['asset', 'assigner', 'receiver'])
                ->findOrFail($id);

            // Verificar que el usuario tiene permiso para ver este PDF
            if ($personnelAsset->receiver_id !== $user->personnel_id) {
                abort(403, 'No tienes permisos para ver este documento.');
            }

            $pdfController = app(\App\Http\Controllers\FormatoController::class);
            return $pdfController->pdfAsignacion($personnelAsset->id);

        } catch (\Exception $e) {
            Log::error('Error al generar PDF: ' . $e->getMessage());
            abort(404, 'Documento no encontrado.');
        }
    }
}