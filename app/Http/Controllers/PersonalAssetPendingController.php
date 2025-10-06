<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonalAssetPendingRequest;
use App\Http\Requests\UpdatePersonalAssetPendingRequest;
use App\Models\PersonalAssetPending;

class PersonalAssetPendingController extends Controller
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
    public function store(StorePersonalAssetPendingRequest $request)
    {
        try {
            $personalAssetPending = PersonalAssetPending::create($request->validated());
            $personalAssetPending->load(['asset', 'assigner', 'receiver']);

            return response()->json([
                'ok' => true,
                'message' => 'Activo personal pendiente creado exitosamente',
                'data' => $personalAssetPending
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear el activo personal pendiente',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PersonalAssetPending $personalAssetPending)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PersonalAssetPending $personalAssetPending)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonalAssetPendingRequest $request, PersonalAssetPending $personalAssetPending)
    {
        $data = $request ->validated();
        try {
            $personalAssetPending->update($data);
            $personalAssetPending->load(['asset', 'assigner', 'receiver']);

            return response()->json([
                'ok' => true,
                'message' => 'Activo personal pendiente actualizado exitosamente',
                'data' => $personalAssetPending
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al actualizar el activo personal pendiente',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PersonalAssetPending $personalAssetPending)
    {
        //
    }
}
