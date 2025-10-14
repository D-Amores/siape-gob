<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Models\Asset;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al eliminar el activo',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }

    public function assetsApi()
    {
        try {
            $assets = Asset::select('id', 'inventory_number', 'model')
                ->orderBy('inventory_number', 'asc')
                ->get()
                ->map(function ($asset) {
                    return [
                        'id' => $asset->id,
                        'text' => "{$asset->inventory_number} - {$asset->model}"
                    ];
                });

            return response()->json([
                'ok' => true,
                'data' => $assets
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al obtener los bienes (assets)',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }
}
