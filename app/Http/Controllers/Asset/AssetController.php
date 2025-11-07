<?php

namespace App\Http\Controllers\Asset;

use App\Http\Requests\Asset\StoreAssetRequest;
use App\Http\Requests\Asset\UpdateAssetRequest;
use App\Http\Requests\Asset\AssetsApiRequest;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use App\Models\Asset;
use App\Http\Controllers\Controller;

class AssetController extends Controller
{
    /**
     * Handle the incoming request for assets API.
     */
    public function assetsApi(AssetsApiRequest $request)
    {
        $option = $request->input('option');

        $data = null;

        switch ($option) {
            case 'table':
                // Solo los campos necesarios para la tabla principal + relaciones básicas
                $data = Asset::with(['brand', 'category'])
                    ->get(['id', 'inventory_number', 'model', 'serial_number', 'brand_id', 'category_id', 'is_active', 'type']);
                break;

            case 'details':
                $assetId = $request->input('id');

                if (!$assetId) {
                    return response()->json([
                        'ok' => false,
                        'message' => 'ID del activo requerido para detalles.'
                    ], 422);
                }

                $data = Asset::with(['brand', 'category', 'personnelAssets'])
                            ->find($assetId);

                if (!$data) {
                    return response()->json([
                        'ok' => false,
                        'message' => 'Activo no encontrado.'
                    ], 404);
                }
                break;
            case 'available':
                // Activos que no están asignados (disponibles)
                $data = Asset::available()
                    ->orderBy('inventory_number', 'asc')
                    ->get(['id', 'inventory_number', 'model'])
                    ->map(function ($asset) {
                        return [
                            'id' => $asset->id,
                            'text' => "{$asset->model} - {$asset->inventory_number}" . ($asset->type ? " ({$asset->type})" : ''),
                        ];
                    });
                break;

            default:
                return response()->json([
                    'ok' => false,
                    'message' => 'Opción no válida.',
                ], 422);
        }

        // Agregar label de estado para table y details
        if ($option !== 'available') {
            if ($data instanceof \Illuminate\Database\Eloquent\Collection) {
                $data = $data->map(function($asset) {
                    $asset->is_active_label = $asset->isActive() ? 'Activo' : 'Inactivo';
                    return $asset;
                });
            } else {
                $data->is_active_label = $data->isActive() ? 'Activo' : 'Inactivo';
            }
        }

        return response()->json([
            'ok' => true,
            'data' => $data,
        ]);

        if ($option !== 'available') {
            $data = $data->map(function($asset) {
                $asset->is_active_label = $asset->isActive() ? 'Activo' : 'Inactivo';
                return $asset;
            });
        }

        return response()->json([
            'ok' => true,
            'data' => $data,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('assets.asset');
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
    public function store(StoreAssetRequest $request)
    {
        try {
            $asset = Asset::create($request->validated());
            $asset->load(['brand', 'category']);

            return response()->json([
                'ok' => true,
                'message' => 'Activo creado exitosamente',
                'data' => $asset
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear el activo',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssetRequest $request, Asset $asset)
    {
        $data = $request->validated();
        try {
            $asset->update($data);

            return response()->json([
                'ok' => true,
                'message' => 'Activo actualizado exitosamente',
                'data' => $asset
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al actualizar el activo',
                'error' => config('app.debug') ? $th->getMessage() : 'Error interno'
            ], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset)
    {
        try {
            $asset->delete();

            return response()->json([
                'ok' => true,
                'message' => 'Activo eliminado exitosamente'
            ], 200);
        } catch (QueryException $e) {
            Log::error('Error de clave foránea al eliminar el activo: ' . $e->getMessage());
            return response()->json([
                'ok' => false,
                'message' => 'No se puede eliminar el activo porque está relacionado con otros registros.'
            ], 400);
        } catch (\Throwable $e) {
            Log::error($e);
            return response()->json([
                'ok' => false,
                'message' => 'Error al eliminar el activo',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }
}
