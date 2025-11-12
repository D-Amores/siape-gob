@extends('layouts.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('modernize/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('cdn/buttons/2.4.2/css/buttons.dataTables.min.css') }}">
@endsection

@section('title')
    Reportes
@endsection

@section('subtitle')
    Aquí puedes ver todos tus reportes.
@endsection

@section('content')
@endsection

@section('scripts')

    <script src="{{ asset('cdn/buttons/3.0.2/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('cdn/buttons/3.0.2/js/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('cdn/buttons/3.0.2/js/buttons.print.min.js')}}"></script>


    <script src="{{ asset('cdn/ajax/libs/jszip/3.10.1/jszip.min.js')}}"></script>
    <script src="{{ asset('cdn/ajax/libs/pdfmake/0.2.7/pdfmake.min.js')}}"></script>
    <script src="{{ asset('cdn/ajax/libs/pdfmake/0.2.7/vfs_fonts.js')}}"></script>

    <script src="{{ asset('js/helpers/tools/datatable-manager.js') }}"></script>
    <script src="{{ asset('js/helpers/alerts/alerts.js') }}"></script>
    <script src="{{ asset('js/helpers/modals/modal-actions.js') }}"></script>

@endsection
