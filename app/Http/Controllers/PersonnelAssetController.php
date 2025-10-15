<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonnelAssetRequest;
use App\Http\Requests\UpdatePersonnelAssetRequest;
use App\Models\PersonnelAsset;

class PersonnelAssetController extends Controller
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
    public function store(StorePersonnelAssetRequest $request)
    {
        try {
            $personnelAsset = PersonnelAsset::create($request->validated());
            $personnelAsset->load(['asset', 'assigner', 'receiver']);

            return response()->json([
                'ok' => true,
                'message' => 'Activo de personal creado exitosamente',
                'data' => $personnelAsset
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear el activo personal',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PersonnelAsset $personnelAsset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PersonnelAsset $personnelAsset)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonnelAssetRequest $request, PersonnelAsset $personnelAsset)
    {
        $data = $request ->validated();

        try {
            $personnelAsset->update($data);
            $personnelAsset->load(['asset', 'assigner', 'receiver']);

            return response()->json([
                'ok' => true,
                'message' => 'Activo de personal actualizado exitosamente',
                'data' => $personnelAsset
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al actualizar el activo personal',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PersonnelAsset $personnelAsset)
    {
        try {
            $personnelAsset->delete();

            return response()->json([
                'ok' => true,
                'message' => 'Activo de personal eliminado exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al eliminar el activo personal',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }
}
