<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Http\Requests\AssetsApiRequest;
use App\Models\Asset;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('assets.asset');
    }

    /**
     * Handle the incoming request for assets API.
     */
    public function assetsApi(AssetsApiRequest $request)
    {
        $option = $request->query('option');

        $data = null;

        switch ($option) {
            case 'table':
                // Solo los campos necesarios para la tabla principal + relaciones básicas
                $data = Asset::with(['brand', 'category'])
                    ->get(['id', 'inventory_number', 'model', 'serial_number', 'brand_id', 'category_id', 'is_active']);
                break;

            case 'details':
                // Todos los campos, incluyendo relaciones y posibles datos nulos
                $data = Asset::with(['brand', 'category', 'personalAssets'])->get();
                break;

            default:
                return response()->json([
                    'ok' => false,
                    'message' => 'Opción no válida.',
                ], 422);
        }

        $data = $data->map(function($asset) {
            $asset->is_active_label = $asset->isActive() ? 'Activo' : 'Inactivo';
            return $asset;
        });

        return response()->json([
            'ok' => true,
            'data' => $data,
        ]);
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
        $data = $request -> validated();
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

        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al eliminar el activo',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }
}
