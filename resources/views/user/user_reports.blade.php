@extends('layouts.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('modernize/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('cdn/buttons/2.4.2/css/buttons.dataTables.min.css') }}">
@endsection

@section('title')
    Reportes
@endsection

@section('subtitle')
    Aquí puedes ver todos tus reportes.
@endsection

@section('content')

    <div class="container-fluid py-3">
        <div class="datatables">
            <div class="card shadow-lg">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="reportsTable" class="table table-hover w-100 table-striped table-bordered display text-nowrap align-middle">
                            <thead class>
                                <tr>
                                    <th class="text-center py-1">Folio</th>
                                    <th class="py-1">Activo</th>
                                    <th class="py-1">Estado</th>
                                    <th class="py-1">Descripción</th>
                                    <th class="py-1">Fecha Reporte</th>
                                    <th class="py-1">Fecha Cierre</th>
                                    <th class="text-center py-1">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center py-1">Folio</th>
                                    <th class="py-1">Activo</th>
                                    <th class="py-1">Estado</th>
                                    <th class="py-1">Descripción</th>
                                    <th class="py-1">Fecha Reporte</th>
                                    <th class="py-1">Fecha Cierre</th>
                                    <th class="text-center py-1">Acciones</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="logsModal" tabindex="-1" aria-labelledby="logsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                
                <!-- Encabezado -->
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title fw-bold text-primary" id="logsModalLabel">
                        <i class="fas fa-laptop me-2"></i>Historial del Reporte
                    </h5>
                    <button type="button" class="btn-close" id="btnCerrarModalReporte" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" id="btnCerrarFooterReporte" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script src="{{ asset('cdn/buttons/3.0.2/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('cdn/buttons/3.0.2/js/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('cdn/buttons/3.0.2/js/buttons.print.min.js')}}"></script>


    <script src="{{ asset('cdn/ajax/libs/jszip/3.10.1/jszip.min.js')}}"></script>
    <script src="{{ asset('cdn/ajax/libs/pdfmake/0.2.7/pdfmake.min.js')}}"></script>
    <script src="{{ asset('cdn/ajax/libs/pdfmake/0.2.7/vfs_fonts.js')}}"></script>

    <script src="{{ asset('js/helpers/tools/datatable-manager.js') }}"></script>
    <script src="{{ asset('js/helpers/alerts/alerts.js') }}"></script>
    <script src="{{ asset('js/helpers/modals/modal-actions.js') }}"></script>
    <script src="{{ asset('js/helpers/tools/utils.js') }}"></script>
    <script> const languageDataTable = "{{ asset('cdn/datatables-language/es-MX.json') }}"; </script>

    <script src="{{ asset('js/user/user_reports.js') }}"></script>

    <script>
        //Api uris
        const URIUserReports = `${BASE_URL}/user/reports`;
    </script>

@endsection
