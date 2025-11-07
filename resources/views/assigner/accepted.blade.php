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
                    </div>
                    <div class="table-responsive">
                        <table id="accepted_assignments" class="table table-hover w-100 table-striped table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Activo</th>
                                    <!-- <th>Categoría</th> -->
                                    <!-- <th>Marca</th> -->
                                    <th>Asignado por</th>
                                    <th>Asignado a</th>
                                    <th>Con Área</th>
                                    <th>Fecha Asignación</th>
                                    <th>Fecha Aceptación</th>
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
@endsection

@section('scripts')
    <script>
        const languageDataTable = '{{ asset('cdn/datatables-language/es-MX.json') }}';

        //Api uris
        const urlAssignmentApi = `${BASE_URL}/assignments/api`;
        const urlAssetApi = `${BASE_URL}/assets/api`;
        const vURIPersonnelApi = `${BASE_URL}/admin/personnel/api`;
        
        //Assignment uris
        const URIUnassignedAsset = `${BASE_URL}/assets/unassigned`;
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

    <!-- Accepted Assignments Config -->
    <script src="{{ asset('js/assigner/accepted/table-config.js') }}"></script>
    <script src="{{ asset('js/assigner/accepted/accepted-crud.js') }}"></script>
    <script src="{{ asset('js/assigner/accepted/accepted.js') }}"></script>
@endsection
