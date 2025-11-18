<?php

namespace App\Http\Controllers\Assigner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Assigner\AssetAcceptedApiRequest;
use App\Models\PersonnelAsset;
use App\Models\PersonnelAssetPending;
use Illuminate\Support\Facades\Log;

class AssetAcceptedController extends Controller
{
    public function assignmentsAssetApi(AssetAcceptedApiRequest $request)
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
                            'assignment_date' => $item->assignment_date ? $item->assignment_date->format('Y-m-d') : 'Sin fecha',
                            'confirmation_date' => optional($item->confirmation_date)->format('Y-m-d'),
                            'asset_name' => $item->asset->asset_name ?? 'Sin nombre',
                            'assigner_name' => $item->assigner->full_name ?? 'Desconocido',
                            'receiver_name' => $item->receiver->full_name ?? 'Desconocido',
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
                                'status' => $assignment->asset->status->name ?? 'Sin estado',
                                'asset_name' => $assignment->asset->asset_name ?? 'Sin nombre',
                                'brand' => $assignment->asset->brand->name ?? 'Sin marca',
                                'category' => $assignment->asset->category->name ?? 'Sin categoría',
                            ],
                            'receiver' => [
                                'name' => $assignment->receiver->full_name ?? 'Desconocido',
                                'area' => $assignment->receiver->area_name ?? 'Sin área',
                            ],
                            'assigner' => $assignment->assigner->full_name ?? 'Desconocido',
                            'assignment_date' => optional($assignment->assignment_date)->format('Y-m-d'),
                            'confirmation_date' => optional($assignment->confirmation_date)->format('Y-m-d'),
                            'path_acceptance_doc' => $assignment->acceptance_doc_url,
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
            Log::error("AssetAcceptedController@assignmentsAssetApi: {$e->getMessage()}");
        }
        return response()->json($response, $status);
    }

    public function index()
    {
        return view('assigner.accepted');
    }
}
