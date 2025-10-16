@extends('layouts.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('modernize/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('cdn/buttons/2.4.2/css/buttons.dataTables.min.css') }}">

@endsection
@section('title', 'Panel de Administración')
@section('subtitle', 'Gestión de personal')

@section('actions')
    <button class="btn btn-primary w-md-auto me-2" id="btnOpenModalPersonnelCreate">
        <i class="bx bx-plus me-1"></i>
        Registrar Personal
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
                            Lista de personal
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataPersonnelTable" class="table table-hover text-nowrap table-bordered align-middle">
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
            </div>
        </div>
    </div>

    <!-- Modal Crear Personal -->
    <div class="modal fade" id="modalPersonnelCreate" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border border-primary border-2 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bx bx-user-plus me-2"></i>
                        Crear Nuevo Personal
                    </h5>
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
                                    <i class="bx bx-user me-1"></i> Nombre(s) *
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
                                <input type="text" class="form-control" id="phone" name="phone" maxlength="20">
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
                        <button type="button" class="btn btn-outline-secondary" id="btnCloseModalPersonnelCreate">
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-primary" id="btnPersonnelCreate">
                            <span class="spinner-border spinner-border-sm me-2 d-none" id="personnelCreateSpinner"
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
    <div class="modal fade" id="modalPersonnelEdit" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bx bx-edit me-2"></i>
                        Editar Personal
                    </h5>
                </div>
                <form id="personnelEditForm">
                    <input type="hidden" id="personnel_id_edit" name="personnel_id">
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Nombre -->
                            <div class="col-md-6">
                                <label for="name_edit" class="form-label">
                                    <i class="bx bx-user me-1"></i> Nombre(s) *
                                </label>
                                <input type="text" class="form-control" id="name_edit" name="name"
                                    maxlength="255" required>
                            </div>

                            <!-- Apellido Paterno -->
                            <div class="col-md-6">
                                <label for="last_name_edit" class="form-label">
                                    <i class="bx bx-user-pin me-1"></i> Apellido Paterno *
                                </label>
                                <input type="text" class="form-control" id="last_name_edit" name="last_name"
                                    maxlength="255" required>
                            </div>

                            <!-- Apellido Materno -->
                            <div class="col-md-6">
                                <label for="middle_name_edit" class="form-label">
                                    <i class="bx bx-user-circle me-1"></i> Apellido Materno
                                </label>
                                <input type="text" class="form-control" id="middle_name_edit" name="middle_name"
                                    maxlength="255">
                            </div>

                            <!-- Teléfono -->
                            <div class="col-md-6">
                                <label for="phone_edit" class="form-label">
                                    <i class="bx bx-phone me-1"></i> Teléfono
                                </label>
                                <input type="text" class="form-control" id="phone_edit" name="phone"
                                    maxlength="20">
                            </div>

                            <!-- Correo electrónico -->
                            <div class="col-md-6">
                                <label for="email_edit" class="form-label">
                                    <i class="bx bx-envelope me-1"></i> Correo electrónico *
                                </label>
                                <input type="email" class="form-control" id="email_edit" name="email"
                                    maxlength="255" required>
                            </div>

                            <!-- Estado (Activo / Inactivo) -->
                            <div class="col-md-6">
                                <label for="is_active_edit" class="form-label">
                                    <i class="bx bx-toggle-left me-1"></i> Estado
                                </label>
                                <select class="form-select" id="is_active_edit" name="is_active">
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>

                            <!-- Área -->
                            <div class="col-md-6">
                                <label for="area_id_edit" class="form-label">
                                    <i class="bx bx-buildings me-1"></i> Área *
                                </label>
                                <select class="form-select areaSelect select2" id="area_id_edit" name="area_id" required>
                                    <option value="">Seleccionar área...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex flex-row justify-content-md-end ps-1 justify-content-center">
                        <button type="button" class="btn btn-outline-secondary"
                            id="btnCloseModalPersonnelEdit">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="btnPersonnelEdit">
                            <span class="spinner-border spinner-border-sm me-2 d-none" id="personnelEditSpinner"
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

    <!-- Helpers -->
    <script src="{{ asset('js/helpers/tools/utils.js') }}"></script>
    <script src="{{ asset('js/helpers/modals/modal-actions.js') }}"></script>
    <script src="{{ asset('js/helpers/alerts/alerts.js') }}"></script>

    <!-- APIs -->
    <script src="{{ asset('js/admin/admin-api.js') }}"></script>

    <!-- Configuración de la tabla -->
    <script src="{{ asset('js/admin/personnel/table-config.js') }}"></script>

    <!-- Scripts para manejar personal -->
    <script src="{{ asset('js/admin/personnel/form-validate.js') }}"></script>
    <script src="{{ asset('js/admin/personnel/personnel-crud.js') }}"></script>
    <script src="{{ asset('js/admin/personnel/personnel.js') }}"></script>
@endsection
