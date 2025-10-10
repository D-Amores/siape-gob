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
    <div class="container-fluid">
        <div class="datatables">
            <!-- Card con sombra más pronunciada -->
            <div class="card shadow-lg">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="file_export" class="table table-hover table-striped table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">N. de Inventario</th>
                                    <th>Modelo</th>
                                    <th>Serie</th>
                                    <th>Marca</th>
                                    <th>Categoría</th>
                                    <th width="120" class="text-center">Estado</th>
                                    <th width="100" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="card-footer bg-light py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Mostrando 5 marcas registradas
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                Última actualización: {{ now()->format('d/m/Y H:i') }}
                            </small>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title text-primary" id="modalDetallesBienLabel">
                        <i class="fas fa-laptop me-2"></i>Detalles del Bien
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-2">Información General</h6>
                            <div class="mb-2">
                                <strong>Número de Inventario:</strong>
                                <span class="ms-2">000123</span>
                            </div>
                            <div class="mb-2">
                                <strong>Modelo:</strong>
                                <span class="ms-2">ThinkPad T14</span>
                            </div>
                            <div class="mb-2">
                                <strong>Serie:</strong>
                                <span class="ms-2">PF3X9L2</span>
                            </div>
                            <div class="mb-2">
                                <strong>Marca:</strong>
                                <span class="ms-2">Lenovo</span>
                            </div>
                            <div class="mb-2">
                                <strong>Categoría:</strong>
                                <span class="ms-2">Computadora portátil</span>
                            </div>
                            <div class="mb-2">
                                <strong>Estado:</strong>
                                <span class="ms-2 badge status-active">Activo</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-2">Especificaciones Técnicas</h6>
                            <div class="mb-2">
                                <strong>Procesador:</strong>
                                <span class="ms-2">Intel Core i5-1135G7</span>
                            </div>
                            <div class="mb-2">
                                <strong>Velocidad:</strong>
                                <span class="ms-2">2.4 GHz</span>
                            </div>
                            <div class="mb-2">
                                <strong>Memoria:</strong>
                                <span class="ms-2">16 GB</span>
                            </div>
                            <div class="mb-2">
                                <strong>Almacenamiento:</strong>
                                <span class="ms-2">512 GB SSD</span>
                            </div>
                            <div class="mb-2">
                                <strong>Tarjeta Gráfica:</strong>
                                <span class="ms-2">Intel Iris Xe</span>
                            </div>
                            <div class="mb-2">
                                <strong>Sistema Operativo:</strong>
                                <span class="ms-2">Windows 11 Pro</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <h6 class="text-muted mb-2">Descripción</h6>
                            <div class="border rounded p-3 bg-light">
                                Laptop de uso administrativo con excelente rendimiento y durabilidad. Equipo asignado al departamento de tecnología.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-2">Información Adicional</h6>
                            <div class="mb-2">
                                <strong>Fecha de Adquisición:</strong>
                                <span class="ms-2">15/03/2023</span>
                            </div>
                            <div class="mb-2">
                                <strong>Proveedor:</strong>
                                <span class="ms-2">TecnoImport S.A.</span>
                            </div>
                            <div class="mb-2">
                                <strong>Garantía:</strong>
                                <span class="ms-2">24 meses</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-2">Ubicación y Asignación</h6>
                            <div class="mb-2">
                                <strong>Departamento:</strong>
                                <span class="ms-2">Tecnología</span>
                            </div>
                            <div class="mb-2">
                                <strong>Responsable:</strong>
                                <span class="ms-2">Juan Pérez</span>
                            </div>
                            <div class="mb-2">
                                <strong>Ubicación Física:</strong>
                                <span class="ms-2">Oficina 304, Piso 3</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
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
