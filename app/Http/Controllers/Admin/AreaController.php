<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AreApiRequest;
use App\Models\Area;

class AreaController extends Controller
{
    public function areaApi(AreApiRequest $request)
    {
        $response = ['ok'=> false, 'message' => 'Opción no válida', 'data' => []];
        $option = $request->input('option');

        switch ($option) {
            case 'area':
                $areas = Area::all();
                $response = ['ok' => true, 'data' => $areas];
                break;
            default:
                $response = ['ok'=> false, 'message' => 'Opción no válida', 'data' => []];
                break;
        }

        return response()->json($response);
    }
    
}
