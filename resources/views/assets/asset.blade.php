@extends('layouts.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('modernize/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
@endsection

@section('title')
    Bienes
@endsection

@section('subtitle')
    Aquí puedes administrar tus Bienes.
@endsection

@section('actions')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoBien">
        <i class="fas fa-plus-circle me-2"></i> Agregar bien
    </button>
@endsection

@section('content')
    <div class="container-fluid py-3">
        <div class="datatables">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table id="file_export" class="table table-hover table-sm align-middle mb-0">
                            <thead class="table-light text-uppercase text-muted small">
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
                        </table>
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="card-footer bg-white border-top py-2">
                    <div class="d-flex justify-content-between small text-muted">
                        <div>
                            <i class="fas fa-info-circle me-1"></i> Mostrando 5 marcas registradas
                        </div>
                        <div>
                            Última actualización: {{ now()->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dark-transparent sidebartoggler"></div>

    <!-- Modal: Nuevo Bien -->
    <div class="modal fade" id="modalNuevoBien" tabindex="-1" aria-labelledby="modalNuevoBienLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">

                <!-- Header -->
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title fw-bold text-primary" id="modalNuevoBienLabel">
                        <i class="fas fa-laptop me-2"></i>Nuevo Bien
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <!-- Body -->
                <div class="modal-body py-4 px-4">
                    <form id="formNuevoBien">
                        <div class="row g-4">

                            <!-- Columna Izquierda -->
                            <div class="col-md-6">
                                <div class="card h-100 border-0 bg-light-subtle">
                                    <div class="card-body">
                                        <h6 class="text-uppercase text-secondary fw-semibold mb-3">
                                            <i class="fas fa-info-circle me-2"></i>Información General
                                        </h6>

                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="numeroInventario" placeholder="Número de inventario" required>
                                            <label for="numeroInventario"><i class="fas fa-barcode me-1 text-muted"></i> Número de inventario</label>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <select class="form-select" id="marca" required>
                                                <option value="" selected>Seleccione marca</option>
                                                <option value="1">Dell</option>
                                                <option value="2">HP</option>
                                                <option value="3">Lenovo</option>
                                                <option value="4">Asus</option>
                                                <option value="5">Acer</option>
                                            </select>
                                            <label for="marca"><i class="fas fa-tag me-1 text-muted"></i> Marca</label>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="modelo" placeholder="Modelo">
                                            <label for="modelo"><i class="fas fa-laptop-code me-1 text-muted"></i> Modelo</label>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="serie" placeholder="Serie">
                                            <label for="serie"><i class="fas fa-hashtag me-1 text-muted"></i> Serie</label>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <select class="form-select" id="estado" required>
                                                <option value="1" selected>Activo</option>
                                                <option value="0">Inactivo</option>
                                            </select>
                                            <label for="estado"><i class="fas fa-toggle-on me-1 text-muted"></i> Estado</label>
                                        </div>

                                        <div class="form-floating">
                                            <select class="form-select" id="categoria" required>
                                                <option value="" selected>Seleccione categoría</option>
                                                <option value="1">Laptop</option>
                                                <option value="2">Desktop</option>
                                                <option value="3">Monitor</option>
                                                <option value="4">Impresora</option>
                                                <option value="5">Servidor</option>
                                            </select>
                                            <label for="categoria"><i class="fas fa-layer-group me-1 text-muted"></i> Categoría</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Columna Derecha -->
                            <div class="col-md-6">
                                <div class="card h-100 border-0 bg-light-subtle">
                                    <div class="card-body">

                                        <!-- Campos dinámicos -->
                                        <div id="camposDinamicos"></div>

                                        <h6 class="text-uppercase text-secondary fw-semibold mt-4 mb-3">
                                            <i class="fas fa-align-left me-2"></i>Descripción
                                        </h6>
                                        <div class="form-floating">
                                            <textarea class="form-control" placeholder="Descripción" id="descripcion" style="height: 100px"></textarea>
                                            <label for="descripcion"><i class="fas fa-pen me-1 text-muted"></i> Descripción</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cerrar
                    </button>
                    <button type="submit" form="formNuevoBien" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Guardar
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Detalles del Bien -->
    <div class="modal fade" id="modalDetallesBien" tabindex="-1" aria-labelledby="modalDetallesBienLabel" aria-hidden="true">
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
                                    <div id="detalle-descripcion" class="p-3 bg-white border rounded text-secondary small" style="min-height: 80px;"></div>
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
                    <button type="button" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i> Editar
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal de Éxito -->
    <div class="modal fade" id="modalExito" tabindex="-1" aria-labelledby="modalExitoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">

                <!-- Encabezado -->
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title fw-bold text-primary" id="modalExitoLabel">
                        <i class="fas fa-circle-check me-2"></i>Éxito
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <!-- Cuerpo -->
                <div class="modal-body text-center py-4">
                    <i class="fas fa-check-circle fa-3x text-primary mb-3"></i>
                    <p id="mensajeExito" class="mb-0 fw-medium text-secondary">Activo creado exitosamente</p>
                </div>

                <!-- Footer -->
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-outline-primary px-4" data-bs-dismiss="modal">
                        <i class="fas fa-thumbs-up me-1"></i> Aceptar
                    </button>
                </div>

            </div>
        </div>
    </div>

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
    <script src="{{ asset('js/assets/assets.js') }}"></script>
@endsection
