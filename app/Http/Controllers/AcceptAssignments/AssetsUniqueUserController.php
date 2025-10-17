<?php

namespace App\Http\Controllers\AcceptAssignments;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PersonnelAsset;
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
