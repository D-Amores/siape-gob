<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\Admin\UserApiRequest;
use Spatie\Permission\Models\Role;
use App\Models\Personnel;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Exception;

class UserController extends Controller
{
    public function userApi(UserApiRequest $request)
    {
        $response = ['ok' => false, 'message' => 'Error al procesar la solicitud.'];
        $status = 500;
        $data = $request->validated();

        try {
            switch ($data['option']) {
                case 'users':
                    $users = User::all();
                    $response = ['ok' => true, 'message' => 'Usuarios obtenidos exitosamente.', 'data' => $users];
                    $status = 200;
                    break;
                case 'users_areas':
                    $users = User::with('area')->get();
                    $response = ['ok' => true, 'message' => 'Usuarios con áreas obtenidos exitosamente.', 'data' => $users];
                    $status = 200;
                    break;
                case 'users_areas_personnel':
                    $users = User::with('area', 'personnel', 'roles')->get();
                    $response = ['ok' => true, 'message' => 'Usuarios con áreas y personal obtenidos exitosamente.', 'data' => $users];
                    $status = 200;
                    break;
                case 'roles':
                    $roles = Role::all();
                    $response = ['ok' => true, 'message' => 'Roles obtenidos exitosamente.', 'data' => $roles];
                    $status = 200;
                    break;
                default:
                    $response['message'] = 'Opción no válida.';
                    $status = 400;
                    break;
            }
        } catch (Exception $e) {
            $response['message'] = 'Error al procesar la solicitud.';
            if(config('app.debug')) {
                $response['errors'][] = $e->getMessage();
            }
            Log::error('Error al procesar la solicitud de usuarios: ' . $e->getMessage());
            $status = 500;
        }
        return response()->json($response, $status);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.users');
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
    public function store(StoreUserRequest $request)
    {
        $response = ['ok' => false, 'message' => 'Error al crear el usuario.'];
        $status = 500;
        $data = $request->validated();
        try {

            $personnel = Personnel::find($data['personnel_id']);

            if (!$personnel) {
                return response()->json(['ok' => false, 'message' => 'Personal no encontrado.'], 404);
            }

            // Extraer y eliminar role_id de los datos
            $roleId = $data['role_id'];
            unset($data['role_id']);
            $role = Role::find($roleId); // Obtener el rol basado en el ID proporcionado

            // Asignar el área y el estado activo basados en el personal seleccionado
            $data['area_id'] = $personnel->area_id;
            $data['is_active'] = $personnel->isActive();

            if ($role) {
                $user = User::create($data);
                $user->assignRole($role); // Asignar el rol al usuario
            } else {
                $response['message'] = 'Rol no encontrado.';
                return response()->json($response, 404);
            }

            $response = ['ok' => true, 'message' => 'Usuario creado exitosamente.', 'data' => $user];
            $status = 201;
        } catch (Exception $e) {
            $response['message'] = 'Error al registrar el usuario.';
            if(config('app.debug')) {
                $response['errors'][] = $e->getMessage();
            }
            $status = 500;
            Log::error('Error al registrar el usuario: ' . $e->getMessage());
        }

        return response()->json($response, $status);

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('roles');
        return response()->json([
            'ok' => true, 
            'message' => 'Usuario obtenido exitosamente.', 
            'data' => $user],
         200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $response = ['ok'=>false, 'message'=>'Error al actualizar el usuario.'];
        $status = 500;
        $data = $request->validated();
        try {
            // Si se actualiza el personnel_id, actualizar también el area_id
            if (isset($data['personnel_id'])) {
                $personnel = Personnel::find($data['personnel_id']);
                $data['area_id'] = $personnel->area_id;
                $data['is_active'] = $personnel->isActive();
            }
            // Actualizar roles si se proporciona role_id
            if (isset($data['role_id'])) {
                $role = Role::find($data['role_id']);
                if ($role) {
                    $user->syncRoles([$role->name]); // Sincronizar roles
                } else {
                    $response['message'] = 'Rol no encontrado.';
                    return response()->json($response, 404);
                }
                unset($data['role_id']); // Eliminar role_id de los datos para evitar errores de asignación masiva
            }

            $user->update($data);
            $response = ['ok'=>true, 'message'=>'Usuario actualizado exitosamente.'];
            $status = 200;
        } catch (Exception $e) {
            $response['message'] = 'Error al actualizar el usuario.';
            if(config('app.debug')) {
                $response['errors'][] = $e->getMessage();
            }
            Log::error('Error al actualizar el usuario: ' . $e->getMessage());
        }

        return response()->json($response, $status);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $response = ['ok'=>false, 'message'=>'Error al eliminar el usuario.'];
        $status = 500;
        try {
            $user->delete();
            $response = ['ok'=>true, 'message'=>'Usuario eliminado exitosamente.'];
            $status = 200;
        } catch (Exception $e) {
            $response['message'] = 'Error al eliminar el usuario.';
            if(config('app.debug')) {
                $response['errors'][] = $e->getMessage();
            }
            $status = 500;
            Log::error('Error al eliminar el usuario: ' . $e->getMessage());
        }

        return response()->json($response, $status);
    }
}
