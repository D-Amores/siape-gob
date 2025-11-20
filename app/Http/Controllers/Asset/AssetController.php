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
        try {
            switch ($option) {
                case 'table':
                    $recordsTotal = Asset::count();

                    $start = $request->input('start', 0);
                    $length = $request->input('length', 30);
                    $searchValue = $request->input('search.value', '');

                    $query = Asset::with(['brand', 'category', 'status'])
                        ->select('assets.*')
                        ->filterByStatus($request->input('filtroCondicion'))
                        ->filterByState($request->input('filtroEstado'))
                        ->filterByCategory($request->input('filtroCategoria'))
                        ->filterByBrand($request->input('filtroMarca'))
                        ->search($searchValue);

                    $recordsFiltered = $query->count();

                    $orderColumnIndex = $request->input('order.0.column', 0);
                    $orderColumnDir = $request->input('order.0.dir', 'asc');
                    $columns = [
                        0 => 'inventory_number',
                        1 => 'model',
                        2 => 'serial_number',
                        6 => 'is_active',
                    ];

                    $orderColumn = $columns[$orderColumnIndex] ?? 'inventory_number';
                    $query->orderBy($orderColumn, $orderColumnDir);

                    $data = $query->skip($start)
                        ->take($length)
                        ->get();

                    $data = $data->map(function ($asset) {
                        $asset->is_active_label = $asset->isActive() ? 'Activo' : 'Inactivo';
                        return $asset;
                    });

                    return response()->json([
                        'draw' => intval($request->input('draw')),
                        'recordsTotal' => $recordsTotal,
                        'recordsFiltered' => $recordsFiltered,
                        'data' => $data,
                    ]);

                    break;

                case 'details':
                    $assetId = $request->input('id');

                    if (!$assetId) {
                        return response()->json([
                            'ok' => false,
                            'message' => 'ID del activo requerido para detalles.'
                        ], 422);
                    }

                    $data = Asset::with(['brand', 'category', 'personnelAssets', 'status'])
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
                                'text' => $asset->asset_name . ($asset->type ? " ({$asset->type})" : ''),
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
                    $data = $data->map(function ($asset) {
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
        } catch (\Exception $e) {
            Log::error('Error en assetsApi: ' . $e->getMessage());
            return response()->json([
                'ok' => false,
                'message' => 'Error al procesar la solicitud',
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = \App\Models\Brand::orderBy('name')->get(['id', 'name']);
        $categories = \App\Models\Category::orderBy('name')->get(['id', 'name']);
        $conditions = \App\Models\Status::orderBy('name')->get(['id', 'name']);

        return view('assets.asset', [
            'brands' => $brands,
            'categories' => $categories,
            'conditions' => $conditions
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
            $asset->load(['brand', 'category', 'status']);

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
            // Validar si el bien tiene asignaciones pendientes o confirmadas
            $hasAssignments = $asset->personnelAssets()->exists() || 
                            $asset->personnelAssetPendings()->exists();

            if ($hasAssignments) {
                return response()->json([
                    'ok' => false,
                    'message' => 'No se puede eliminar el bien porque tiene asignaciones activas o pendientes.'
                ], 400);
            }

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
