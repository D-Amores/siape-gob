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
                                    <th class="py-1">Estado del Reporte</th>
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
                                    <th class="py-1">Estado del Reporte</th>
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

                <!-- Cuerpo del Modal -->
                <div class="modal-body py-4 px-4">
                    <div class="row g-4 align-items-stretch">
                    
                        <!-- Información del Reporte -->
                        <div class="col-md-6">
                            <div class="card h-100 border-0 bg-light-subtle">
                                <div class="card-body">
                                    <h6 class="text-uppercase text-secondary fw-semibold mb-3">
                                        <i class="fas fa-info-circle me-2"></i>Información del Reporte
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="small text-muted">Folio</div>
                                            <div id="log-folio" class="fw-semibold">—</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="small text-muted">Estado del Reporte</div>
                                            <span id="log-status" class="badge rounded-pill px-3 py-2">—</span>
                                        </div>
                                        <div class="col-6">
                                            <div class="small text-muted">Fecha de Reporte</div>
                                            <div id="log-reported-at" class="fw-semibold">—</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="small text-muted">Fecha de Cierre</div>
                                            <div id="log-closed-at" class="fw-semibold">—</div>
                                        </div>
                                        <div class="col-12">
                                            <div class="small text-muted">Descripción del Reporte</div>
                                            <div id="log-description" class="p-2 bg-white border rounded text-secondary small" style="min-height: 60px;">—</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Bien -->
                        <div class="col-md-6">
                            <div class="card h-100 border-0 bg-light-subtle">
                                <div class="card-body">
                                    <h6 class="text-uppercase text-secondary fw-semibold mb-3">
                                        <i class="fas fa-laptop me-2"></i>Información del Bien
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="small text-muted">Inventario</div>
                                            <div id="log-asset-inventory" class="fw-semibold">—</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="small text-muted">Modelo</div>
                                            <div id="log-asset-model" class="fw-semibold">—</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="small text-muted">Serie</div>
                                            <div id="log-asset-serial" class="fw-semibold">—</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="small text-muted">Marca</div>
                                            <div id="log-asset-brand" class="fw-semibold">—</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="small text-muted">Categoría</div>
                                            <div id="log-asset-category" class="fw-semibold">—</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="small text-muted">Disponibilidad</div>
                                            <span id="log-asset-status" class="badge rounded-pill px-3 py-2">—</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Historial de Logs -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-0 bg-light-subtle">
                                <div class="card-body">
                                    <h6 class="text-uppercase text-secondary fw-semibold mb-3">
                                        <i class="fas fa-history me-2"></i>Historial de Actividades
                                    </h6>
                                    <div id="logs-container" class="timeline">
                                        <!-- Los logs se cargarán dinámicamente aquí -->
                                        <div class="text-center text-muted py-4">
                                            <i class="fas fa-clock fa-2x mb-2"></i>
                                            <p>Cargando historial...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
