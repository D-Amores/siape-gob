@extends('layouts.layout')

@section('styles')
    {{-- <link rel="stylesheet" href="{{ asset('modernize/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('cdn/buttons/2.4.2/css/buttons.dataTables.min.css') }}">
@endsection

@section('title')
    Historial
@endsection

@section('subtitle')
    Aquí puedes ver tu historial de bienes.
@endsection

{{-- @section('actions')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        <i class="fas fa-plus-circle me-2"></i> Agregar Categoria
    </button>
@endsection --}}

@section('content')
    <div class="container-fluid">
        <div class="datatables">
            <div class="card shadow-lg">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="historic" class="table table-hover w-100 table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Activo</th>
                                    <th scope="col">Marca</th>
                                    <th scope="col">Categoría</th>
                                    <th scope="col">Asignado Por</th>
                                    <th scope="col">Asignado A</th>
                                    <th scope="col">Fecha de Asignación</th>
                                    <th scope="col">Fecha de Confirmación</th>
                                    <th scope="col">Fecha de Desasignación</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const vHistoricApi = `${BASE_URL}/historic/api`;
    </script>
    <script src="{{ asset('cdn/buttons/3.0.2/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('cdn/buttons/3.0.2/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('cdn/buttons/3.0.2/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('cdn/ajax/libs/jszip/3.10.1/jszip.min.js') }}"></script>
    <script src="{{ asset('cdn/ajax/libs/pdfmake/0.2.7/pdfmake.min.js') }}"></script>
    <script src="{{ asset('cdn/ajax/libs/pdfmake/0.2.7/vfs_fonts.js') }}"></script>

    <!-- Helpers -->
    <script src="{{ asset('js/helpers/alerts/alerts.js') }}"></script>
    
    <!-- Datatables -->
    <script src="{{ asset('js/helpers/tools/datatable-manager.js') }}"></script>

    <!-- Historic JS -->
    <script src="{{ asset('js/historic/historic-api.js') }}"></script>
    <script src="{{ asset('js/historic/historic.js') }}"></script>

@endsection
