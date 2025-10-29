<?php

namespace App\Http\Controllers\AcceptAssignments;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PersonnelAsset;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AssetsUniqueUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user.assets_user');
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
        try {
            $user = Auth::user();

            if (!$user || !$user->personnel_id) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Usuario no válido o sin personal asignado.'
                ], 403);
            }

            // Buscar la asignación específica del usuario
            $personnelAsset = PersonnelAsset::with([
                'asset.brand', 
                'asset.category',
                'assigner',
                'receiver'
            ])
                ->where('id', $id)
                ->where('receiver_id', $user->personnel_id)
                ->first();

            if (!$personnelAsset) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Asignación no encontrada o no autorizada.'
                ], 404);
            }

            $asset = $personnelAsset->asset;

            // Determinar el estado del documento
            $pathAcceptanceDoc = 'No disponible';
            if ($personnelAsset->path_acceptance_doc) {
                $pathAcceptanceDoc = (strtolower($personnelAsset->path_acceptance_doc) === 'pending') 
                    ? 'pending' 
                    : $personnelAsset->path_acceptance_doc;
            }

            $data = [
                // Información del bien
                'inventory_number' => $asset->inventory_number ?? '—',
                'model' => $asset->model ?? '—',
                'serial_number' => $asset->serial_number ?? '—',
                'brand' => $asset->brand->name ?? '—',
                'category' => $asset->category->name ?? '—',
                'type' => $asset->type ?? '—',
                'status' => $asset->is_active ? 'Activo' : 'Inactivo',
                'cpu' => $asset->cpu ?? '—',
                'speed' => $asset->speed ?? '—',
                'memory' => $asset->memory ?? '—',
                'storage' => $asset->storage ?? '—',
                'description' => $asset->description ?? 'Sin descripción',
                'created_at' => $asset->created_at ? $asset->created_at->toISOString() : null,
                
                // Información de la asignación
                'assignment_date' => $personnelAsset->assignment_date ? $personnelAsset->assignment_date->format('d/m/Y') : '—',
                'confirmation_date' => $personnelAsset->confirmation_date ? $personnelAsset->confirmation_date->format('d/m/Y') : 'Pendiente',
                'assigner_name' => optional($personnelAsset->assigner)
                    ? trim("{$personnelAsset->assigner->last_name} {$personnelAsset->assigner->middle_name} {$personnelAsset->assigner->name} ")
                    : 'Desconocido',
                'receiver_name' => $personnelAsset->receiver?->name
                    ? trim("{$personnelAsset->receiver?->last_name} {$personnelAsset->receiver?->middle_name} {$personnelAsset->receiver?->name} ")
                    : 'Desconocido',
                'path_acceptance_doc' => $personnelAsset->path_acceptance_doc ?? 'No disponible'
            ];

            return response()->json([
                'ok' => true,
                'data' => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al obtener detalles del bien.',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $user = Auth::user();

            if (!$user || !$user->personnel_id) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Usuario no válido o sin personal asignado.'
                ], 403);
            }

            // Buscar la asignación específica del usuario
            $personnelAsset = PersonnelAsset::with(['receiver'])
                ->where('id', $id)
                ->where('receiver_id', $user->personnel_id)
                ->first();

            if (!$personnelAsset) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Asignación no encontrada o no autorizada.'
                ], 404);
            }

            $request->validate([
                'acceptance_document' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240' // 10MB máximo
            ]);

            // Obtener el archivo
            $file = $request->file('acceptance_document');
            
            // Generar la estructura de carpetas
            $monthYear = Carbon::now()->locale('es')->translatedFormat('F-Y'); // ejemplo: "octubre-2024"

            // Sanitizar también el nombre de la carpeta del mes
            $monthYear = $this->sanitizeFolderName($monthYear);
        
            // Obtener el nombre del receptor
            $receiver = $personnelAsset->receiver;
            $userFolder = trim("{$receiver->last_name} {$receiver->middle_name} {$receiver->name}");
        
            // Sanitizar el nombre de la carpeta: reemplazar espacios por guiones bajos y eliminar caracteres problemáticos
            $userFolder = $this->sanitizeFolderName($userFolder);
            
            // Ruta base de almacenamiento
            $basePath = "acceptances/{$monthYear}/{$userFolder}";
            
            // Generar nombre único para el archivo
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $sanitizedFileName = $this->sanitizeFileName($originalName);
            $fileName = 'doc_aceptacion_' . $personnelAsset->id . '_' . $sanitizedFileName . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Guardar el archivo
            $filePath = $file->storeAs($basePath, $fileName, 'public');

            // Actualizar la asignación con la nueva ruta del documento
            $personnelAsset->update([
                'path_acceptance_doc' => $filePath,
                'confirmation_date' => Carbon::now()
            ]);

            return response()->json([
                'ok' => true,
                'message' => 'Documento de aceptación subido correctamente.',
                'file_path' => $filePath
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al subir el documento de aceptación.',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }

    /**
     * Sanitiza el nombre de la carpeta reemplazando espacios y caracteres especiales
     */
    private function sanitizeFolderName($name)
    {
        // Reemplazar espacios por guiones bajos
        $name = preg_replace('/\s+/', '_', $name);
        
        // Eliminar caracteres especiales excepto guiones bajos
        $name = preg_replace('/[^a-zA-Z0-9_-]/', '', $name);
        
        // Limitar la longitud
        $name = substr($name, 0, 100);
        
        return $name;
    }

    /**
     * Sanitiza el nombre del archivo
     */
    private function sanitizeFileName($name)
    {
        // Reemplazar espacios por guiones bajos
        $name = preg_replace('/\s+/', '_', $name);
        
        // Eliminar caracteres especiales excepto guiones bajos y puntos
        $name = preg_replace('/[^a-zA-Z0-9._-]/', '', $name);
        
        // Limitar la longitud
        $name = substr($name, 0, 50);
        
        return $name;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * API para obtener los bienes en resguardo del usuario.
     */
    public function assetsUniqueUsuarioAPi()
    {
        try {
            $user = Auth::user();

            if (!$user || !$user->personnel_id) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Usuario no válido o sin personal asignado.'
                ], 403);
            }

            // Obtener todos los bienes aceptados del usuario
            $assets = PersonnelAsset::with(['asset.brand', 'asset.category'])
                ->where('receiver_id', $user->personnel_id)
                ->where('status', 'assigned') 
                ->orderBy('assignment_date', 'desc')
                ->get();

            $data = $assets->map(function ($item) {
                return [
                    'id' => $item->id,
                    'inventory_number' => $item->asset->inventory_number ?? '—',
                    'model' => $item->asset->model ?? '—',
                    'serial_number' => $item->asset->serial_number ?? '—',
                    'brand' => $item->asset->brand->name ?? '—',
                    'category' => $item->asset->category->name ?? '—',
                    'status' => $item->asset->is_active ? 'Activo' : 'Inactivo',
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
                'message' => 'Error al obtener bienes del usuario.',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }

    /**
     * Descargar documento de aceptación
     */
    public function downloadDocument($assignmentId)
    {
        try {
            $user = Auth::user();

            if (!$user || !$user->personnel_id) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Usuario no válido o sin personal asignado.'
                ], 403);
            }

            // Buscar la asignación específica del usuario
            $personnelAsset = PersonnelAsset::where('id', $assignmentId)
                ->where('receiver_id', $user->personnel_id)
                ->first();

            if (!$personnelAsset) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Asignación no encontrada o no autorizada.'
                ], 404);
            }

            // Verificar que exista el documento
            if (!$personnelAsset->path_acceptance_doc || 
                $personnelAsset->path_acceptance_doc === 'No disponible' || 
                strtolower($personnelAsset->path_acceptance_doc) === 'pending') {
                return response()->json([
                    'ok' => false,
                    'message' => 'Documento no disponible para descarga.'
                ], 404);
            }

            // Verificar que el archivo exista físicamente
            if (!Storage::disk('public')->exists($personnelAsset->path_acceptance_doc)) {
                return response()->json([
                    'ok' => false,
                    'message' => 'El archivo no existe en el servidor.'
                ], 404);
            }

            // Obtener el nombre original del archivo para la descarga
            $originalName = pathinfo($personnelAsset->path_acceptance_doc, PATHINFO_BASENAME);
            $downloadName = 'documento_aceptacion_' . $personnelAsset->id . '.' . pathinfo($personnelAsset->path_acceptance_doc, PATHINFO_EXTENSION);

            /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
            $disk = Storage::disk('public');
            return $disk->download($personnelAsset->path_acceptance_doc, $downloadName);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al descargar el documento.',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }
}
