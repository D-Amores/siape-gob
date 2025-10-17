@extends('layouts.layout')

@section('styles')
    {{-- <link rel="stylesheet" href="{{ asset('modernize/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('cdn/buttons/2.4.2/css/buttons.dataTables.min.css') }}">
@endsection
@section('title', 'Panel de Administración')
@section('subtitle', 'Gestión de usuarios')

@section('actions')
    <button class="btn btn-primary w-md-auto me-2" id="btnOpenModalUserCreate">
        <i class="bx bx-plus me-1"></i>
        Crear Usuario
    </button>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="datatables">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h4 class="mb-0">
                            <i class="ti ti-user"></i>
                            Lista de Usuarios
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataUsersTable" class="table w-100 table-hover text-nowrap table-bordered align-middle">
                                <thead class="text-dark fs-4">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Usuario</th>
                                        <th scope="col">Persona Asignada</th>
                                        <th scope="col">Área</th>
                                        <th scope="col">Rol</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear Usuario -->
    <div class="modal fade" id="modalUserCreate" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- Centrado y tamaño grande -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bx bx-user-plus me-2"></i>
                        Crear Nuevo Usuario
                    </h5>
                    <!-- Quité el botón de cierre (X) para que solo se cierre con Cancelar) -->
                </div>
                <form id="userCreateForm">
                    <div class="modal-body pb-0">
                        <div class="row g-3"> <!-- Espaciado responsivo -->
                            <div class="col-md-6">
                                <label for="username" class="form-label">
                                    <i class="bx bx-at me-1"></i> Nombre de Usuario *
                                </label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label">
                                    <i class="bx bx-lock me-1"></i> Contraseña *
                                </label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">
                                <i class="bx bx-lock-alt me-1"></i> Confirmar Contraseña *
                                </label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" minlength="8" required>
                            </div>

                            <div class="col-md-6">
                                <label for="personnel_id" class="form-label">
                                    <i class="bx bx-buildings me-1"></i> Asignar Personal *
                                </label>
                                <select class="form-select personnelSelect select2" id="personnel_id" name="personnel_id">
                                    <option value="">Seleccionar personal...</option>
                                </select>
                            </div>

                            {{-- <div class="col-md-6">
                                <label for="area_id" class="form-label">
                                    <i class="bx bx-buildings me-1"></i> Área
                                </label>
                                <select class="form-select areaSelect select2" id="area_id" name="area_id">
                                    <option value="">Seleccionar área...</option>
                                </select>
                            </div>  --}}
                            <div class="col-md-6">
                                <label for="role_id" class="form-label">
                                    <i class="bx bx-shield me-1"></i> Rol *
                                </label>
                                <select class="form-select rolSelect" id="role_id" name="role_id" required>
                                    <option value="">Seleccionar rol...</option>
                                </select>
                            </div> 
                        </div>

                        {{-- <div class="alert alert-info mt-3">
                            <i class="bx bx-info-circle me-2"></i>
                            Se generará automáticamente una contraseña segura y se enviará al usuario por correo electrónico
                            despues de confirmar su correo.
                        </div> --}}
                    </div>
                    <div class="modal-footer ps-1 d-flex flex-row justify-content-end">
                        <button type="button" class="btn btn-outline-secondary" id="btnCloseModalUserCreate">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="btnUserCreate">
                            <span class="spinner-border spinner-border-sm me-2 d-none" id="userCreateSpinner"
                                role="status"></span>
                            <i class="bx d-none d-md-inline bx-plus me-1"></i>
                            Crear Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Usuario -->
    <div class="modal fade" id="modalUserEdit" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bx bx-edit me-2"></i>
                        Editar Usuario
                    </h5>
                    <!-- Quité el botón de cierre (X) -->
                </div>
                <form id="userEditForm">
                    <input type="hidden" id="userEdit" name="user_id">
                    <div class="modal-body pb-0">
                        <div class="row g-3"> <!-- Espaciado responsivo -->
                            <div class="col-md-6">
                                <label for="username_edit" class="form-label">
                                    <i class="bx bx-at me-1"></i> Nombre de Usuario *
                                </label>
                                <input type="text" class="form-control" id="username_edit" name="username" required>
                            </div>

                            <div class="col-md-6">
                                <label for="password_edit" class="form-label">
                                    <i class="bx bx-lock me-1"></i> Contraseña *
                                </label>
                                <input type="password" class="form-control" id="password_edit" name="password">
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation_edit" class="form-label">
                                <i class="bx bx-lock-alt me-1"></i> Confirmar Contraseña *
                                </label>
                                <input type="password" class="form-control" id="password_confirmation_edit" name="password_confirmation" minlength="8">
                            </div>

                            <div class="col-md-6">
                                <label for="personnel_id_edit" class="form-label">
                                    <i class="bx bx-buildings me-1"></i> Asignar Personal *
                                </label>
                                <select class="form-select personnelSelect select2" id="personnel_id_edit" name="personnel_id">
                                    <option value="">Seleccionar personal...</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="role_id_edit" class="form-label">
                                    <i class="bx bx-shield me-1"></i> Rol *
                                </label>
                                <select class="form-select rolSelect" id="role_id_edit" name="role_id" required>
                                    <option value="">Seleccionar rol...</option>
                                </select>
                            </div> 
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="bx bx-info-circle me-2"></i>
                            Al desactivar un personal, se desactivará su cuenta y no podrá acceder al sistema.
                            Al eliminar un personal, la cuenta queda desactivada pero se mantiene el registro en el sistema.
                            Puede reasignar el personal a otro usuario si es necesario, recuerde guardar los cambios.
                        </div>
                    </div>
                    <div class="modal-footer d-flex flex-row justify-content-md-end ps-1 justify-content-center">
                        <button type="button" class="btn btn-outline-secondary" id="btnCloseModalUserEdit">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="btnUserEdit">
                            <span class="spinner-border spinner-border-sm me-2 d-none" id="userEditSpinner"
                                role="status"></span>
                            <i class="bx bx-save d-none d-md-inline me-1"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    const languageDataTable = '{{ asset('cdn/datatables-language/es-MX.json') }}';
    </script>
    {{-- <script src="{{ asset('modernize/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('modernize/assets/js/datatable/datatable-advanced.init.js') }}"></script> --}}
    {{-- <script src="{{ asset('cdn/buttons/2.4.2/js/dataTables.buttons.min.js') }}"></script> --}}

    <!-- Helpers -->
    <script src="{{ asset('js/helpers/tools/utils.js') }}"></script>
    <script src="{{ asset('js/helpers/modals/modal-actions.js') }}"></script>
    <script src="{{ asset('js/helpers/alerts/alerts.js') }}"></script>

   <!-- Configuración de la tabla -->
   <script src="{{ asset('js/helpers/tools/datatable-manager.js') }}"></script>
    <script src="{{ asset('js/admin/users/table-config.js') }}"></script>

    <!-- APIs -->
    <script src="{{ asset('js/admin/admin-api.js') }}"></script>

    <!-- Scripts para manejar usuarios -->
    <script src="{{ asset('js/admin/users/form-validate.js') }}"></script>
    <script src="{{ asset('js/admin/users/users-crud.js') }}"></script>
    <script src="{{ asset('js/admin/users/users.js') }}"></script>
@endsection
