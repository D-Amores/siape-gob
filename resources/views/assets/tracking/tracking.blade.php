@extends('layouts.layout')

@section('styles')
    <!-- Bootstrap CSS (si no está ya incluido en tu layout) -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
@endsection

@section('title')
    Seguimiento de Bienes
@endsection

@section('subtitle')
    Aquí puedes ver el seguimiento de tus bienes con reporte.
@endsection

@section('actions')
@endsection

@section('content')
<div class="container-fluid mt-4">
    <!-- Tabla principal -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Listado de bienes con reportes</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Folio</th>
                            <th>Bien</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Reportado por</th>
                            <th>Fecha de reporte</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Ejemplo estático -->
                        <tr>
                            <td>1</td>
                            <td><span class="badge bg-secondary">SRV-2025-0001</span></td>
                            <td>Computadora HP</td>
                            <td>Falla al encender</td>
                            <td><span class="badge bg-warning text-dark">En proceso</span></td>
                            <td>Juan Pérez</td>
                            <td>2025-11-10</td>
                            <td>
                                <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#seguimientoModal">
                                    Ver seguimiento
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td><span class="badge bg-secondary">SRV-2025-0002</span></td>
                            <td>Impresora Epson</td>
                            <td>Atasco de papel</td>
                            <td><span class="badge bg-success">Finalizado</span></td>
                            <td>María López</td>
                            <td>2025-10-28</td>
                            <td>
                                <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#seguimientoModal">
                                    Ver seguimiento
                                </button>
                            </td>
                        </tr>
                        <!-- Fin ejemplo -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de seguimiento -->
<div class="modal fade" id="seguimientoModal" tabindex="-1" aria-labelledby="seguimientoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="seguimientoModalLabel">Seguimiento del Bien</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">

        <!-- Información del reporte -->
        <div class="mb-3">
            <h6 class="fw-bold">Información del Reporte</h6>
            <ul class="list-group list-group-flush small">
                <li class="list-group-item"><strong>Folio:</strong> SRV-2025-0001</li>
                <li class="list-group-item"><strong>Bien:</strong> Computadora HP</li>
                <li class="list-group-item"><strong>Descripción:</strong> Falla al encender</li>
                <li class="list-group-item"><strong>Estado actual:</strong> En proceso</li>
            </ul>
        </div>

        <!-- Línea de tiempo del seguimiento -->
        <div class="mb-3">
            <h6 class="fw-bold">Historial de Acciones</h6>
            <ul class="list-group small">
                <li class="list-group-item">
                    <span class="fw-bold text-primary">10/11/2025 - Creación del reporte</span><br>
                    <span>Reporte levantado por Juan Pérez</span>
                </li>
                <li class="list-group-item">
                    <span class="fw-bold text-primary">11/11/2025 - Revisión técnica</span><br>
                    <span>Se diagnosticó posible daño en la fuente de poder.</span>
                </li>
                <li class="list-group-item">
                    <span class="fw-bold text-primary">12/11/2025 - En proceso de reparación</span><br>
                    <span>Técnico asignado: Carlos Díaz.</span>
                </li>
            </ul>
        </div>

        <!-- Detalles del mantenimiento -->
        <div>
            <h6 class="fw-bold">Mantenimiento Realizado</h6>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Responsable</th>
                            <th>Trabajo realizado</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        <tr>
                            <td>2025-11-11</td>
                            <td>2025-11-12</td>
                            <td>Carlos Díaz</td>
                            <td>Reemplazo de fuente de poder y limpieza interna.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
    <!-- Bootstrap JS (si no está incluido en tu layout) -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> --}}
@endsection
