<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StorePersonnelRequest;
use App\Http\Requests\Admin\UpdatePersonnelRequest;
use App\Http\Requests\Admin\PersonnelApiRequest;
use App\Http\Controllers\Controller;
use App\Models\Personnel;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
class PersonnelController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function personnelApi(PersonnelApiRequest $request)
    {
        $option = $request->input('option');

        $data = null;

        switch ($option) {
            case 'area':
                $data = Personnel::excludeCurrent()->get();
                break;

            case 'area_user':
                $data = Personnel::withUser()->excludeCurrent()->get();
                break;
            case 'personnel_without_user_assignment':
                $data = Personnel::withoutUser()->excludeCurrent()->get();
                break;
            case 'personnel_with_user_assignment':
                $data = Personnel::excludeCurrent()->get();
                break;

            // Agregar más casos según sea necesario
            // case 'area_user_status':
            //     $query->whereHas('area')->whereHas('user')->where('is_active', true);
            //     break;

            default:
                return response()->json([
                    'ok' => false,
                    'message' => 'Opción no válida.',
                ], 422);
        }

        return response()->json([
            'ok' => true,
            'data' => $data,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.personnel');
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
    public function store(StorePersonnelRequest $request)
    {
        $response = [ 'ok' => false, 'message' => ''];
        $status = 201;
        $data = $request->validated();
        
        try {
            $personnel = Personnel::create([
                'name' => $data['name'],
                'last_name' => $data['last_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'phone' => $data['phone'] ?? null,
                'email' => $data['email'],
                'curp' => $data['curp'],
                'area_id' => $data['area_id'],
                'area_name' => $data['area_name'],
            ]);
            $response['ok'] = true;
            $response['message'] = 'Personal registrado con éxito.';

        }catch (\Exception $e) {
            $response['message'] = 'Error al registrar el personal.';
            if(config('app.debug')) {
                $response['errors'][] = $e->getMessage();
            }
            $status = 500;
            Log::error('Error al registrar el personal: ' . $e->getMessage());
        }
        return response()->json($response, $status);
    }

    /**
     * Display the specified resource.
     */
    public function show(Personnel $personnel)
    {
        return response()->json([
            'ok' => true,
            'message' => 'Personal encontrado con éxito.',
            'data' => $personnel,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Personnel $personnel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonnelRequest $request, Personnel $personnel)
    {
        $response = ['ok'=> false, 'message'=> ''];
        $status = 200;
        $data = $request->validated();

        try {
            $personnel->update($data);
            $response['ok'] = true;
            $response['message'] = 'Personal actualizado con éxito.';
        } catch (\Exception $e) {
            $response['message'] = 'Error al actualizar el personal.';
            if(config('app.debug')) {
                $response['errors'][] = $e->getMessage();
            }
            $status = 500;
            Log::error('Error al actualizar el personal: ' . $e->getMessage());
        }
        return response()->json($response, $status);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Personnel $personnel)
    {
        $response = ['ok'=> false, 'message'=> ''];
        $status = 200;

        if($personnel->assignedAssets()->count() > 0 || $personnel->receivedAssets()->count() > 0) {
            $response['message'] = 'No se puede eliminar el personal porque tiene activos asignados o recibidos.';
            $status = 400;
            return response()->json($response, $status);
        }

        try {
            $personnel->delete();
            $response['ok'] = true;
            $response['message'] = 'Personal eliminado con éxito.';
        }catch (QueryException $e) {
            Log::error('Error de clave foránea al eliminar el personal: ' . $e->getMessage());
            $response['message'] = 'No se puede eliminar el personal porque está relacionado con otros registros.';
            $status = 400;
        }catch (\Exception $e) {
            $response['message'] = 'Error al eliminar el personal.';
            if(config('app.debug')) {
                $response['errors'][] = $e->getMessage();
            }
            $status = 500;
            Log::error('Error al eliminar el personal: ' . $e->getMessage());
        }
        return response()->json($response, $status);
    }
}
