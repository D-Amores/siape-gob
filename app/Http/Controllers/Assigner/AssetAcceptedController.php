<?php

namespace App\Http\Controllers\Assigner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Assigner\AssetAcceptedApiRequest;
use App\Models\PersonnelAsset;
use App\Models\PersonnelAssetPending;

class AssetAcceptedController extends Controller
{
    public function acceptAssetApi(AssetAcceptedApiRequest $request)
    {
        $response = [
            'ok' => false,
            'message' => 'Ocurrió un error inesperado. Por favor, intente nuevamente más tarde.'
        ];
        $status = 500;
        $option = $request->input('option');
        $data = null;

        try {
            switch ($option) {
                case 'pending':
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
                    $response = [
                        'ok' => true,
                        'message' => 'Asignaciones pendientes obtenidas correctamente.',
                        'data' => $data
                    ];;
                    $status = 200;
                    break;
                case 'accepted':
                    $data = PersonnelAsset::acceptedWithRelations()->get()->map(function ($assignment) {
                        return [
                            'id' => $assignment->id,
                            'asset' => [
                                'inventory_number' => $assignment->asset->inventory_number,
                                'model' => $assignment->asset->model,
                                'brand' => $assignment->asset->brand->name ?? 'Sin marca',
                                'category' => $assignment->asset->category->name ?? 'Sin categoría',
                            ],
                            'receiver' => [
                                'name' => "{$assignment->receiver->name} {$assignment->receiver->last_name}",
                                'area' => $assignment->receiver->area->name ?? 'Sin área',
                            ],
                            'assigner' => "{$assignment->assigner->name} {$assignment->assigner->last_name}",
                            'assignment_date' => optional($assignment->assignment_date)->format('Y-m-d'),
                            'confirmation_date' => optional($assignment->confirmation_date)->format('Y-m-d'),
                            'path_acceptance_doc' => $assignment->path_acceptance_doc,
                        ];
                    });

                    $response = [
                        'ok' => true,
                        'message' => 'Asignaciones aceptadas obtenidas correctamente.',
                        'data' => $data
                    ];
                    $status = 200;
                    break;

                default:
                    $response['message'] = 'Opción no válida.';
                    $status = 400;
                    break;
            }
        } catch (\Throwable $e) {
            $response['message'] = 'Error al procesar la solicitud.';
            if (config('app.debug')) {
                $response['error'] = $e->getMessage();
            }
        }
        return response()->json($response, $status);
    }
}
