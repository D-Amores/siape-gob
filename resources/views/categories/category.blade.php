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
            <!-- Card con sombra más pronunciada -->
            <div class="card shadow-lg">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="file_export" class="table table-hover w-100 table-striped table-bordered display">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">Categoria</th>
                                    <th class="text-center" style="width: 150px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-folder text-muted me-3"></i>
                                            <span>Lenovo</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-outline-primary border-0" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger border-0" title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-folder text-muted me-3"></i>
                                            <span>Apple</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-outline-primary border-0" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger border-0" title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-folder text-muted me-3"></i>
                                            <span>Tiger Nixon</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-outline-primary border-0" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger border-0" title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-folder text-muted me-3"></i>
                                            <span>DELL</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-outline-primary border-0" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger border-0" title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Puedes agregar más filas según sea necesario -->
                            </tbody>
                            <tfoot class="table-light">
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

    <!-- Modal Sin Botón de Cerrar -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <!-- Encabezado del Modal con gris oscuro -->
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold text-center w-100 m-0 text-white" id="addCategoryModalLabel">
                        <i class="fas fa-folder-plus me-2"></i>Agregar Categoría
                    </h5>
                    <!-- Se eliminó el botón de cerrar (X) -->
                </div>

                <!-- Formulario del Modal -->
                <form id="categoryForm">
                    <div class="modal-body p-4">
                        <!-- Solo Campo Nombre -->
                        <div class="mb-3">
                            <label for="categoryName" class="form-label fw-semibold">
                                <i class="fas fa-tag text-dark me-2"></i>Nombre de la Categoría
                            </label>
                            <input type="text" class="form-control form-control-lg" id="categoryName"
                                placeholder="Ingrese el nombre" required>
                        </div>
                    </div>

                    <!-- Pie del Modal -->
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
        document.getElementById('categoryForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const categoryName = document.getElementById('categoryName').value;
            const categoryDescription = document.getElementById('categoryDescription').value;
            const categoryColor = document.querySelector('input[name="categoryColor"]:checked').value;

            // Aquí iría tu lógica para guardar la categoría
            console.log('Guardando categoría:', {
                name: categoryName,
                description: categoryDescription,
                color: categoryColor
            });

            // Cerrar el modal después de guardar
            const modal = bootstrap.Modal.getInstance(document.getElementById('addCategoryModal'));
            modal.hide();

            // Limpiar el formulario
            this.reset();
        });
    </script>
@endsection
