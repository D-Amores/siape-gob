<?php

namespace App\Http\Controllers\Assigner;

use App\Http\Controllers\Controller;
use App\Models\PersonnelAsset;
use Illuminate\Support\Facades\Log;

class AssetUnassigedController extends Controller
{
    public function destroy(PersonnelAsset $personnelAsset)
    {
        $response = ['ok' => false, 'message' => 'Fallo al desasignar el activo.'];
        $status = 500;
        try{
            $personnelAsset->update([
                'status' => 'unassigned',
                'unassignment_date' => now(),
            ]);
            $response = ['ok' => true, 'message' => 'Activo desasignado con éxito.', 'data' => $personnelAsset];
            $status = 200;

        } catch (\Exception $e) {
            Log::error($e);
        }

        return response()->json($response, $status);
    }
}
