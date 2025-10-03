<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\Personnel;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            $data['area_id'] = Personnel::find($data['personnel_id'])->area_id;
            $user = User::create(
                [
                    'username' => $data['username'],
                    'password' => Hash::make($data['password']),
                    'personnel_id' => $data['personnel_id'],
                    'area_id' => $data['area_id'],
                ]
            );
            $response = ['ok' => true, 'message' => 'Usuario creado exitosamente.', 'data' => $user];
            $status = 201;
        } catch (\Exception $e) {
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
