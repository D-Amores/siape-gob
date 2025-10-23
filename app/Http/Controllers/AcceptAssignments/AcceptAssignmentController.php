<?php

namespace App\Http\Controllers\AcceptAssignments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PersonnelAsset;
use App\Models\PersonnelAssetPending;
use Carbon\Carbon;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AcceptAssignmentController extends Controller
{
    public function accept(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user || !$user->personnel_id) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Usuario no válido o sin personal asignado.'
                ], 403);
            }

            DB::transaction(function () use ($request, $user) {
                // Encontrar la asignación pendiente
                $pending = PersonnelAssetPending::find($request->id);

                if (!$pending) {
                    throw new \Exception('Asignación pendiente no encontrada.');
                }

                // Verificar que pertenece al usuario
                if ($pending->receiver_id !== $user->personnel_id) {
                    throw new \Exception('No tienes permiso para aceptar esta asignación.');
                }

                // Crear el registro en PersonnelAsset
                $personnelAsset = PersonnelAsset::create([
                    'assignment_date' => $pending->assignment_date,
                    'confirmation_date' => Carbon::now(),
                    'asset_id' => $pending->asset_id,
                    'assigner_id' => $pending->assigner_id,
                    'receiver_id' => $pending->receiver_id,
                    'path_acceptance_doc' => 'pending', // Temporal
                ]);

                // Generar PDF
                $mpdf = new Mpdf();
                $html = view('pdf.assignment_acceptance', [
                    'assignment' => $personnelAsset->load(['assigner', 'receiver', 'asset'])
                ])->render();
                
                $mpdf->WriteHTML($html);

                $filename = 'asignacion_' . $personnelAsset->id . '.pdf';
                $path = storage_path('app/public/acceptances/' . $filename);

                // Crear carpeta si no existe
                if (!file_exists(dirname($path))) {
                    mkdir(dirname($path), 0775, true);
                }

                // Guardar PDF en disco
                $mpdf->Output($path, Destination::FILE);

                // Actualizar la ruta del PDF
                $personnelAsset->path_acceptance_doc = 'storage/acceptances/' . $filename;
                $personnelAsset->save();

                // Eliminar el registro pendiente
                $pending->delete();
            });

            // Obtener el registro creado para generar la URL
            $personnelAsset = PersonnelAsset::where('receiver_id', $user->personnel_id)
                ->latest()
                ->first();

            $pdfUrl = asset($personnelAsset->path_acceptance_doc);

            return response()->json([
                'ok' => true,
                'message' => 'Asignación aceptada correctamente.',
                'pdfUrl' => $pdfUrl
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al aceptar la asignación: ' . $e->getMessage()
            ], 500);
        }
    }

    // Método para descargar PDF individual
    public function generatePdf($id)
    {
        $personnelAsset = PersonnelAsset::with(['assigner', 'receiver', 'asset'])->find($id);

        if (!$personnelAsset) {
            abort(404, 'Asignación no encontrada');
        }

        $path = storage_path('app/public/acceptances/asignacion_' . $id . '.pdf');

        if (!file_exists($path)) {
            abort(404, 'PDF no encontrado');
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="asignacion_' . $id . '.pdf"'
        ]);
    }
}