<?php

namespace App\Http\Controllers\Admin;

use App\Models\PersonnelAssetPending;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePersonnelAssetPendingRequest;
use App\Http\Requests\Admin\UpdatePersonnelAssetPendingRequest;
use Illuminate\Support\Facades\Auth;

class PersonnelAssetPendingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.assigments');
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
    public function store(StorePersonnelAssetPendingRequest $request)
    {
        try {
            $user = Auth::user();
            if (!$user || !$user->personnel_id) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Usuario no válido o sin personal asignado'
                ], 403);
            }

            $validatedData = $request->validated();

            $validatedData['assigner_id'] = $user->personnel_id;

            $personnelAssetPending = PersonnelAssetPending::create($validatedData);

            return response()->json([
                'ok' => true,
                'message' => 'Activo de personal pendiente creado exitosamente',
                'data' => $personnelAssetPending
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
    public function show(PersonnelAssetPending $personnelAssetPending)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PersonnelAssetPending $personnelAssetPending)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonnelAssetPendingRequest $request, PersonnelAssetPending $personnelAssetPending)
    {
        $data = $request->validated();
        try {
            $personnelAssetPending->update($data);

            return response()->json([
                'ok' => true,
                'message' => 'Activo de personal pendiente actualizado exitosamente',
                'data' => $personnelAssetPending
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
    public function destroy(PersonnelAssetPending $personnelAssetPending)
    {
        try {
            $personnelAssetPending->delete();

            return response()->json([
                'ok' => true,
                'message' => 'Activo de personal pendiente eliminado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al eliminar el activo personal pendiente',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }

    public function personnelAssetPendingApi()
    {
        try {
            $assignments = PersonnelAssetPending::with(['asset', 'assigner', 'receiver'])
                ->orderBy('assignment_date', 'desc')
                ->get();

            $data = $assignments->map(function ($item) {
                return [
                    'id' => $item->id,
                    'assignment_date' => $item->assignment_date->format('Y-m-d'),
                    'confirmation_date' => optional($item->confirmation_date)->format('Y-m-d'),
                    'asset_id' => $item->asset->model ?? 'Sin nombre',
                    'assigner_name' => $item->assigner->name ?? 'Desconocido',
                    'receiver_name' => $item->receiver->name ?? 'Desconocido',
                ];
            });


            return response()->json([
                'ok' => true,
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al obtener las asignaciones pendientes',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }
}
