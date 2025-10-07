@extends('layouts.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('modernize/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
@endsection

@section('title')
    Marcas
@endsection

@section('subtitle')
    Aquí puedes administrar las marcas de tus Bienes.
@endsection

@section('content')
    <div class="container-fluid">
        <div class="datatables">
            <div class="card custom-shadow border-0">
                <!-- Card Header con Estadísticas -->
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="card-title mb-0 text-primary">
                                <i class="fas fa-tags me-2"></i>Lista de Marcas
                            </h5>
                            <small class="text-muted">Total de marcas registradas: <span class="badge bg-primary">3</span></small>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="btn-group" role="group">
                                <button class="btn btn-new-brand btn-sm" data-bs-toggle="tooltip" title="Agregar nueva marca">
                                    <i class="fas fa-plus-circle me-1"></i>Nueva Marca
                                </button>
                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" title="Actualizar lista">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-0">
                    <div class="table-container">
                        <table id="file_export" class="table table-hover table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="80" class="text-center">ID</th>
                                    <th>Nombre de la Marca</th>
                                    <th width="120" class="text-center">Estado</th>
                                    <th width="100" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark">#1</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="brand-icon me-3">
                                                <i class="fas fa-laptop text-primary"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Lenovo Thinkpad</h6>
                                                <small class="text-muted">Equipos empresariales</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge status-active">Activo</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark">#2</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="brand-icon me-3">
                                                <i class="fas fa-desktop text-info"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Dell Inspiron</h6>
                                                <small class="text-muted">Línea multimedia</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge status-active">Activo</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark">#3</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="brand-icon me-3">
                                                <i class="fas fa-tv text-warning"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">HP Pavilion</h6>
                                                <small class="text-muted">Equipos para hogar</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge status-active">Activo</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
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
                                Mostrando 3 marcas registradas
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

    {{-- <style>
        .page-header-inventory {
            background: transparent;
            padding: 1.5rem 0;
        }

        .inventory-main-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .inventory-subtitle {
            font-size: 1.1rem;
            color: #6c757d;
            text-align: center;
            font-weight: 400;
        }

        .custom-shadow {
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08) !important;
            border-radius: 12px;
            overflow: hidden;
            transition: box-shadow 0.3s ease;
        }

        .custom-shadow:hover {
            box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.12) !important;
        }

        .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        /* Estilos mejorados para el botón Nueva Marca */
        .btn-new-brand {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: white;
            font-weight: 500;
            padding: 0.5rem 1rem;
        }

        .btn-new-brand:hover {
            background: linear-gradient(135deg, #218838 0%, #1e9e8a 100%);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        }

        /* Estilos mejorados para el badge Activo */
        .status-active {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
        }

        .table th {
            border-bottom: 2px solid #e9ecef;
            font-weight: 600;
            color: #495057;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.04);
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            background: rgba(0, 123, 255, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
        }

        .btn-group .btn {
            border-radius: 6px;
            margin: 0 2px;
        }

        .table-container {
            border-radius: 0 0 12px 12px;
            overflow: hidden;
        }

        /* Scroll horizontal para móviles */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Estilos para la paginación de DataTables */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border: 1px solid #dee2e6 !important;
            padding: .5rem 0.75rem !important;
            margin-left: 2px !important;
            border-radius: 0.375rem !important;
            color: #6c757d !important;
            background: white !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #007bff !important;
            border-color: #007bff !important;
            color: white !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #e9ecef !important;
            border-color: #dee2e6 !important;
            color: #495057 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #0056b3 !important;
            border-color: #0056b3 !important;
            color: white !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            color: #6c757d !important;
            background: #f8f9fa !important;
            cursor: not-allowed !important;
        }

        @media (max-width: 768px) {
            .inventory-main-title {
                font-size: 1.8rem;
            }

            .card-header .row {
                flex-direction: column;
                gap: 1rem;
            }

            .card-header .text-end {
                text-align: left !important;
            }

            .table-container {
                overflow-x: auto;
            }

            /* Asegurar que la tabla sea scrollable en móviles */
            .table {
                min-width: 600px;
            }

            .dataTables_wrapper {
                font-size: 0.875rem;
            }
        }

        @media (max-width: 576px) {
            .inventory-main-title {
                font-size: 1.5rem;
            }

            .inventory-subtitle {
                font-size: 0.9rem;
            }

            .btn-group {
                width: 100%;
            }

            .btn-group .btn {
                flex: 1;
            }
        }

    /* Espaciado para la paginación */
    .dataTables_wrapper .dataTables_paginate {
        /* margin-top: 1rem !important; */
        padding: 0.5rem 1rem 1rem 1rem !important;
        /* padding-top: 0.5rem !important; */
        /* padding-bottom: 1rem !important; */
    }

    /* Espacio entre los botones de exportación y la tabla */
    .dataTables_wrapper .row:first-child {
        /* margin-bottom: 1rem !important; */
        padding: 1rem 1rem 1rem 1rem !important;
    }
    </style> --}}
@endsection

@section('scripts')
    <!-- jQuery debe ir PRIMERO -->
    <script src="{{ asset('modernize/assets/libs/jquery/dist/jquery.min.js') }}"></script>

    <!-- Luego el resto de scripts -->
    <script src="{{ asset('modernize/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('modernize/assets/libs/simplebar/dist/simplebar.min.js') }}"></script>

    <!-- DataTables y dependencias -->
    <script src="{{ asset('modernize/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('modernize/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <!-- Scripts del tema (al final) -->
    <script src="{{ asset('modernize/assets/js/theme/app.init.js') }}"></script>
    <script src="{{ asset('modernize/assets/js/theme/theme.js') }}"></script>
    <script src="{{ asset('modernize/assets/js/theme/sidebarmenu.js') }}"></script>

    <!-- ELIMINA app.min.js si causa conflictos -->
    <!-- <script src="{{ asset('modernize/assets/js/theme/app.min.js') }}"></script> -->

    <!-- solar icons -->
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>

    <script>
    // Inicializar tooltips
    $(document).ready(function() {
        $('[data-bs-toggle="tooltip"]').tooltip();

        // Inicializar DataTables
        $('#file_export').DataTable({
            dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>' +
                    '<"row"<"col-sm-12"tr>>' +
                    '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            buttons: [
                {
                    extend: 'copy',
                    className: 'btn btn-sm btn-outline-secondary'
                },
                {
                    extend: 'csv',
                    className: 'btn btn-sm btn-outline-secondary'
                },
                {
                    extend: 'excel',
                    className: 'btn btn-sm btn-outline-secondary'
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-sm btn-outline-secondary'
                },
                {
                    extend: 'print',
                    className: 'btn btn-sm btn-outline-secondary'
                }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            responsive: true,
            pageLength: 10,
            order: [[0, 'asc']],
            columnDefs: [
                { orderable: false, targets: [3] } // Deshabilitar ordenamiento en columna de acciones
            ]
        });
    });
    </script>
@endsection
