@extends('layouts.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('modernize/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('cdn/buttons/2.4.2/css/buttons.dataTables.min.css') }}">
@endsection

@section('title')
    Asignaciones
@endsection

@section('subtitle')
    Aquí puedes administrar las asignaciones de tus Bienes.
@endsection

@section('actions')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAssignmentModal">
        <i class="fas fa-plus-circle me-2"></i> Agregar Asignación
    </button>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="datatables">
            <div class="card shadow-lg">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="file_export" class="table table-hover w-100 table-striped table-bordered display">
                            <thead>
                                <tr>
                                    <th class="text-center">Asignación</th>
                                    <th class="text-center" style="width: 150px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center">Asignación</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="dark-transparent sidebartoggler"></div>

    <!-- Modal para Nueva Asignación Pendiente -->
    <div class="modal fade" id="addAssignmentModal" tabindex="-1" aria-labelledby="addAssignmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content border-0 shadow">

                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold text-center w-100 m-0 text-white" id="addAssignmentModalLabel">
                        <i class="ti ti-arrows-exchange me-2"></i>Nueva Asignación Pendiente
                    </h5>
                </div>

                <form id="assignmentForm">
                    <div class="modal-body p-4">

                        <!-- Select para Personal -->
                        <div class="mb-4">
                            <label for="assignedUser" class="form-label fw-semibold">
                                <i class="ti ti-user me-2 text-primary"></i>Personal
                            </label>
                            <select class="form-control" id="assignedUser" name="receiver_id" required>
                                {{-- <option value="">Cargando personal...</option> --}}
                            </select>
                        </div>

                        <!-- Select para Bienes -->
                        <div class="mb-4">
                            <label for="assignedAsset" class="form-label fw-semibold">
                                <i class="ti ti-package me-2 text-success"></i>Bien
                            </label>
                            <select class="form-control" id="assignedAsset" name="asset_id" required>
                                {{-- <option value="">Cargando bienes...</option> --}}
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check me-2"></i>Crear Asignación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para editar categoría -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold text-center w-100 m-0 text-white" id="editCategoryModalLabel">
                        <i class="fas fa-edit me-2"></i>Editar Asignación
                    </h5>
                </div>

                <form id="editCategoryForm">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="editCategoryName" class="form-label fw-semibold">
                                <i class="fas fa-tag text-dark me-2"></i>Nombre de la Asignación
                            </label>
                            <input type="text" class="form-control form-control-lg" id="editCategoryName"
                                placeholder="Ingrese el nombre">
                            <input type="hidden" id="editCategoryId">
                            <!-- Mensaje de ayuda -->
                            <div class="form-text">
                                Modifique el nombre de la categoría según sea necesario
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
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
    <script>
        const language = "{{ asset('cdn/datatables-language/es-MX.json') }}";
    </script>
    <script src="{{ asset('js/admin/assigment/assets.js') }}"></script>
    <script src="{{ asset('js/admin/assigment/personnel.js') }}"></script>
    <script src="{{ asset('js/admin/assigment/assigments.js') }}"></script>
@endsection
