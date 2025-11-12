@extends('layouts.layout')

@section('styles')
@endsection

@section('title')
    Seguimiento de Bienes
@endsection

@section('subtitle')
    Aquí puedes ver la lista de bienes con reportes y darles seguimiento.
@endsection

@section('actions')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="datatables">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="mb-3">
                            <i class="ti ti-user"></i>
                            Lista de reportes de bienes
                        </h4>
                        <div class="table-responsive">
                            <table id="tracking-table"
                                class="table table-hover w-100 table-striped table-bordered align-middle">
                                <thead class="text-dark fs-4">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Folio</th>
                                        <th scope="col">Bien</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Reportado por</th>
                                        <th scope="col">Fecha de reporte</th>
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
    </div>

    <!-- Modal Actualizar Seguimiento -->
    <div class="modal fade" id="modalTrackingEdit" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bx bx-revision me-2"></i>
                        Actualizar Seguimiento
                    </h5>
                </div>

                <form id="trackingUpdateForm">
                    <input type="hidden" id="maintenance_report_id_update" name="maintenance_report_id">

                    <div class="modal-body pb-0">
                        <div class="row g-3">

                            <!-- Estado del mantenimiento -->
                            <div class="col-md-6">
                                <label for="status_id_update" class="form-label">
                                    <i class="bx bx-bar-chart-alt-2 me-1"></i> Estado del Seguimiento *
                                </label>
                                <select class="form-select status-select" id="status_id_update" name="status_id" required>
                                    <option value="">Seleccionar estado...</option>
                                </select>
                            </div>

                            <!-- Estado del activo -->
                            <div class="col-md-6">
                                <label for="asset_status_id_update" class="form-label">
                                    <i class="bx bx-cog me-1"></i> Estado del Activo *
                                </label>
                                <select class="form-select status-select" id="asset_status_id_update" name="asset_status_id" required>
                                    <option value="">Seleccionar estado...</option>
                                </select>
                            </div>

                            <!-- Comentario -->
                            <div class="col-12">
                                <label for="comment_update" class="form-label">
                                    <i class="bx bx-comment-detail me-1"></i> Comentario
                                </label>
                                <textarea class="form-control" id="comment_update" name="comment" rows="2" placeholder="Comentario adicional..."></textarea>
                            </div>

                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="bx bx-info-circle me-2"></i>
                            Actualizar el seguimiento permite registrar el estado actual del mantenimiento y del activo.
                            Asegúrate de guardar los cambios antes de cerrar este modal.
                        </div>
                    </div>

                    <div class="modal-footer d-flex flex-row justify-content-md-end ps-1 justify-content-center">
                        <button type="button" class="btn btn-outline-secondary"
                            id="btnCloseModalTrackingEdit">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="btnTrackingEdit">
                            <span class="spinner-border spinner-border-sm me-2 d-none" id="trackingEditSpinner"
                                role="status"></span>
                            <i class="bx bx-save d-none d-md-inline me-1"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Cerrar Seguimiento -->
    <div class="modal fade" id="modalTrackingClose" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bx bx-check-shield me-2"></i>
                        Cerrar Seguimiento
                    </h5>
                </div>

                <form id="trackingCloseForm">
                    <input type="hidden" id="maintenance_report_id_close" name="maintenance_report_id">

                    <div class="modal-body pb-0">
                        <div class="row g-3">

                            <!-- Estado del seguimiento -->
                            {{-- <div class="col-md-6">
                                <label for="status_id_close" class="form-label">
                                    <i class="bx bx-bar-chart-alt me-1"></i> Estado del Seguimiento *
                                </label>
                                <select class="form-select status-select" id="status_id_close" name="status_id" required>
                                    <option value="">Seleccionar estado...</option>
                                </select>
                            </div> --}}

                            <!-- Estado del activo -->
                            <div class="col-md-6">
                                <label for="asset_status_id_close" class="form-label">
                                    <i class="bx bx-cog me-1"></i> Estado del Activo *
                                </label>
                                <select class="form-select status-select" id="asset_status_id_close" name="asset_status_id" required>
                                    <option value="">Seleccionar estado...</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <br>
                                <div class="form-text text-muted mt-2">
                                    <i class="bx bx-info-circle me-1"></i>
                                    Al elegir el estado <strong>"Fuera de servicio"</strong>, el activo o bien se debe desasignar de forma manual, en su respectivo apartado.
                                </div>
                            </div>

                            <!-- Trabajo realizado -->
                            <div class="col-12">
                                <label for="work_done_close" class="form-label">
                                    <i class="bx bx-wrench me-1"></i> Trabajo Realizado *
                                </label>
                                <textarea class="form-control" id="work_done_close" name="work_done" rows="3"
                                    placeholder="Describe detalladamente el trabajo realizado..." required></textarea>
                            </div>

                            <!-- Observación -->
                            <div class="col-12">
                                <label for="observation_close" class="form-label">
                                    <i class="bx bx-message-square-detail me-1"></i> Observación *
                                </label>
                                <textarea class="form-control" id="observation_close" name="observation" rows="2"
                                    placeholder="Agrega una observación sobre el cierre..." required></textarea>
                            </div>

                            <!-- Comentario opcional -->
                            <div class="col-12">
                                <label for="comment_close" class="form-label">
                                    <i class="bx bx-comment-detail me-1"></i> Comentario *
                                </label>
                                <textarea class="form-control" id="comment_close" name="comment" rows="2"
                                    placeholder="Comentario adicional..." required></textarea>
                            </div>

                        </div>

                        <div class="alert alert-warning mt-3 mb-0">
                            <i class="bx bx-info-circle me-2"></i>
                            Al finalizar el seguimiento, el mantenimiento quedará marcado como finalizado y el estado del
                            activo se actualizará.
                            Esta acción no se puede deshacer.
                        </div>
                    </div>

                    <div class="modal-footer d-flex flex-row justify-content-md-end ps-1 justify-content-center">
                        <button type="button" class="btn btn-outline-secondary"
                            id="btnCloseModalTrackingClose">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="btnTrackingClose">
                            <span class="spinner-border spinner-border-sm me-2 d-none" id="trackingCloseSpinner"
                                role="status"></span>
                            <i class="bx bx-lock d-none d-md-inline me-1"></i> Finalizar Seguimiento
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const reportApiUrl = `${BASE_URL}/assets/asset-reports/api`;
        const statusApiUrl = `${BASE_URL}/statuses/api`;
        const assetTrackingUrl = `${BASE_URL}/assets/asset-tracking`;
        const languageDataTable = '{{ asset('cdn/datatables-language/es-MX.json') }}';
    </script>

    <!-- Helpers -->
    <script src="{{ asset('js/helpers/tools/utils.js') }}"></script>
    <script src="{{ asset('js/helpers/modals/modal-actions.js') }}"></script>
    <script src="{{ asset('js/helpers/alerts/alerts.js') }}"></script>

    <!-- Table config -->
    <script src="{{ asset('js/helpers/tools/datatable-manager.js') }}"></script>
    <script src="{{ asset('js/assets/tracking/index/index-table-config.js') }}"></script>
    <!-- Api interactions -->
    <script src="{{ asset('js/assets/tracking/tracking-api.js') }}"></script>
    <script src="{{ asset('js/assets/tracking/tracking-crud.js') }}"></script>

    <!-- tracking js -->
    <script src="{{ asset('js/assets/tracking/index/form-validate.js') }}"></script>
    <script src="{{ asset('js/assets/tracking/index/index-tracking.js') }}"></script>
@endsection
