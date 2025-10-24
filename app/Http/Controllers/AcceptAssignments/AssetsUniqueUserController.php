<?php

namespace App\Http\Controllers\AcceptAssignments;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PersonnelAsset;
use App\Models\Asset;
use Illuminate\Http\Request;

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
}
