@extends('layouts.layout')

@section('styles')
@endsection

@section('title')
    Seguimiento de Bienes
@endsection

@section('subtitle')
    Aquí puedes ver la lista de bienes con reportes y darles seguimiento.
@endsection

@section('actions')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="datatables">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="mb-2">
                        <h4>
                            <i class="ti ti-list"></i>
                            Reporte de Bienes
                        </h4>
                        <p class="card-text text-muted mt-1">
                            Al inicar el seguimiento de un reporte, el reporte desaparece de este apartado y aparece en su sección correspondiente.
                        </p>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-3">
                                <label for="filter-reports" class="form-label fw-semibold">Filtrar por:</label>
                                <select id="filter-reports" class="form-select shadow-sm border-primary">
                                    <option value="all" selected>Todos los reportes</option>
                                    <option value="open">Reportes abiertos</option>
                                    <option value="closed">Reportes cerrados</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-2 d-flex align-items-end">
                                <button id="btn-filter" class="btn btn-primary w-100 shadow-sm">
                                    <i class="ti ti-filter"></i> Filtrar
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="reports-table"
                                class="table table-hover w-100 table-striped table-bordered align-middle">
                                <thead class="text-dark fs-4">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Folio</th>
                                        <th scope="col">Bien</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Reportado por</th>
                                        <th scope="col">Fecha de reporte</th>
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
@endsection

@section('scripts')
    <script>
        const assetTrackingUrl = `${BASE_URL}/assets/asset-tracking`;
        const reportApiUrl = `${BASE_URL}/assets/asset-reports/api`;
        const languageDataTable = '{{ asset('cdn/datatables-language/es-MX.json') }}';
    </script>

    <!-- Helpers -->
    <script src="{{ asset('js/helpers/tools/utils.js') }}"></script>
    <script src="{{ asset('js/helpers/modals/modal-actions.js') }}"></script>
    <script src="{{ asset('js/helpers/alerts/alerts.js') }}"></script>

    <!-- Table config -->
    <script src="{{ asset('js/helpers/tools/datatable-manager.js') }}"></script>
    <script src="{{ asset('js/assets/tracking/create/create-table-config.js') }}"></script>

    <!-- Api interactions -->
    <script src="{{ asset('js/assets/tracking/tracking-api.js') }}"></script>
    <script src="{{ asset('js/assets/tracking/tracking-crud.js') }}"></script>

    <!-- tracking js -->
    <script src="{{ asset('js/assets/tracking/create/create-tracking.js') }}"></script>
@endsection
