<!-- Navbar -->
<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="container-xxl">
        <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
            <a href="{{ route('home') }}" class="app-brand-link gap-2">
                <span class="avatar me-2">
                    <img src="{{ config('setting.app_logo') }}" alt="{{ Config::get('setting.app_name') }}" class="rounded" />
                </span>
            </a>
            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
                <i class="ti ti-x ti-sm align-middle"></i>
            </a>
        </div>
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="ti ti-menu-2 ti-sm"></i>
            </a>
        </div>
        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Style Switcher -->
                <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <i class="ti ti-md"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                                <span class="align-middle"><i class="ti ti-sun me-2"></i>Light</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                                <span class="align-middle"><i class="ti ti-moon me-2"></i>Dark</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                                <span class="align-middle"><i class="ti ti-device-desktop me-2"></i>System</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- / Style Switcher-->
                <!-- User -->
                <?php if ($sessionUser) { ?>
                    <li class="nav-item navbar-dropdown dropdown-user dropdown">
                        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                            <div class="avatar">
                                <img src="{{ $general->getFileUrl($sessionUser->image,'profile') }}" alt class="rounded-circle" />
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item pjax" href="{{ route('account/update') }}">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar avatar-online">
                                                <img src="{{ $general->getFileUrl($sessionUser->image,'profile') }}" alt class="rounded-circle" />
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <span class="fw-semibold d-block">{{$sessionUser->first_name.' '.$sessionUser->last_name}}</span>
                                            <small class="text-muted">{{ $sessionUser->email }}</small>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                            </li>
                            <li>
                                <a class="dropdown-item pjax" href="{{ route('account/update') }}">
                                    <i class="ti ti-user-check me-2 ti-sm"></i>
                                    <span class="align-middle">My Account</span>
                                </a>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}">
                                    <i class="ti ti-logout me-2 ti-sm"></i>
                                    <span class="align-middle">Log Out</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } else { ?>
                    <li class="menu-item {{ $general->routeMatchClass('login')}}">
                        <a href="login" class="menu-link">
                            <div data-i18n="Login" style="padding-right: 10px;">Login</div>
                        </a>
                    </li>
                    <br>
                    <li class="menu-item {{ $general->routeMatchClass('account/register')}}">
                        <a href="account/register" class="menu-link">
                            <div data-i18n="Register">Register</div>
                        </a>
                    </li>
                <?php } ?>
                <!--/ User -->
            </ul>
        </div>
        <!-- Search Small Screens -->
        <div class="navbar-search-wrapper search-input-wrapper container-xxl d-none">
            <input type="text" class="form-control search-input border-0" placeholder="Search..." aria-label="Search..." />
            <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>
        </div>
    </div>
</nav>
<!-- / Navbar -->