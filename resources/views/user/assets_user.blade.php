@extends('layouts.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('modernize/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('cdn/buttons/2.4.2/css/buttons.dataTables.min.css') }}">
@endsection

@section('title')
    Bienes
@endsection

@section('subtitle')
    Aquí puedes ver todos tus bienes.
@endsection

@section('content')
    <div class="container-fluid py-3">
        <div class="datatables">
            <div class="card shadow-lg">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="assets_unique_user" class="table table-hover w-100 table-striped table-bordered display text-nowrap align-middle">
                            <thead class>
                                <tr>
                                    <th class="text-center py-1">N. de Inventario</th>
                                    <th class="py-1">Modelo</th>
                                    <th class="py-1">Serie</th>
                                    <th class="py-1">Marca</th>
                                    <th class="py-1">Categoría</th>
                                    <th class="text-center py-1">Estado</th>
                                    <th class="text-center py-1">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center py-1">N. de Inventario</th>
                                    <th class="py-1">Modelo</th>
                                    <th class="py-1">Serie</th>
                                    <th class="py-1">Marca</th>
                                    <th class="py-1">Categoría</th>
                                    <th class="text-center py-1">Estado</th>
                                    <th class="text-center py-1">Acciones</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detalles del Bien -->
    <div class="modal fade" id="modalDetallesUnicoUsuario" tabindex="-1" aria-labelledby="modalDetallesBienLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">

                <!-- Encabezado -->
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title fw-bold text-primary" id="modalDetallesBienLabel">
                        <i class="fas fa-laptop me-2"></i>Detalles del Bien
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <!-- Cuerpo -->
                <div class="modal-body py-4 px-4">
                    <div class="row g-4 align-items-stretch">

                        <!-- Columna izquierda -->
                        <div class="col-md-6">
                            <div class="card h-100 border-0 bg-light-subtle">
                                <div class="card-body">
                                    <h6 class="text-uppercase text-secondary fw-semibold mb-3">
                                        <i class="fas fa-info-circle me-2"></i>Información General
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="small text-muted">Inventario</div>
                                            <div id="detalle-inventario" class="fw-semibold"></div>
                                        </div>
                                        <div class="col-6">
                                            <div class="small text-muted">Modelo</div>
                                            <div id="detalle-modelo" class="fw-semibold"></div>
                                        </div>
                                        <div class="col-6">
                                            <div class="small text-muted">Serie</div>
                                            <div id="detalle-serie" class="fw-semibold"></div>
                                        </div>
                                        <div class="col-6">
                                            <div class="small text-muted">Marca</div>
                                            <div id="detalle-marca" class="fw-semibold"></div>
                                        </div>
                                        <div class="col-6">
                                            <div class="small text-muted">Categoría</div>
                                            <div id="detalle-categoria" class="fw-semibold"></div>
                                        </div>
                                        <div class="col-6">
                                            <div class="small text-muted">Estado</div>
                                            <span id="detalle-estado" class="badge rounded-pill px-3 py-2"></span>
                                        </div>
                                        <div class="col-12">
                                            <div class="small text-muted">Creado el</div>
                                            <div id="detalle-creado" class="fw-semibold">—</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Columna derecha -->
                        <div class="col-md-6">
                            <div class="card h-100 border-0 bg-light-subtle">
                                <div class="card-body">
                                    <h6 class="text-uppercase text-secondary fw-semibold mb-3">
                                        <i class="fas fa-microchip me-2"></i>Especificaciones Técnicas
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="small text-muted">CPU</div>
                                            <div id="detalle-cpu" class="fw-semibold"></div>
                                        </div>
                                        <div class="col-6">
                                            <div class="small text-muted">Velocidad</div>
                                            <div id="detalle-velocidad" class="fw-semibold"></div>
                                        </div>
                                        <div class="col-6">
                                            <div class="small text-muted">Memoria</div>
                                            <div id="detalle-memoria" class="fw-semibold"></div>
                                        </div>
                                        <div class="col-6">
                                            <div class="small text-muted">Almacenamiento</div>
                                            <div id="detalle-almacenamiento" class="fw-semibold"></div>
                                        </div>
                                    </div>

                                    <hr class="my-4">
                                    <h6 class="text-uppercase text-secondary fw-semibold mb-3">
                                        <i class="fas fa-align-left me-2"></i>Descripción
                                    </h6>
                                    <div id="detalle-descripcion" class="p-3 bg-white border rounded text-secondary small"
                                        style="min-height: 80px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cerrar
                    </button>
                </div>

            </div>
        </div>
    </div>

    <div class="dark-transparent sidebartoggler"></div>
@endsection

@section('scripts')
    <script src="{{ asset('modernize/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>

    <script src="{{ asset('cdn/buttons/2.4.2/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('cdn/ajax/libs/jszip/3.10.1/jszip.min.js') }}"></script>
    <script src="{{ asset('cdn/ajax/libs/pdfmake/0.1.53/pdfmake.min.js') }}"></script>
    <script src="{{ asset('cdn/ajax/libs/pdfmake/0.1.53/vfs_fonts.js') }}"></script>
    <script src="{{ asset('cdn/buttons/2.4.2/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('cdn/buttons/2.4.2/js/buttons.print.min.js') }}"></script>

    <script src="{{ asset('modernize/assets/js/datatable/datatable-advanced.init.js') }}"></script>
    <script>
        const language = "{{ asset('cdn/datatables-language/es-MX.json') }}";
    </script>

    <script src="{{ asset('js/user/assets_unique_user.js') }}"></script>


@endsection
