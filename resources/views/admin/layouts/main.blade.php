<?php
if (isset($_GET['partial']) && $_GET['partial']) {
    if (isset($_GET['layout']) && $_GET['layout'] == 'main') {
?>
        <div id="main-content" data-title="@yield('title') | {{ Config::get('setting.app_name') }}">
            {{ view('common/message_alert') }}
            @stack('styles')
            @yield('content')
            @stack('scripts')
        </div>
    <?php } else {
        echo 'reload';
    }
} else {
    $sessionUser = false;
    if (!auth()->guest()) {
        $sessionUser = auth()->user();
    }
    ?>
    <!DOCTYPE html>
    <html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="theme/assets/" data-template="vertical-menu-template-no-customizer">

    <head>
        <meta charset="utf-8" />
        <base href="{{ URL::to('/') }}/">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
        <title>@yield('title') | {{ config('setting.app_name') }}</title>
        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ config('setting.app_favicon') }}" type="image/x-icon">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

        <!-- Icons -->
        <link rel="stylesheet" href="theme/assets/vendor/fonts/fontawesome.css" />
        <link rel="stylesheet" href="theme/assets/vendor/fonts/tabler-icons.css" />
        <link rel="stylesheet" href="theme/assets/vendor/fonts/flag-icons.css" />

        <!-- Core CSS -->
        <link rel="stylesheet" href="theme/assets/vendor/css/rtl/core.css" class="template-customizer-core-css  " />
        <link rel="stylesheet" href="theme/assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
        <link rel="stylesheet" href="theme/assets/css/demo.css" />

        <!-- Vendors CSS -->
        <link rel="stylesheet" href="theme/assets/vendor/libs/node-waves/node-waves.css" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" integrity="sha512-UtLOu9C7NuThQhuXXrGwx9Jb/z9zPQJctuAgNUBK3Z6kkSYT9wJ+2+dh6klS+TDBCV9kNPBbAxbVD+vCcfGPaA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.15.10/sweetalert2.min.css" integrity="sha512-Of+yU7HlIFqXQcG8Usdd67ejABz27o7CRB1tJCvzGYhTddCi4TZLVhh9tGaJCwlrBiodWCzAx+igo9oaNbUk5A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
        <link rel="stylesheet" href="theme/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
        <link rel="stylesheet" href="theme/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
        <link rel="stylesheet" href="theme/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
        
        
        <link rel="stylesheet" href="assets/css/common.css" />

        @stack('style')

        <!-- Helpers -->
        <script src="theme/assets/vendor/js/helpers.js"></script>
        <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
        <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
        <script src="theme/assets/vendor/js/template-customizer.js"></script>
        <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
        <script src="theme/assets/js/config.js"></script>
        <script>
            /*Global variables*/
            var APP_UID = '{{config("setting.app_uid")}}';
            var CSRF_NAME = '_token';
            var CSRF_TOKEN = "{{ Session::token() }}";
            var dataTableObj = false;
            var documentReadyFunctions = [];
            function documentReady(fn) {
                documentReadyFunctions.push(fn);
            }
        </script>
    </head>

    <body>
        
        <!-- Layout wrapper -->
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <!-- Menu -->
                <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                    <div class="app-brand demo">
                        <a href="{{ route('admin/account/update') }}" class="app-brand-link">
                            <span class="avatar me-2">
                                <img src="{{ config('setting.app_logo') }}" alt="{{ Config::get('setting.app_name') }}" class="rounded" />
                            </span>
                        </a>

                        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
                            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
                        </a>
                    </div>
                    <div class="menu-inner-shadow"></div>
                    <ul class="menu-inner py-1">
                        <li class="menu-item {{ $general->routeMatchClass(['admin/dashboard']) }}">
                            <a href="{{ route('admin/dashboard') }}" class="menu-link pjax">
                                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                                <div data-i18n="Dashboard">Dashboard</div>
                            </a>
                        </li>
                        @if($sessionUser->hasPermission('admin_user'))
                        <li class="menu-item {{ $general->routeMatchClass(['admin/user/create','admin/user/update','admin/user/view','admin/user']) }}">
                            <a href="{{ route('admin/user') }}" class="menu-link pjax" data-pjax-cache="true" >
                                <i class="menu-icon tf-icons ti ti-users"></i>
                                <div data-i18n="Users">Users</div>
                            </a>
                        </li>
                        @endif

                        @if($sessionUser->hasPermission(['admin_setting','admin_seo','admin_admin','admin_device','admin_log','admin_page']))
                        <li class="menu-item {{ $general->routeMatchClass(['admin/setting/update','admin/seo/meta','admin/seo/create','admin/seo/update','admin/seo/delete','admin/admin','admin/admin/create','admin/admin/update','admin/admin/view','admin/admin/delete','admin/device','admin/activity','admin/page','admin/page/view','admin/page/update','admin/page/delete'], 'open') }}">
                            <a href="javascript:void(0);" class="menu-link menu-toggle pjax">
                                <i class="menu-icon tf-icons ti ti-settings"></i>
                                <div data-i18n="Setting">Setting</div>
                            </a>
                            <ul class="menu-sub">
                                @if($sessionUser->hasPermission('admin_setting'))
                                <li class="menu-item {{ $general->routeMatchClass(['admin/setting/update']) }}">
                                    <a href="{{ route('admin/setting/update') }}" class="menu-link pjax">
                                        <div data-i18n="Setting">Setting</div>
                                    </a>
                                </li>
                                @endif

                                @if($sessionUser->hasPermission('admin_seo'))
                                <li class="menu-item {{ $general->routeMatchClass(['admin/seo/create','admin/seo/update','admin/seo/meta']) }}">
                                    <a href="{{ route('admin/seo/meta') }}" class="menu-link pjax" data-pjax-cache="true" >
                                        <div data-i18n="Seo Meta">Seo Meta</div>
                                    </a>
                                </li>
                                @endif

                                @if($sessionUser->hasPermission('admin_admin'))
                                <li class="menu-item {{ $general->routeMatchClass(['admin/admin/create','admin/admin/update','admin/admin/view','admin/admin']) }}">
                                    <a href="{{ route('admin/admin') }}" class="menu-link pjax" data-pjax-cache="true" >
                                        <div data-i18n="Admin">Admin</div>
                                    </a>
                                </li>
                                @endif

                                @if($sessionUser->hasPermission('admin_device'))
                                <li class="menu-item {{ $general->routeMatchClass(['admin/device']) }}">
                                    <a href="{{ route('admin/device') }}" class="menu-link pjax" data-pjax-cache="true" >
                                        <div data-i18n="Device">Device</div>
                                    </a>
                                </li>
                                @endif

                                @if($sessionUser->hasPermission('admin_log'))
                                <li class="menu-item {{ $general->routeMatchClass(['admin/activity']) }}">
                                    <a href="{{ route('admin/activity') }}" class="menu-link pjax" data-pjax-cache="true" >
                                        <div data-i18n="Activity">Activity</div>
                                    </a>
                                </li>
                                @endif

                                @if($sessionUser->hasPermission('admin_page'))
                                <li class="menu-item {{ $general->routeMatchClass(['admin/page/update','admin/page/view','admin/page']) }}">
                                    <a href="{{ route('admin/page') }}" class="menu-link pjax" data-pjax-cache="true" >
                                        <div data-i18n="Pages">Pages</div>
                                    </a>
                                </li>
                                @endif

                                @if($sessionUser->hasPermission('admin_emailtemplate'))
                                <li class="menu-item {{ $general->routeMatchClass(['admin/email-template/update','admin/email-template/view','admin/email-template']) }}">
                                    <a href="{{ route('admin/email-template') }}" class="menu-link pjax" data-pjax-cache="true" >
                                        <div data-i18n="Email Template">Email Template</div>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif


                        <li class="menu-item">
                            <a href="{{ route('admin/auth/logout') }}" class="menu-link noroute">
                                <i class="menu-icon tf-icons ti ti-logout me-2 ti-sm"></i>
                                <div>Logout</div>
                            </a>
                        </li>
                    </ul>
                </aside>
                <!-- / Menu -->
                <!-- Layout container -->
                <div class="layout-page">
                    <!-- Navbar -->

                    <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                                <i class="ti ti-menu-2 ti-sm"></i>
                            </a>
                        </div>

                        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                            <!-- Search -->
                            <div class="navbar-nav align-items-center">
                                <div class="nav-item navbar-search-wrapper mb-0">

                                </div>
                            </div>
                            <!-- /Search -->

                            <ul class="navbar-nav flex-row align-items-center ms-auto">
                                <!-- User -->
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
                                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                        <div class="avatar avatar">
                                            <img src="{{ $general->getFileUrl($sessionUser->image,'profile') }}" alt class="rounded-circle" />
                                        </div>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item pjax" href="{{ route('admin/account/update') }}">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar avatar">
                                                            <img src="{{ $general->getFileUrl($sessionUser->image,'profile') }}" alt class="rounded-circle" />
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <span class="fw-semibold d-block">{{ $sessionUser->first_name.' '.$sessionUser->last_name }}</span>
                                                        <small class="text-muted">{{ $sessionUser->email }}</small>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                        </li>
                                        <li>
                                            <a class="dropdown-item pjax" href="{{ route('admin/account/update') }}">
                                                <i class="ti ti-user-check me-2 ti-sm"></i>
                                                <span class="align-middle">My Account</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item noroute" href="{{ route('admin/auth/logout') }}">
                                                <i class="ti ti-logout me-2 ti-sm"></i>
                                                <span class="align-middle">Log Out</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <!--/ User -->
                            </ul>
                        </div>

                        <!-- Search Small Screens -->
                        <div class="navbar-search-wrapper search-input-wrapper d-none">
                            <input type="text" class="form-control search-input container-xxl border-0" placeholder="Search..." aria-label="Search..." />
                            <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>
                        </div>
                    </nav>
                    <!-- / Navbar -->
                    <div class="content-wrapper">
                        <div class="container-xxl flex-grow-1 container-p-y" id="main-container" data-layout="main">
                            <div id="main-content" data-title="@yield('title') | {{config('setting.app_name')}}">
                                {{ view('common/message_alert') }}
                                @yield('content')
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
                                <div>
                                    ©{{date('Y')}}, made by <a href="admin/dashboard" target="_self" class="fw-semibold">{{ config('setting.app_name') }}</a>
                                </div>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->
                </div>
            </div>
            <div class="layout-overlay layout-menu-toggle"></div>
            <div class="drag-target"></div>
        </div>
        <div class="modal fade" id="common-modal">
            <div class="modal-dialog">
                <div class="modal-content" id="common-modal-content">
                </div>
            </div>
        </div>
       
        <script src="theme/assets/vendor/libs/jquery/jquery.js"></script>
        <script src="theme/assets/vendor/libs/popper/popper.js"></script>
        <script src="theme/assets/vendor/js/bootstrap.js"></script>
        <script src="theme/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
        <script src="theme/assets/vendor/libs/node-waves/node-waves.js"></script>

        <script src="theme/assets/vendor/libs/hammer/hammer.js"></script>
        <script src="theme/assets/vendor/libs/i18n/i18n.js"></script>
        <script src="theme/assets/vendor/libs/typeahead-js/typeahead.js"></script>
        <script src="theme/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
        <script src="theme/assets/vendor/js/menu.js"></script>

        <script src="theme/assets/js/main.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js" integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.15.9/sweetalert2.min.js" integrity="sha512-42SOMmTiQryVFk+kJc8Mk1YCoPYvTSX1KCz7sZOGGFcBzytpPLeKuF6AOOQvln5zrUBDjJqshCdMGYRVC/BsYg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js" integrity="sha512-JyCZjCOZoyeQZSd5+YEAcFgz2fowJ1F1hyJOXgtKu4llIa0KneLcidn5bwfutiehUTiOuK87A986BZJMko0eWQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="assets/js/common.js"></script>
        <script src="assets/js/pjax.js"></script>
        <script src="assets/js/app.js"></script>
        @stack('scripts')
        <script>
            $(document).ready(function() {
                runDocumentReady();
            });
        </script>
    </body>

    </html>
<?php } ?>