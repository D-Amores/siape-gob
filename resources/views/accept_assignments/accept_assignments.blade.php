@extends('layouts.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('modernize/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('cdn/buttons/2.4.2/css/buttons.dataTables.min.css') }}">
@endsection

@section('title')
    Aceptar Asignaciones
@endsection

@section('subtitle')
    Aquí puedes aceptar las asignaciones de los bienes.
@endsection

@section('content')
    <div class="container-fluid">
        <div class="datatables">
            <div class="card shadow-lg">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="personnel_assigments" class="table table-hover w-100 table-striped table-bordered display">
                            <thead>
                                <tr>
                                    <th class="text-center">Fecha de Asignación</th>
                                    <th class="text-center">Fecha de Confrimación</th>
                                    <th class="text-center">Bien Asignado</th>
                                    <th class="text-center">Lo asígna</th>
                                    <th class="text-center">Asignado a</th>
                                    <th class="text-center" style="width: 150px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center">Fecha de Asignación</th>
                                    <th class="text-center">Fecha de Confrimación</th>
                                    <th class="text-center">Bien Asignado</th>
                                    <th class="text-center">Lo asígna</th>
                                    <th class="text-center">Asignado a</th>
                                    <th class="text-center" style="width: 150px;">Acciones</th>
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

    <script src="{{ asset('js/assigments/datatable-assigments-personnel.js') }}"></script>
@endsection
