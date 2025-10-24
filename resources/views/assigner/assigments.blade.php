@extends('layouts.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('cdn/buttons/2.4.2/css/buttons.dataTables.min.css') }}">
@endsection

@section('title')
    Asignaciones
@endsection

@section('subtitle')
    Aquí puedes administrar las asignaciones de tus Bienes.
@endsection

@section('actions')
    <button type="button" class="btn btn-primary" id="btnOpenModalAddAssignment">
        <i class="fas fa-plus-circle me-2"></i> Agregar Asignación
    </button>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="datatables">
            <div class="card shadow-lg">
                <div class="card-body">
                    <div class="mb-2">
                        <h4 class="card-title mb-0">Asignaciones Pendientes</h4>
                    </div>
                    <div class="table-responsive">
                        <table id="file_export" class="table table-hover w-100 table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Recibe</th>
                                    <th class="text-center">Bien</th>
                                    <th class="text-center">Asigna</th>
                                    <th class="text-center" style="width: 150px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Contenido de la primera tabla -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="datatables mt-4">
            <div class="card shadow-lg">
                <div class="card-body">
                    <div class="mb-2">
                        <h4 class="card-title mb-0">Asignaciones Aceptadas</h4>
                    </div>
                    <div class="table-responsive">
                        <table id="accepted_assignments" class="table table-hover w-100 table-striped table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Activo</th>
                                    <th>Categoría</th>
                                    <th>Marca</th>
                                    <th>Asignado por</th>
                                    <th>Asignado a</th>
                                    <th>Con Área</th>
                                    <th>Fecha Asignación</th>
                                    <th>Fecha Aceptación</th>
                                    <th>Documento</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Aquí irá tu contenido dinámico -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="dark-transparent sidebartoggler"></div>

    <div class="modal fade" id="addAssignmentModal" tabindex="-1" aria-labelledby="addAssignmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content border-0 shadow">

                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold text-center w-100 m-0 text-white" id="addAssignmentModalLabel">
                        <i class="ti ti-arrows-exchange me-2"></i>Nueva Asignación Pendiente
                    </h5>
                </div>

                <form id="assignmentForm">
                    <div class="modal-body p-4">

                        <div class="mb-4">
                            <label for="assignedUser" class="form-label fw-semibold">
                                <i class="ti ti-user me-2 text-primary"></i>Personal
                            </label>
                            <select class="form-control" id="assignedUser" name="receiver_id">
                                {{-- <option value="">Cargando personal...</option> --}}
                            </select>
                        </div>

                        <!-- Select para Bienes -->
                        <div class="mb-4">
                            <label for="assignedAsset" class="form-label fw-semibold">
                                <i class="ti ti-package me-2 text-success"></i>Bien
                            </label>
                            <select class="form-control" id="assignedAsset" name="asset_id">
                                {{-- <option value="">Cargando bienes...</option> --}}
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="btnCloseModalAddAssignment">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check me-2"></i>Crear Asignación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAssignmentEdit" tabindex="-1" aria-labelledby="modalAssignmentEditLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold text-center w-100 m-0 text-white" id="modalAssignmentEditLabel">
                        <i class="fas fa-edit me-2"></i>Editar Asignación
                    </h5>
                </div>

                <form id="editCategoryForm">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="editCategoryName" class="form-label fw-semibold">
                                <i class="fas fa-tag text-dark me-2"></i>Nombre de la Asignación
                            </label>
                            <input type="text" class="form-control form-control-lg" id="editCategoryName"
                                placeholder="Ingrese el nombre">
                            <input type="hidden" id="editCategoryId">
                            <!-- Mensaje de ayuda -->
                            <div class="form-text">
                                Modifique el nombre de la categoría según sea necesario
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="btnCloseModalAssignmentEdit">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar Cambios
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

        //Api uris
        const urlAssignmentApi = `${BASE_URL}/assignments/api`;
        const urlAssetApi = `${BASE_URL}/assets/api`;
        const vURIPersonnelApi = `${BASE_URL}/admin/personnel/api`;

        //Assignment uris
        const URIAssignedAsset = `${BASE_URL}/personnel-asset-pending`;
    </script>

    <script src="{{ asset('cdn/buttons/3.0.2/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('cdn/buttons/3.0.2/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('cdn/buttons/3.0.2/js/buttons.print.min.js') }}"></script>

    <script src="{{ asset('cdn/ajax/libs/jszip/3.10.1/jszip.min.js') }}"></script>
    <script src="{{ asset('cdn/ajax/libs/pdfmake/0.2.7/pdfmake.min.js') }}"></script>
    <script src="{{ asset('cdn/ajax/libs/pdfmake/0.2.7/vfs_fonts.js') }}"></script>

    <script src="{{ asset('js/helpers/tools/datatable-manager.js') }}"></script>
    <script src="{{ asset('js/helpers/alerts/alerts.js') }}"></script>
    <script src="{{ asset('js/helpers/modals/modal-actions.js') }}"></script>

    <script src="{{ asset('js/assigner/assigner-api.js') }}"></script>

    <!-- Assigment Config -->
    <script src="{{ asset('js/assigner/assigment/datatable-config.js') }}"></script>
    <script src="{{ asset('js/assigner/assigment/assets.js') }}"></script>
    <script src="{{ asset('js/assigner/assigment/personnel.js') }}"></script>
    <script src="{{ asset('js/assigner/assigment/assigment-crud.js') }}"></script>
    <script src="{{ asset('js/assigner/assigment/assigments.js') }}"></script>

    <!-- Accepted Assignments Config -->
    <script src="{{ asset('js/assigner/accepted/table-config.js') }}"></script>
    <script src="{{ asset('js/assigner/accepted/accepted.js') }}"></script>
@endsection
