<aside class="left-sidebar with-vertical">
    <div><!-- ---------------------------------- -->
        <!-- Start Vertical Layout Sidebar -->
        <!-- ---------------------------------- -->
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="http://localhost/modernize/main/index.html" class="text-nowrap logo-img">
                <img src="{{ asset('modernize/assets/images/logos/dark-logo.svg') }}" class="dark-logo" alt="Logo-Dark">
                <img src="{{ asset('modernize/assets/images/logos/light-logo.svg') }}" class="light-logo" alt="Logo-light"
                    style="display: none;">
            </a>
            <a href="javascript:void(0)" class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
                <i class="ti ti-x"></i>
            </a>
        </div>

        <nav class="sidebar-nav scroll-sidebar" data-simplebar="init">
            <div class="simplebar-wrapper" style="margin: 0px -24px;">
                <div class="simplebar-height-auto-observer-wrapper">
                    <div class="simplebar-height-auto-observer"></div>
                </div>
                <div class="simplebar-mask">
                    <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                        <div class="simplebar-content-wrapper" tabindex="0" role="region"
                            aria-label="scrollable content" style="height: 100%; overflow: hidden;">
                            <div class="simplebar-content" style="padding: 0px 24px;">
                                <ul id="sidebarnav">
                                    <!-- ---------------------------------- -->
                                    <!-- Home -->
                                    <!-- ---------------------------------- -->
                                    <li class="nav-small-cap">
                                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                                        <span class="hide-menu">Home</span>
                                    </li>
                                    <!-- ---------------------------------- -->
                                    <!-- Dashboard -->
                                    <!-- ---------------------------------- -->
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ route('dashboard') }}" class="get-url"
                                            aria-expanded="false">
                                            <span>
                                                <i class="ti ti-home"></i>
                                            </span>
                                            <span class="hide-menu">Inicio</span>
                                        </a>
                                    </li>
                                    @hasanyrole('admin|assigner')
                                        <li class="sidebar-item">
                                            <a class="sidebar-link" href="{{ route('categories.index') }}"
                                                aria-expanded="false">
                                                <span>
                                                    <i class="ti ti-category"></i>
                                                </span>
                                                <span class="hide-menu">Categorías</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a class="sidebar-link" href="{{ route('brands.index') }}"
                                                aria-expanded="false">
                                                <span>
                                                    <i class="ti ti-tag"></i>
                                                </span>
                                                <span class="hide-menu">Marcas</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a class="sidebar-link" href="{{ route('personnel-asset-pending.index') }}"
                                                aria-expanded="false">
                                                <span>
                                                    <i class="ti ti-arrow-right"></i>
                                                </span>
                                                <span class="hide-menu">Asignaciones</span>
                                            </a>
                                        </li>
                                    @endhasanyrole
                                    @role('user')
                                        <li class="sidebar-item">
                                            <a class="sidebar-link" href="{{ route('assets-user.index') }}" aria-expanded="false">
                                                <span>
                                                    <i class="ti ti-package"></i>
                                                </span>
                                                <span class="hide-menu">Bienes</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a class="sidebar-link" href="{{ route('accept-assignments.index') }}" aria-expanded="false">
                                                <span>
                                                    <i class="ti ti-arrows-exchange me-2"></i>
                                                </span>
                                                <span class="hide-menu">Aceptar Asignaciones</span>
                                            </a>
                                        </li>
                                    @endrole
                                    @role('admin')
                                        <!-- ADMIN -->
                                        <li class="nav-small-cap">
                                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                                            <span class="hide-menu">Administración</span>
                                        </li>
                                        <li class="sidebar-item">
                                            <a class="sidebar-link" href="{{ route('personnel.index') }}"
                                                aria-expanded="false">
                                                <span>
                                                    <i class="ti ti-id-badge"></i>
                                                </span>
                                                <span class="hide-menu">Personal</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a class="sidebar-link" href="{{ route('users.index') }}" aria-expanded="false">
                                                <span>
                                                    <i class="ti ti-user"></i>
                                                </span>
                                                <span class="hide-menu">Usuarios</span>
                                            </a>
                                        </li>
                                    @endrole
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="simplebar-placeholder" style="width: 269px; height: 399px;"></div>
            </div>
            <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
            </div>
            <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
                <div class="simplebar-scrollbar"
                    style="height: 0px; transform: translate3d(0px, 0px, 0px); display: none;"></div>
            </div>
        </nav>

        <div class="fixed-profile p-3 mx-4 mb-2 bg-secondary-subtle rounded mt-3">
            <div class="hstack gap-3">
                <div class="john-img">
                    <img src="{{ asset('modernize/assets/images/profile/user-1.jpg') }}" class="rounded-circle"
                        width="40" height="40" alt="modernize-img">
                </div>
                <div class="john-title">
                    <h6 class="mb-0 fs-4 fw-semibold">Mathew</h6>
                    <span class="fs-2">Designer</span>
                </div>
                <button id="btnLogout" class="border-0 bg-transparent text-primary ms-auto" tabindex="0" type="button"
                    aria-label="logout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="logout">
                    <i class="ti ti-power fs-6"></i>
                </button>
            </div>
        </div>

        <!-- ---------------------------------- -->
        <!-- Start Vertical Layout Sidebar -->
        <!-- ---------------------------------- -->
    </div>
</aside>
