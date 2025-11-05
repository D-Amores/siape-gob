<?php

namespace App\Http\Controllers\Historic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Historic\HistoricApiRequest;
use App\Models\PersonnelAsset;
use Illuminate\Support\Facades\Auth;



class HistoricController extends Controller
{
    public function historicApi(HistoricApiRequest $request)
    {
        $response = ['ok' => false, 'message' => 'No se pudo procesar la solicitud'];
        $status = 400;
        $request->validated();
        $option = $request->input('option');
        try{
            $user = Auth::user();
            switch ($option){
                case 'historic':
                    $query = PersonnelAsset::historicWithRelations();
                    if($user->hasRole('user')){
                        $query->where('receiver_id', $user->personnel->id);
                    }elseif($user->hasRole(['assigner','admin'])){
                        $query->where('assigner_id', $user->personnel->id);
                    }

                    $data = $query->get()->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'asset_name' => $item->asset->model.'-'.$item->asset->serial_number ?? '',
                            'brand' => $item->asset->brand->name ?? '',
                            'category' => $item->asset->category->name ?? '',
                            'assigned_by' => $item->assigner->name.' '.$item->assigner->last_name ?? '',
                            'assigned_to' => $item->receiver->name.' '.$item->receiver->last_name ?? '',
                            'assignment_date' => optional($item->assignment_date)->format('Y-m-d'),
                            'confirmation_date' => optional($item->confirmation_date)->format('Y-m-d'),
                            'unassignment_date' => optional($item->unassignment_date)->format('Y-m-d'),
                            'status' => $item->status,
                        ];
                    });

                    $response['ok'] = true;
                    $response['message'] = 'Histórico obtenido correctamente';
                    $response['data'] = $data;
                    $status = 200;
                    break;
                default:
                    $response['message'] = 'Opción no válida';
                    $status = 400;
                    break;
            }
        }catch (\Exception $e){
            $response['message'] = 'Error del servidor';
            $status = 500;
        }
        return response()->json($response, $status);
    }

    public function index()
    {
        return view('historic.historic');
    }

}
