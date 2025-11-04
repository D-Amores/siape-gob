@extends('layouts.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('cdn/buttons/2.4.2/css/buttons.dataTables.min.css') }}">
@endsection

@section('title')
    Historial
@endsection

@section('subtitle')
    Aquí puedes ver tu historial de bienes.
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-lg">
            <div class="card-body">
                {{-- ✅ Hacemos la tabla 100% responsive con Bootstrap --}}
                <div class="table-responsive">
                    <table id="historic" class="table table-hover table-striped table-bordered align-middle text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Activo</th>
                                <th>Marca</th>
                                <th>Categoría</th>
                                <th>Asigna</th>
                                <th>Asignado</th>
                                <th>Asignación</th>
                                <th>Confirmación</th>
                                <th>Desasigna</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Aquí va el contenido dinámico --}}
                        </tbody>
                    </table>
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
