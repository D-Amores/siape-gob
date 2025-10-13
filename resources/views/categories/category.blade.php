@extends('layouts.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('modernize/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('cdn/buttons/2.4.2/css/buttons.dataTables.min.css') }}">
@endsection

@section('title')
    Categoria
@endsection

@section('subtitle')
    Aquí puedes administrar las categorias de tus Bienes.
@endsection

@section('actions')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        <i class="fas fa-plus-circle me-2"></i> Agregar Categoria
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
                                    <th class="text-center">Categoria</th>
                                    <th class="text-center" style="width: 150px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center">Categoria</th>
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

    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content border-0 shadow">

                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold text-center w-100 m-0 text-white" id="addCategoryModalLabel">
                        <i class="fas fa-folder-plus me-2"></i>Agregar Categoría
                    </h5>
                </div>

                <form id="categoryForm">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="categoryName" class="form-label fw-semibold">
                                <i class="fas fa-tag text-dark me-2"></i>Nombre de la Categoría
                            </label>
                            <input type="text" class="form-control form-control-lg" id="categoryName"
                                placeholder="Ingrese el nombre">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check me-2"></i>Agregar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para editar categoría -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold text-center w-100 m-0 text-white" id="editCategoryModalLabel">
                        <i class="fas fa-edit me-2"></i>Editar Categoría
                    </h5>
                </div>

                <form id="editCategoryForm">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="editCategoryName" class="form-label fw-semibold">
                                <i class="fas fa-tag text-dark me-2"></i>Nombre de la Categoría
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
    <script src="{{ asset('js/categories/category.js') }}"></script>
    <script>
        const language = "{{ asset('cdn/datatables-language/es-MX.json') }}";
    </script>
@endsection
