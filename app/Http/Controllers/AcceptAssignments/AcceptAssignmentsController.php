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
                    'assignment_date' => $item->assignment_date->format('Y-m-d'),
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

            // Generar URL para descargar el PDF (sin guardar)
            $pdfUrl = route('accept-assignments.pdf', ['id' => $personnelAsset->id]);

            return response()->json([
                'ok' => true,
                'message' => 'Asignación aceptada correctamente.',
                'pdfUrl' => $pdfUrl
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error al aceptar asignación: ' . $e->getMessage());
            return response()->json([
                'ok' => false,
                'message' => 'Error al aceptar la asignación: ' . $e->getMessage(),
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }

    public function generatePdf($id)
    {
        try {
            $user = Auth::user();
            $personnelAsset = PersonnelAsset::with(['assigner', 'receiver', 'asset'])->find($id);

            if (!$personnelAsset) {
                abort(404, 'Asignación no encontrada');
            }

            // Verificar que el usuario tiene permisos para ver este PDF
            if ($personnelAsset->receiver_id !== $user->personnel_id) {
                abort(403, 'No tienes permiso para ver este documento');
            }

            // Configurar mPDF
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'default_font' => 'dejavusans',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 15,
                'margin_bottom' => 15,
                'margin_header' => 5,
                'margin_footer' => 5,
            ]);

            // Generar HTML del PDF
            $html = view('pdf.assignment_acceptance', [
                'assignment' => $personnelAsset
            ])->render();

            $mpdf->WriteHTML($html);

            $filename = 'acta_aceptacion_' . $personnelAsset->id . '_' . date('Y-m-d') . '.pdf';

            // Devolver el PDF directamente sin guardar
            return response($mpdf->Output($filename, 'S'), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);

        } catch (\Exception $e) {
            Log::error('Error generando PDF: ' . $e->getMessage());
            abort(500, 'Error al generar el PDF');
        }
    }
}