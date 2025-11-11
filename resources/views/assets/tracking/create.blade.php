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
                        <h4 class="mb-3">
                            <i class="ti ti-user"></i>
                            Lista de reportes de bienes
                        </h4>
                        <div class="table-responsive">
                            <table id="reports-table" class="table table-hover w-100 table-striped table-bordered align-middle">
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
