@extends('layouts.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('cdn/buttons/2.4.2/css/buttons.dataTables.min.css') }}">
@endsection

@section('title')
    Asignaciones Aceptadas
@endsection

@section('subtitle')
    Aquí puedes administrar las asignaciones aceptadas.
@endsection

@section('actions')
    <a href="{{ route('personnel-asset-pending.index') }}" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left me-2"></i> Asignar Bienes
    </a>
@endsection

@section('content')
    <div class="container-fluid">

        <div class="datatables mt-4">
            <div class="card shadow-lg">
                <div class="card-body">
                    <div class="mb-2">
                        <h4 class="card-title mb-0">Asignaciones Aceptadas</h4>
                        <p class="card-text text-muted mt-1">
                            Los bienes se muestran utilizando su <strong>numero de inventario</strong> y
                            <strong>modelo</strong>,
                            con el formato: <em>numero de inventario – modelo</em>.
                        </p>
                    </div>
                    <div class="col-12 col-md-4 mb-2">
                        <label for="filter-accepted-assignments" class="form-label">Filtrar por nombre del personal receptor</label>
                        <input type="text" id="filter-accepted-assignments" class="form-control"
                            placeholder="Escribe al menos 3 caracteres para filtrar...">
                    </div>

                    <div class="table-responsive">
                        <table id="accepted-assignments-names" class="table table-hove table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Asignado a</th>
                                    <th>Con Área</th>
                                    <th>Asignaciones</th>
                                    <th>Asignado por</th>
                                    <th>Acciones</th>
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

    <!-- Modal details -->
    <div class="modal fade" id="details-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <!-- Modal header -->
                <div class="modal-header mb-0 pb-0">
                    <h5 class="modal-title">
                        <i class="bx bx-edit me-2"></i>
                        Detalles de Asignación
                    </h5>
                </div>

                <!-- Modal body -->
                <div class="modal-body">

                    <!-- Información del usuario / receiver -->
                    <div class="mb-3 mt-0">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 border rounded bg-light h-100">
                                    <strong class="text-secondary">Nombre del personal receptor</strong>
                                    <div class="fw-semibold" id="modal-receiver-name"></div>
                                </div>
                            </div>
                            <div id="receiver-id" class="d-none"></div>
                            <div class="col-md-6">
                                <div class="p-3 border rounded bg-light h-100">
                                    <strong class="text-secondary">Área</strong>
                                    <div class="fw-semibold" id="modal-receiver-area"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botón generar reporte -->
                    <div class="text-start mt-3">
                        <button id="details-generate-report" class="btn btn-primary px-4">
                            <i class="fas fa-file-alt me-2"></i> Generar Reporte
                        </button>
                    </div>


                    <!-- Tabla de asignaciones -->
                    <div class="table-responsive">
                        <table id="details-table" class="table table-hover table-striped table-bordered w-100">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Bien</th>
                                    <th>Estado</th>
                                    <th>Fecha Asignación</th>
                                    <th>Fecha Aceptación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Contenido dinámico -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button id="btn-details-close-modal" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const languageDataTable = '{{ asset('cdn/datatables-language/es-MX.json') }}';

        // //Api uris
        const urlAssignmentApi = `${BASE_URL}/assignments/api`;
        const urlAssetApi = `${BASE_URL}/assets/api`;
        // const vURIPersonnelApi = `${BASE_URL}/admin/personnel/api`;

        // //Assignment uris
        const urlUnassignedAsset = `${BASE_URL}/assets/unassigned`;
        // const URIAssignedAsset = `${BASE_URL}/personnel-asset-pending`;
    </script>

    <script src="{{ asset('cdn/buttons/3.0.2/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('cdn/buttons/3.0.2/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('cdn/buttons/3.0.2/js/buttons.print.min.js') }}"></script>

    <script src="{{ asset('cdn/ajax/libs/jszip/3.10.1/jszip.min.js') }}"></script>
    <script src="{{ asset('cdn/ajax/libs/pdfmake/0.2.7/pdfmake.min.js') }}"></script>
    <script src="{{ asset('cdn/ajax/libs/pdfmake/0.2.7/vfs_fonts.js') }}"></script>

    <script src="{{ asset('js/helpers/tools/datatable-manager.js') }}"></script>
    <script src="{{ asset('js/helpers/tools/utils.js') }}"></script>
    <script src="{{ asset('js/helpers/alerts/alerts.js') }}"></script>
    <script src="{{ asset('js/helpers/modals/modal-actions.js') }}"></script>

    <script src="{{ asset('js/assigner/assigner-api.js') }}"></script>

    <!-- Accepted Assignments Config -->
    <script src="{{ asset('js/assigner/accepted/table-config.js') }}"></script>
    <script src="{{ asset('js/assigner/accepted/accepted-crud.js') }}"></script>
    <script src="{{ asset('js/assigner/accepted/accepted.js') }}"></script>
@endsection
