@extends('layouts.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('modernize/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('cdn/buttons/2.4.2/css/buttons.dataTables.min.css') }}">

@endsection
@section('title', 'Panel de Administración')
@section('subtitle', 'Gestión de personal y usuarios')

@section('actions')
    <button class="btn btn-primary w-md-auto me-2" id="btnpersonnelCreateModal">
        <i class="bx bx-plus me-1"></i>
        Registrar Personal
    </button>
    <button class="btn btn-outline-primary w-md-auto me-2" id="btnAbrirCrearUsuario" data-bs-toggle="modal"
        data-bs-target="#userModalCreate">
        <i class="bx bx-plus me-1"></i>
        Crear Usuario
    </button>
@endsection

@section('content')
    <div class="container-fluid" style="max-width: none;">
        <div class="row">
            <div class="col-12">
                <!-- Tabs -->
                <ul class="nav nav-tabs gap-2" id="adminTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link border-3 border-primary border-bottom-0 rounded-top-2 rounded-bottom-0"
                            id="personnel-tab" data-bs-toggle="tab" data-bs-target="#personnel" type="button"
                            role="tab" aria-controls="personnel" aria-selected="true">
                            Personal
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link border-3 border-primary border-bottom-0 rounded-top-2 rounded-bottom-0"
                            id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab"
                            aria-controls="users" aria-selected="false">
                            Usuarios
                        </button>
                    </li>
                </ul>
                <div class="tab-content card mb-3 rounded-top-0 border-primary border-3 shadow" id="adminTabsContent">
                    <!-- Tabla Registros Personnel -->
                    <div class="tab-pane fade datatables" id="personnel" role="tabpanel" aria-labelledby="personnel-tab">
                        <div class="card-header text-center">
                            <h4 class="mb-0">
                                <i class="ti ti-user"></i>
                                Lista de Personal
                            </h4>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table id="dataPersonnelTable" class="table text-nowrap table-bordered align-middle">
                                    <thead class="text-dark fs-4">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nombre(s)</th>
                                            <th scope="col">Teléfono</th>
                                            <th scope="col">E-mail</th>
                                            <th scope="col">Área</th>
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
                    <!-- Tabla users -->
                    <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="users-tab">
                        <div class="card-header text-center">
                            <h4 class="mb-0">
                                <i class="ti ti-user"></i>
                                Lista de Personal
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="dataUsersTable" class="table text-nowrap table-bordered align-middle">
                                    <thead class="text-dark fs-4">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Usuario</th>
                                            <th scope="col">Persona Asignada</th>
                                            <th scope="col">Área</th>
                                            <th scope="col">Estado</th>
                                            {{-- <th scope="col">Rol</th> --}}
                                            <th scope="col">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td scope="row">1</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('modernize/assets/images/profile/user-6.jpg') }}"
                                                        class="rounded-circle" width="40" height="40">
                                                    <div class="ms-3">
                                                        <h6 class="fs-4 fw-semibold mb-0">Christopher</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="ms-3">
                                                        <h6 class="fs-4 fw-semibold mb-0">Christopher Jamil</h6>
                                                        <span class="fw-normal">Morales Flores</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Recursos Humanos</td>
                                            <td>
                                                <span class="badge bg-primary-subtle text-primary">activo</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-success"><i
                                                            class="bx bx-edit"></i></button>
                                                    <button class="btn btn-sm btn-outline-danger"><i
                                                            class="bx bx-trash"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                {{-- Paginación si es necesario --}}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Tabs -->
            </div>
        </div>
    </div>

    <!-- Modal Crear Personal -->
    <div class="modal fade" id="personnelCreateModal" tabindex="-1" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border border-primary border-2 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bx bx-user-plus me-2"></i>
                        Crear Nuevo Personal
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="alert alert-info m-3 d-none" id="personnelCreateAlert">
                    <i class="bx bx-info-circle me-2"></i>
                    Error de validación: Por favor, completa todos los campos obligatorios marcados con *
                </div>
                <form id="personnelCreateForm">
                    <div class="modal-body pb-0">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">
                                    <i class="bx bx-user me-1"></i> Nombre *
                                </label>
                                <input type="text" class="form-control" id="name" name="name" required
                                    maxlength="255">
                            </div>

                            <div class="col-md-6">
                                <label for="last_name" class="form-label">
                                    <i class="bx bx-user-pin me-1"></i> Apellido Paterno *
                                </label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required
                                    maxlength="255">
                            </div>

                            <div class="col-md-6">
                                <label for="middle_name" class="form-label">
                                    <i class="bx bx-user-circle me-1"></i> Apellido Materno
                                </label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name"
                                    maxlength="255">
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    <i class="bx bx-envelope me-1"></i> Correo Electrónico *
                                </label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">
                                    <i class="bx bx-phone me-1"></i> Teléfono
                                </label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    maxlength="20">
                            </div>

                            <div class="col-md-6">
                                <label for="area_id" class="form-label">
                                    <i class="bx bx-buildings me-1"></i> Área *
                                </label>
                                <select class="form-select areaSelect select2" id="area_id" name="area_id" required>
                                    <option value="">Seleccionar área...</option>
                                </select>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="bx bx-info-circle me-2"></i>
                            Recuerda verificar que los datos sean correctos antes de guardar el registro.
                        </div>
                    </div>

                    <div class="modal-footer ps-1 d-flex flex-row justify-content-end">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            id="btnClosePersonnelCreate">
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-primary" id="btnPersonnelCreate">
                            <span class="spinner-border spinner-border-sm me-2 d-none" id="spinnerPersonnelCreate"
                                role="status"></span>
                            <i class="bx bx-plus me-1 d-none d-md-inline"></i>
                            Crear Personal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar personal -->
    <div class="modal fade" id="personnelModalEdit" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bx bx-edit me-2"></i>
                        Editar Usuario
                    </h5>
                    <!-- Quité el botón de cierre (X) -->
                </div>
                <form id="personnelEditForm">
                    <input type="hidden" id="edit_personnel_id" name="personnel_id">
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Nombre -->
                            <div class="col-md-6">
                                <label for="edit_name" class="form-label">
                                    <i class="bx bx-user me-1"></i> Nombre *
                                </label>
                                <input type="text" class="form-control" id="edit_name" name="name"
                                    maxlength="255" required>
                            </div>

                            <!-- Apellido Paterno -->
                            <div class="col-md-6">
                                <label for="edit_last_name" class="form-label">
                                    <i class="bx bx-user-pin me-1"></i> Apellido Paterno *
                                </label>
                                <input type="text" class="form-control" id="edit_last_name" name="last_name"
                                    maxlength="255" required>
                            </div>

                            <!-- Apellido Materno -->
                            <div class="col-md-6">
                                <label for="edit_middle_name" class="form-label">
                                    <i class="bx bx-user-circle me-1"></i> Apellido Materno
                                </label>
                                <input type="text" class="form-control" id="edit_middle_name" name="middle_name"
                                    maxlength="255">
                            </div>

                            <!-- Teléfono -->
                            <div class="col-md-6">
                                <label for="edit_phone" class="form-label">
                                    <i class="bx bx-phone me-1"></i> Teléfono
                                </label>
                                <input type="text" class="form-control" id="edit_phone" name="phone"
                                    maxlength="20">
                            </div>

                            <!-- Correo electrónico -->
                            <div class="col-md-6">
                                <label for="edit_email" class="form-label">
                                    <i class="bx bx-envelope me-1"></i> Correo electrónico *
                                </label>
                                <input type="email" class="form-control" id="edit_email" name="email"
                                    maxlength="255" required>
                            </div>

                            <!-- Estado (Activo / Inactivo) -->
                            <div class="col-md-6">
                                <label for="edit_is_active" class="form-label">
                                    <i class="bx bx-toggle-left me-1"></i> Estado
                                </label>
                                <select class="form-select" id="edit_is_active" name="is_active">
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>

                            <!-- Área -->
                            <div class="col-md-6">
                                <label for="edit_area_id" class="form-label">
                                    <i class="bx bx-buildings me-1"></i> Área *
                                </label>
                                <select class="form-select areaSelect select2" id="edit_area_id" name="area_id" required>
                                    <option value="">Seleccionar área...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex flex-row justify-content-md-end ps-1 justify-content-center">
                        <button type="button" class="btn btn-outline-secondary"
                            id="btnClosePersonnelEdit">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="btnPersonnelUpdate">
                            <span class="spinner-border spinner-border-sm me-2 d-none" id="personnelEditSpinner"
                                role="status"></span>
                            <i class="bx bx-save d-none d-md-inline me-1"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Crear Usuario -->
    <div class="modal fade" id="userModalCreate" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
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
                                <label for="name" class="form-label">
                                    <i class="bx bx-user me-1"></i> Nombre Completo *
                                </label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="username" class="form-label">
                                    <i class="bx bx-at me-1"></i> Nombre de Usuario *
                                </label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    <i class="bx bx-envelope me-1"></i> Correo Electrónico *
                                </label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">
                                    <i class="bx bx-phone me-1"></i> Teléfono
                                </label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>

                            <div class="col-md-6">
                                <label for="area_id" class="form-label">
                                    <i class="bx bx-buildings me-1"></i> Área
                                </label>
                                <select class="form-select areaSelect select2" id="area_id" name="area_id">
                                    <option value="">Seleccionar área...</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="rol" class="form-label">
                                    <i class="bx bx-shield me-1"></i> Rol *
                                </label>
                                <select class="form-select rol-select" id="rol" name="rol" required>
                                    <option value="">Seleccionar rol...</option>
                                </select>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="bx bx-info-circle me-2"></i>
                            Se generará automáticamente una contraseña segura y se enviará al usuario por correo electrónico
                            despues de confirmar su correo.
                        </div>
                    </div>
                    <div class="modal-footer ps-1 d-flex flex-row justify-content-end">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Cancelar</button>
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
    <div class="modal fade" id="userModalEdit" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
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
                    <input type="hidden" id="edit_usuario_id" name="user_id">
                    <div class="modal-body">

                        <div class="row g-3">
                            {{-- <div class="col-md-6">
                            <label for="edit_name" class="form-label">
                                <i class="bx bx-user me-1"></i> Nombre Completo *
                            </label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div> --}}
                            <div class="col-md-6">
                                <label for="edit_username" class="form-label">
                                    <i class="bx bx-at me-1"></i> Nombre de Usuario *
                                </label>
                                <input type="text" class="form-control" id="edit_username" name="username" required>
                            </div>

                            {{-- <div class="col-md-6">
                            <label for="edit_email" class="form-label">
                                <i class="bx bx-envelope me-1"></i> Correo Electrónico *
                            </label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_phone" class="form-label">
                                <i class="bx bx-phone me-1"></i> Teléfono
                            </label>
                            <input type="text" class="form-control" id="edit_phone" name="phone">
                        </div> --}}

                            <div class="col-md-6">
                                <label for="edit_area_id" class="form-label">
                                    <i class="bx bx-buildings me-1"></i> Área
                                </label>
                                <select class="form-select areaSelect select2" id="edit_area_id" name="area_id">
                                    <option value="">Seleccionar área...</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_rol" class="form-label">
                                    <i class="bx bx-shield me-1"></i> Rol *
                                </label>
                                <select class="form-select rol-select" id="edit_rol" name="rol" required>
                                    <option value="">Seleccionar rol...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex flex-row justify-content-md-end ps-1 justify-content-center">
                        <button type="button" class="btn btn-outline-secondary" id="btnCloseEdit">Cancelar</button>
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
    <script src="{{ asset('modernize/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('modernize/assets/js/datatable/datatable-advanced.init.js') }}"></script>

    <!-- Script table-config.js -->
    <script src="{{ asset('js/admin/personnel/table-config.js') }}"></script>

    <!-- Script para manejar el panel de administración -->
    <script src="{{ asset('js/admin/alerts.js') }}"></script>
    <script src="{{ asset('js/admin/personnel/personnel-crud.js') }}"></script>
    <script src="{{ asset('js/admin/personnel/form-actions.js') }}"></script>
    <script src="{{ asset('js/admin/personnel/form-validate.js') }}"></script>
    <script src="{{ asset('js/admin/panel-api.js') }}"></script>
    <script src="{{ asset('js/admin/modal-actions.js') }}"></script>
    <script src="{{ asset('js/admin/helpers.js') }}"></script>
    <script src="{{ asset('js/admin/panel.js') }}"></script>
@endsection
