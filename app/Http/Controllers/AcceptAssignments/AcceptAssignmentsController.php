<?php

namespace App\Http\Controllers\AcceptAssignments;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PersonnelAssetPending;
use Illuminate\Http\Request;

class AcceptAssignmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user.accept_assignments');
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
     * API para obtener las asignaciones pendientes del usuario autenticado.
     */
    public function pendingAssignmentsApi()
    {
        try {
            $user = Auth::user();

            if (!$user || !$user->personnel_id) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Usuario no válido o sin personal asignado.'
                ], 403);
            }

            $assignments = PersonnelAssetPending::with(['asset', 'assigner', 'receiver'])
                ->where('receiver_id', $user->personnel_id)
                ->orderBy('assignment_date', 'desc')
                ->get();

            $data = $assignments->map(function ($item) {
                return [
                    'id' => $item->id,
                    'asset_name' => $item->asset->model ?? 'Sin nombre',
                    'assigner_name' => $item->assigner->name ?? 'Desconocido',
                    'receiver_name' => $item->receiver->name ?? 'Desconocido',
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
                'message' => 'Error al obtener asignaciones pendientes.',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }
}
