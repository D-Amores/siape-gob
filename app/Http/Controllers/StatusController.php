<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Status;

class StatusController extends Controller
{
    public function statusApi(Request $request)
    {
        $response = ['ok' => false, 'message' => 'Error inesperado al obtener los estados.'];
        $statusCode = 500;

        try{
            $requestData = $request->validate([
                'option' => 'required|in:all',
            ]);
            $option = $requestData['option'];

            switch ($option) {
                case 'all':
                    $statuses = Status::all();
                    break;
                default:
                    $statuses = collect();
                    break;
            }

            $mappedStatuses = $statuses->map(function ($status) {
                return [
                    'id' => $status->id,
                    'name' => $status->name,
                ];
            });

            $response = [
                'ok' => true,
                'message' => 'Estados obtenidos correctamente.',
                'data' => $mappedStatuses
            ];
            $statusCode = 200;

        } catch (\Exception $e) {
            Log::error('Error al obtener los estados: ' . $e->getMessage());
            $response['message'] = 'Error al obtener los estados.';
            $statusCode = 500;
        }

        return response()->json($response, $statusCode);
    }
}
