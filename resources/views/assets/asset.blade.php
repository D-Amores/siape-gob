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
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBrandModal">
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

    <!-- Modal para agregar un bien -->
    <div class="modal fade" id="modalNuevoBien" tabindex="-1" aria-labelledby="modalNuevoBienLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title text-primary" id="modalNuevoBienLabel">
                        <i class="fas fa-laptop me-2"></i>Nuevo Bien
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formNuevoBien">
                        <div class="row g-3">
                            <!-- Numero de inventario -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="numeroInventario" placeholder="Número de inventario" required>
                                    <label for="numeroInventario">Número de inventario</label>
                                </div>
                            </div>

                            <!-- Marca -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="marca" placeholder="Marca" required>
                                    <label for="marca">Marca</label>
                                </div>
                            </div>

                            <!-- Modelo -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="modelo" placeholder="Modelo">
                                    <label for="modelo">Modelo</label>
                                </div>
                            </div>

                            <!-- Serie -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="serie" placeholder="Serie">
                                    <label for="serie">Serie</label>
                                </div>
                            </div>

                            <!-- Categoría -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="categoria" required>
                                        <option value="" selected>Seleccione categoría</option>
                                        <option value="computadora">Computadora</option>
                                        <option value="periferico">Periférico</option>
                                        <option value="mobiliario">Mobiliario</option>
                                    </select>
                                    <label for="categoria">Categoría</label>
                                </div>
                            </div>

                            <!-- Campos dinámicos -->
                            <div id="camposDinamicos" class="col-12"></div>

                            <!-- Descripción -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Descripción" id="descripcion" style="height: 100px"></textarea>
                                    <label for="descripcion">Descripción</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" form="formNuevoBien" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ver detalles del bien -->
    <div class="modal fade" id="modalDetallesBien" tabindex="-1" aria-labelledby="modalDetallesBienLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">

                <!-- Encabezado -->
                <div class="modal-header bg-primary bg-opacity-10 border-0">
                    <h5 class="modal-title fw-bold text-primary" id="modalDetallesBienLabel">
                    <i class="fas fa-circle-info me-2"></i>Detalles del Bien
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <!-- Cuerpo -->
                <div class="modal-body py-4 px-4">
                    <!-- Sección: Información general -->
                    <div class="card border-0 bg-light-subtle mb-4">
                        <div class="card-body">
                            <h6 class="text-uppercase text-secondary fw-semibold mb-3">
                            <i class="fas fa-info-circle me-2"></i>Información General
                            </h6>
                            <div class="row g-3">
                            <div class="col-md-6">
                                <div class="small text-muted">Número de Inventario</div>
                                <div id="detalle-inventario" class="fw-semibold"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Modelo</div>
                                <div id="detalle-modelo" class="fw-semibold"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Serie</div>
                                <div id="detalle-serie" class="fw-semibold"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Marca</div>
                                <div id="detalle-marca" class="fw-semibold"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Categoría</div>
                                <div id="detalle-categoria" class="fw-semibold"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Estado</div>
                                <span id="detalle-estado" class="badge rounded-pill px-3 py-2"></span>
                            </div>
                            </div>
                        </div>
                    </div>

        <!-- Sección: Especificaciones técnicas -->
        <div class="card border-0 bg-light-subtle mb-4">
          <div class="card-body">
            <h6 class="text-uppercase text-secondary fw-semibold mb-3">
              <i class="fas fa-microchip me-2"></i>Especificaciones Técnicas
            </h6>
            <div class="row g-3">
              <div class="col-md-6">
                <div class="small text-muted">CPU</div>
                <div id="detalle-cpu" class="fw-semibold"></div>
              </div>
              <div class="col-md-6">
                <div class="small text-muted">Velocidad</div>
                <div id="detalle-velocidad" class="fw-semibold"></div>
              </div>
              <div class="col-md-6">
                <div class="small text-muted">Memoria</div>
                <div id="detalle-memoria" class="fw-semibold"></div>
              </div>
              <div class="col-md-6">
                <div class="small text-muted">Almacenamiento</div>
                <div id="detalle-almacenamiento" class="fw-semibold"></div>
              </div>
              <div class="col-md-6">
                <div class="small text-muted">Creado el</div>
                <div id="detalle-creado" class="fw-semibold">—</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sección: Descripción -->
        <div class="card border-0 bg-light-subtle">
          <div class="card-body">
            <h6 class="text-uppercase text-secondary fw-semibold mb-3">
              <i class="fas fa-align-left me-2"></i>Descripción
            </h6>
            <div id="detalle-descripcion" class="p-3 bg-white border rounded text-secondary small" style="min-height: 80px;"></div>
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
