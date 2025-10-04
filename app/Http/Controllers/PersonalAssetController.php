<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonalAssetRequest;
use App\Http\Requests\UpdatePersonalAssetRequest;
use App\Models\PersonalAsset;

class PersonalAssetController extends Controller
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
    public function store(StorePersonalAssetRequest $request)
    {
        try {
            $personalAsset = PersonalAsset::create($request->validated());
            $personalAsset->load(['asset', 'assigner', 'receiver']);

            return response()->json([
                'ok' => true,
                'message' => 'Activo personal creado exitosamente',
                'data' => $personalAsset
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
    public function show(PersonalAsset $personalAsset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PersonalAsset $personalAsset)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonalAssetRequest $request, PersonalAsset $personalAsset)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PersonalAsset $personalAsset)
    {
        //
    }
}
