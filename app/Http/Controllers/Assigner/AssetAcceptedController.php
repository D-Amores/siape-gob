<?php

namespace App\Http\Controllers\Assigner;

use App\Http\Controllers\Controller;
use App\Services\PdfClass as PdfService;
use App\Http\Requests\Assigner\AssetAcceptedApiRequest;
use App\Models\Personnel;
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
                    ];
                    $status = 200;
                    break;
                case 'accepted':
                    $filter = $request->input('filter');
                    $data = Personnel::acceptedReceivers($filter)
                    ->take(10)
                    ->get()
                    ->map(function ($p){
                        return [
                            'id' => $p->id,
                            'receiver_name' => $p->full_name,
                            'receiver_area' => $p->area_name ?? 'Sin área',
                            'assignments_count' => $p->receivedAssets()->accepted()->count(),
                            'assigner_name' => optional(
                                $p->receivedAssets()->accepted()->first()->assigner ?? null
                            )->full_name ?? 'Desconocido',
                        ];
                    });
                    $response = [
                        'ok' => true,
                        'message' => 'Asignaciones aceptadas obtenidas correctamente.',
                        'data' => $data
                    ];
                    $status = 200;
                    break;
                case 'details':
                    $personnelId = $request->input('personnel_id');
                    $data = PersonnelAsset::assignmentsList($personnelId)->accepted()->get()->map(function ($assignment){
                        return [
                            'id' => $assignment->id,
                            'asset' => [
                                'status' => $assignment->asset->status->name ?? 'Sin estado',
                                'asset_name' => $assignment->asset->asset_name ?? 'Sin nombre',
                                'brand' => $assignment->asset->brand->name ?? 'Sin marca',
                                'category' => $assignment->asset->category->name ?? 'Sin categoría',
                            ],
                            'receiver' => [
                                //'receiver_id' => $assignment->receiver->id,
                                'name' => $assignment->receiver->full_name ?? 'Desconocido',
                                'area' => $assignment->receiver->area_name ?? 'Sin área',
                            ],
                            'assignment_date' => optional($assignment->assignment_date)->format('Y-m-d'),
                            'confirmation_date' => optional($assignment->confirmation_date)->format('Y-m-d'),
                            'path_acceptance_doc' => $assignment->acceptance_doc_url,
                        ];
                    });
                    $response = [
                        'ok' => true,
                        'message' => 'Detalles de la asignación obtenidos correctamente.',
                        'data' => $data
                    ];
                    $status = 200;;
                    break;
                case 'pdf_report':
                    $personnelId = $request->input('personnel_id');
                    $assignments = PersonnelAsset::assignmentsList($personnelId)->accepted()->get();
                    $receiver = Personnel::find($personnelId);
                    

                    if (!$receiver) {
                        $response['message'] = 'Personal no encontrado.';
                        $status = 404;
                        break;
                    }

                    $data = [
                        'receiver' => [
                            'name' => $receiver->full_name,
                            'area' => $receiver->area_name,
                        ],
                        'assignments' => $assignments->map(function ($a) {
                            return [
                                'inventory_number' => $a->asset->inventory_number ?? 'N/A',
                                'model' => $a->asset->model ?? 'Sin nombre',
                                'assigner' => $a->assigner->full_name ?? 'Desconocido',
                                'status' => $a->asset->status->name ?? 'Sin estado',
                                'serial_number' => $a->asset->serial_number ?? 'Sin serie',
                                'brand' => $a->asset->brand->name ?? 'Sin marca',
                                'category' => $a->asset->category->name ?? 'Sin categoría',
                                'assignment_date' => optional($a->assignment_date)->format('Y-m-d'),
                                'confirmation_date' => optional($a->confirmation_date)->format('Y-m-d'),
                            ];
                        }),
                    ];

                    $html = '
                        <div class="titulo2">Detalle del Activo Asignado</div>
                        <div class="subtitulo">Sistema de Inventario y Resguardos</div>

                        <br>
                        <table class="table_dts_dec">
                            <tr class="variable">
                                <td colspan="2" class="td_dec titulo_modulos">INFORMACIÓN DE LA ASIGNACIÓN</td>
                            </tr>
                            <tr>
                                <td class="td_dec td_infor">Receptor</td>
                                <td class="td_dec">'. htmlspecialchars($data["receiver"]["name"]) .'</td>
                            </tr>
                            <tr>
                                <td class="td_dec td_infor">Área</td>
                                <td class="td_dec">'. htmlspecialchars($data["receiver"]["area"]) .'</td>
                            </tr>
                        </table>

                        <br>

                        <table class="table_dts_dec" cellspacing="0" cellpadding="6">
                            <thead>
                                <tr class="variable">
                                    <td colspan="10" class="td_dec titulo_modulos">INFORMACIÓN DE LA ASIGNACIÓN</td>
                                </tr>
                                <tr>
                                    <th class="td_dec td_infor">#</th>
                                    <th class="td_dec td_infor">Inventario</th>
                                    <th class="td_dec td_infor">Modelo</th>
                                    <th class="td_dec td_infor">Serie</th>
                                    <th class="td_dec td_infor">Asignador</th>
                                    <th class="td_dec td_infor">Estado</th>
                                    <th class="td_dec td_infor">Marca</th>
                                    <th class="td_dec td_infor">Categoría</th>
                                    <th class="td_dec td_infor">Asignación</th>
                                    <th class="td_dec td_infor">Aceptación</th>
                                </tr>
                            </thead>
                            <tbody>';

                    foreach ($data["assignments"] as $i => $item) {
                        $html .= '
                        <tr>
                            <td>' . ($i+1) . '</td>
                            <td>' . $item["inventory_number"] . '</td>
                            <td>' . $item["model"] . '</td>
                            <td>' . $item["serial_number"] . '</td>
                            <td>' . $item["assigner"] . '</td>
                            <td>' . $item["status"] . '</td>
                            <td>' . $item["brand"] . '</td>
                            <td>' . $item["category"] . '</td>
                            <td>' . $item["assignment_date"] . '</td>
                            <td>' . $item["confirmation_date"] . '</td>
                        </tr>';
                    }

                    $html .= '</tbody></table>';

                    $pdfService = new PdfService();
                    $fileName = 'reporte_asignaciones_' . $receiver->id . '_' . date('YmdHis');
                    $pdfContent = $pdfService->generarPDF($html);
                    return $pdfService->descargarPDF($pdfContent, $fileName);
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
