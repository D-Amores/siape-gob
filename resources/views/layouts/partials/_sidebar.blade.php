<aside class="left-sidebar with-vertical">
    <div><!-- ---------------------------------- -->
        <!-- Start Vertical Layout Sidebar -->
        <!-- ---------------------------------- -->
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="{{ asset('modernize/main/index.html') }}" class="text-nowrap logo-img">
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
                                        <a class="sidebar-link" href="./" id="get-url" aria-expanded="false">
                                            <span>
                                                <i class="ti ti-aperture"></i>
                                            </span>
                                            <span class="hide-menu">Modern</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ asset('modernize/main/index3.html') }}"
                                            aria-expanded="false">
                                            <span>
                                                <i class="ti ti-currency-dollar"></i>
                                            </span>
                                            <span class="hide-menu">NFT</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ asset('modernize/main/index4.html') }}"
                                            aria-expanded="false">
                                            <span>
                                                <i class="ti ti-cpu"></i>
                                            </span>
                                            <span class="hide-menu">Crypto</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ asset('modernize/main/index5.html') }}"
                                            aria-expanded="false">
                                            <span>
                                                <i class="ti ti-activity-heartbeat"></i>
                                            </span>
                                            <span class="hide-menu">General</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ asset('modernize/main/index6.html') }}"
                                            aria-expanded="false">
                                            <span>
                                                <i class="ti ti-playlist"></i>
                                            </span>
                                            <span class="hide-menu">Music</span>
                                        </a>
                                    </li>
                                    <!-- ---------------------------------- -->
                                    <!-- Frontend page -->
                                    <!-- ---------------------------------- -->
                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow" href="javascript:void(0)"
                                            aria-expanded="false">
                                            <span class="d-flex">
                                                <i class="ti ti-layout-grid"></i>
                                            </span>
                                            <span class="hide-menu">Frontend page</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse first-level">
                                            <li class="sidebar-item">
                                                <a href="{{ asset('modernize/main/frontend-landingpage.html') }}"
                                                    class="sidebar-link">
                                                    <div
                                                        class="round-16 d-flex align-items-center justify-content-center">
                                                        <i class="ti ti-circle"></i>
                                                    </div>
                                                    <span class="hide-menu">Homepage</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a href="{{ asset('modernize/main/frontend-aboutpage.html') }}"
                                                    class="sidebar-link">
                                                    <div
                                                        class="round-16 d-flex align-items-center justify-content-center">
                                                        <i class="ti ti-circle"></i>
                                                    </div>
                                                    <span class="hide-menu">About Us</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a href="{{ asset('modernize/main/frontend-contactpage.html') }}"
                                                    class="sidebar-link">
                                                    <div
                                                        class="round-16 d-flex align-items-center justify-content-center">
                                                        <i class="ti ti-circle"></i>
                                                    </div>
                                                    <span class="hide-menu">Contact Us</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a href="{{ asset('modernize/main/frontend-blogpage.html') }}"
                                                    class="sidebar-link">
                                                    <div
                                                        class="round-16 d-flex align-items-center justify-content-center">
                                                        <i class="ti ti-circle"></i>
                                                    </div>
                                                    <span class="hide-menu">Blog</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a href="{{ asset('modernize/main/frontend-blogdetailpage.html') }}"
                                                    class="sidebar-link">
                                                    <div
                                                        class="round-16 d-flex align-items-center justify-content-center">
                                                        <i class="ti ti-circle"></i>
                                                    </div>
                                                    <span class="hide-menu">Blog Details</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <!-- ---------------------------------- -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="simplebar-placeholder" style="width: 269px; height: 399px;"></div>
            </div>
            <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                <div class="simplebar-scrollbar"
                    style="width: 0px; display: none; transform: translate3d(0px, 0px, 0px);"></div>
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
                aria-label="logout" data-bs-toggle="tooltip" title="Logout">
                    <i class="ti ti-power fs-6"></i>
                </button>
            </div>
        </div>

        <!-- ---------------------------------- -->
        <!-- Start Vertical Layout Sidebar -->
        <!-- ---------------------------------- -->
    </div>
</aside>
