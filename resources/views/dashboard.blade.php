@extends('layouts.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('modernize/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('cdn/buttons/2.4.2/css/buttons.dataTables.min.css') }}">
@endsection

@section('title', 'Dashboard - SIAPE')
@section('subtitle', 'Sistema de Inventario y Asignación de Personal y Equipos')

@section('actions')
    <div class="d-flex gap-2">
        @role('admin|assigner')
            <a href="{{ route('assets.index') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-laptop me-1"></i> Gestionar Activos
            </a>
            <a href="{{ route('personnel-asset-pending.index') }}" class="btn btn-success btn-sm">
                <i class="bx bx-user-check me-1"></i> Asignaciones
            </a>
        @endrole
    </div>
@endsection

@section('content')
    <!-- Hero Section -->
    <div class="bg-primary text-white rounded-3 p-4 p-md-5 mb-4 shadow"
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="mb-3 fw-bold display-5 text-white">Bienvenido a <span class="text-warning">SIAPE</span></h1>
                <h4 class="mb-3 opacity-90 text-white">Sistema de Inventario y Asignación de Personal y Equipos</h4>
                <p class="lead mb-4">
                    Plataforma integral para la gestión eficiente de activos tecnológicos, control de inventarios
                    y asignación de equipos al personal de la organización.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-light text-dark fs-6 p-2">
                        <i class="bx bx-code-alt me-1"></i> Laravel 12
                    </span>
                    <span class="badge bg-light text-dark fs-6 p-2">
                        <i class="bx bx-data me-1"></i> MySQL
                    </span>
                    <span class="badge bg-light text-dark fs-6 p-2">
                        <i class="bx bx-shield-alt-2 me-1"></i> Spatie Permissions
                    </span>
                </div>
            </div>
            <div class="col-lg-4 text-center d-none d-lg-block">
                <i class="bx bx-server text-white opacity-25" style="font-size: 8rem;"></i>
            </div>
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    @role('admin|assigner')
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="bg-danger text-white rounded-3 p-4 text-center shadow-lg">
                    <i class="bx bx-laptop display-6 mb-3"></i>
                    <h3 class="fw-bold text-white">{{ \App\Models\Asset::count() }}</h3>
                    <p class="mb-0">Activos Registrados</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="bg-primary text-white rounded-3 p-4 text-center shadow-lg">
                    <i class="bx bx-user display-6 mb-3"></i>
                    <h3 class="fw-bold text-white">{{ \App\Models\Personnel::count() }}</h3>
                    <p class="mb-0">Personal Activo</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="bg-success text-white rounded-3 p-4 text-center shadow-lg">
                    <i class="bx bx-check-circle display-6 mb-3"></i>
                    <h3 class="fw-bold text-white">{{ \App\Models\PersonnelAsset::count() }}</h3>
                    <p class="mb-0">Asignaciones Activas</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="bg-warning text-white rounded-3 p-4 text-center shadow-lg">
                    <i class="bx bx-time-five display-6 mb-3"></i>
                    <h3 class="fw-bold text-white">{{ \App\Models\PersonnelAssetPending::count() }}</h3>
                    <p class="mb-0">Pendientes</p>
                </div>
            </div>
        </div>
    @endrole

    <!-- Funcionalidades Principales -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="mb-4 fw-bold">Funcionalidades del Sistema</h3>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bx bx-laptop text-primary display-4"></i>
                    </div>
                    <h5 class="card-title fw-bold">Gestión de Activos</h5>
                    <p class="card-text text-muted">
                        Control completo del inventario de equipos tecnológicos con especificaciones técnicas detalladas,
                        números de serie, marcas y categorías.
                    </p>
                    <ul class="list-unstyled text-start">
                        <li><i class="bx bx-check text-success me-2"></i> Registro de equipos</li>
                        <li><i class="bx bx-check text-success me-2"></i> Especificaciones técnicas</li>
                        <li><i class="bx bx-check text-success me-2"></i> Control de estado</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bx bx-user-check text-success display-4"></i>
                    </div>
                    <h5 class="card-title fw-bold">Asignación de Personal</h5>
                    <p class="card-text text-muted">
                        Sistema robusto para asignar equipos al personal, con seguimiento de responsabilidades
                        y control de entregas.
                    </p>
                    <ul class="list-unstyled text-start">
                        <li><i class="bx bx-check text-success me-2"></i> Asignaciones por área</li>
                        <li><i class="bx bx-check text-success me-2"></i> Historial de asignaciones</li>
                        <li><i class="bx bx-check text-success me-2"></i> Estados pendientes</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bx bx-shield-alt-2 text-warning display-4"></i>
                    </div>
                    <h5 class="card-title fw-bold">Control de Acceso</h5>
                    <p class="card-text text-muted">
                        Sistema de roles y permisos que garantiza la seguridad y el acceso controlado
                        según el nivel de autorización.
                    </p>
                    <ul class="list-unstyled text-start">
                        <li><i class="bx bx-check text-success me-2"></i> Roles diferenciados</li>
                        <li><i class="bx bx-check text-success me-2"></i> Permisos granulares</li>
                        <li><i class="bx bx-check text-success me-2"></i> Auditoría de accesos</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Proceso de Trabajo -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="mb-4 fw-bold">Flujo de Trabajo del Sistema</h3>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="card shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 40px; height: 40px;">
                            <span class="fw-bold">1</span>
                        </div>
                        <h5 class="mb-0 fw-bold">Registro de Activos</h5>
                    </div>
                    <p class="text-muted mb-0 ms-5">
                        Los administradores registran nuevos equipos con sus especificaciones técnicas,
                        marcas, categorías y números de inventario únicos.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="card shadow-sm border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 40px; height: 40px;">
                            <span class="fw-bold">2</span>
                        </div>
                        <h5 class="mb-0 fw-bold">Gestión de Personal</h5>
                    </div>
                    <p class="text-muted mb-0 ms-5">
                        Se mantiene un registro actualizado del personal por áreas, con sus datos
                        de contacto y estado de actividad.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="card shadow-sm border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 40px; height: 40px;">
                            <span class="fw-bold">3</span>
                        </div>
                        <h5 class="mb-0 fw-bold">Asignación de Equipos</h5>
                    </div>
                    <p class="text-muted mb-0 ms-5">
                        Los asignadores vinculan equipos específicos al personal correspondiente,
                        creando un registro de responsabilidad clara.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="card shadow-sm border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 40px; height: 40px;">
                            <span class="fw-bold">4</span>
                        </div>
                        <h5 class="mb-0 fw-bold">Seguimiento y Control</h5>
                    </div>
                    <p class="text-muted mb-0 ms-5">
                        Monitoreo continuo de asignaciones, gestión de estados pendientes
                        y generación de reportes para toma de decisiones.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Roles del Sistema -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="mb-4 fw-bold">Roles y Permisos del Sistema</h3>
        </div>

        <div class="col-lg-4 mb-3">
            <div class="card border-danger h-100">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0 text-white"><i class="bx bx-crown me-2"></i>Administrador</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><i class="bx bx-check text-success me-2"></i> Acceso total al sistema</li>
                        <li><i class="bx bx-check text-success me-2"></i> Gestión de usuarios</li>
                        <li><i class="bx bx-check text-success me-2"></i> Configuración de áreas</li>
                        <li><i class="bx bx-check text-success me-2"></i> Gestión de personal</li>
                        <li><i class="bx bx-check text-success me-2"></i> Reportes avanzados</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-3">
            <div class="card border-primary h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white"><i class="bx bx-user-voice me-2"></i>Asignador</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><i class="bx bx-check text-success me-2"></i> Gestión de activos</li>
                        <li><i class="bx bx-check text-success me-2"></i> Asignación de equipos</li>
                        <li><i class="bx bx-check text-success me-2"></i> Control de inventario</li>
                        <li><i class="bx bx-check text-success me-2"></i> Gestión de pendientes</li>
                        <li><i class="bx bx-check text-success me-2"></i> Marcas y categorías</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-3">
            <div class="card border-success h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0 text-white"><i class="bx bx-user me-2"></i>Usuario</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><i class="bx bx-check text-success me-2"></i> Vista de equipos asignados</li>
                        <li><i class="bx bx-check text-success me-2"></i> Consulta de información</li>
                        <li><i class="bx bx-check text-success me-2"></i> Reportes personales</li>
                        <li><i class="bx bx-x text-muted me-2"></i> Gestión limitada</li>
                        <li><i class="bx bx-x text-muted me-2"></i> Solo lectura</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Información Técnica -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-info-circle me-2"></i>Información Técnica del Sistema
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <h6 class="fw-bold text-primary">Tecnologías Utilizadas:</h6>
                            <ul class="list-unstyled">
                                <li><i class="bx bx-code-alt text-primary me-2"></i><strong>Framework:</strong> Laravel 12
                                </li>
                                <li><i class="bx bx-data text-primary me-2"></i><strong>Base de Datos:</strong> MySQL</li>
                                <li><i class="bx bx-palette text-primary me-2"></i><strong>Frontend:</strong> Bootstrap 5,
                                    Blade Templates</li>
                                <li><i class="bx bx-shield-alt-2 text-primary me-2"></i><strong>Autenticación:</strong>
                                    Laravel Auth + Spatie Permissions</li>
                                <li><i class="bx bx-table text-primary me-2"></i><strong>Tablas:</strong> DataTables</li>
                            </ul>
                        </div>
                        <div class="col-lg-6">
                            <h6 class="fw-bold text-success">Características del Sistema:</h6>
                            <ul class="list-unstyled">
                                <li><i class="bx bx-check-circle text-success me-2"></i>Sistema de roles y permisos
                                    granulares</li>
                                <li><i class="bx bx-check-circle text-success me-2"></i>Gestión completa de inventario
                                    tecnológico</li>
                                <li><i class="bx bx-check-circle text-success me-2"></i>Asignación inteligente de recursos
                                </li>
                                <li><i class="bx bx-check-circle text-success me-2"></i>Control de estados y seguimiento
                                </li>
                                <li><i class="bx bx-check-circle text-success me-2"></i>Interfaz responsive y moderna</li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="fw-bold text-info mb-3">
                            <i class="bx bx-target-lock me-2"></i>Objetivo del Sistema
                        </h6>
                        <p class="text-muted mb-0">
                            SIAPE (Sistema de Inventario y Asignación de Personal y Equipos) fue diseñado para
                            optimizar la gestión de activos tecnológicos en organizaciones gubernamentales,
                            proporcionando un control eficiente del inventario, asignaciones responsables del
                            personal y seguimiento detallado de recursos, garantizando transparencia y
                            responsabilidad en el manejo de bienes públicos.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Animaciones y efectos adicionales
        document.addEventListener('DOMContentLoaded', function() {
            // Animación de entrada para las tarjetas
            const cards = document.querySelectorAll('.card');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            });

            cards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });
        });
    </script>
@endsection
