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
    <div class="container-fluid">
        <div class="datatables">
            <div class="card shadow-lg">
                <div class="card-body">
                    <div class="mb-2">
                        <h4 class="card-title mb-0">Asignaciones Pendientes</h4>
                    </div>
                    <div class="table-responsive">
                        <table id="table_assets_users" class="table table-hover w-100 table-striped table-bordered display">
                            <thead>
                                <tr>
                                    <th class="text-center">Asigna</th>
                                    <th class="text-center">Recibe</th>
                                    <th class="text-center">Bien</th>
                                    <th class="text-center">Fecha de Asignación</th>
                                    <th class="text-center" style="width: 150px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Contenido de la primera tabla -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center">Asigna</th>
                                    <th class="text-center">Recibe</th>
                                    <th class="text-center">Bien</th>
                                    <th class="text-center">Fecha de Asignación</th>
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


@endsection
