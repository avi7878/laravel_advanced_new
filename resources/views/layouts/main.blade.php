<?php
if (isset($_GET['partial']) && $_GET['partial']) {
    if (isset($_GET['layout']) && $_GET['layout'] == 'main') {
?>
        <div id="main-content" data-title="@yield('title') | {{config('setting.app_name')}}">
            {{ view('common/message_alert') }}
            @stack('styles')
            @yield('content')
            @stack('scripts')
        </div>
    <?php
    } else {
        echo 'reload';
    }
} else {
    $metaTags = $general->getMetaTags();
    $sessionUser = false;
    if (!auth()->guest()) {
        $sessionUser = auth()->user();
    }
    ?>
    <!DOCTYPE html>
    <html lang="{{ Config::get('app.locale') }}" class="dark-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="theme/assets/" data-template="horizontal-menu-template">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
        <base href="{{ URL::to('/') }}/">
        <meta http-equiv="Content-Language" content="{{ Config::get('app.locale') }}">
        @if($metaTags)
        {!! $metaTags !!}
        @else
        <title>@yield('title') | {{config('setting.app_name')}}</title>
        @endif
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
        <link rel="stylesheet" href="theme/assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
        <link rel="stylesheet" href="theme/assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
        <link rel="stylesheet" href="theme/assets/css/demo.css" />
        <!-- Vendors CSS -->
        <link rel="stylesheet" href="theme/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
        <link rel="stylesheet" href="theme/assets/vendor/libs/node-waves/node-waves.css" />
        <link rel="stylesheet" href="theme/assets/vendor/libs/typeahead-js/typeahead.css" />
        <link rel="stylesheet" href="theme/assets/vendor/libs/swiper/swiper.css" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.15.10/sweetalert2.min.css" integrity="sha512-Of+yU7HlIFqXQcG8Usdd67ejABz27o7CRB1tJCvzGYhTddCi4TZLVhh9tGaJCwlrBiodWCzAx+igo9oaNbUk5A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" integrity="sha512-UtLOu9C7NuThQhuXXrGwx9Jb/z9zPQJctuAgNUBK3Z6kkSYT9wJ+2+dh6klS+TDBCV9kNPBbAxbVD+vCcfGPaA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- Page CSS -->
        <link rel="stylesheet" href="assets/css/common.css?cache=off">
        <!-- Helpers -->
        <script src="theme/assets/vendor/js/helpers.js"></script>
        <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
        <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
        <script src="theme/assets/vendor/js/template-customizer.js"></script>
        <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
        <script src="theme/assets/js/config.js"></script>
        @stack('styles')
        <script>
            /*Global variables*/
            var APP_UID = '{{config("setting.app_uid")}}';
            var APP_URL="{{ URL::to('/') }}/";
            var CSRF_NAME = '_token';
            var CSRF_TOKEN = "{{ Session::token() }}";
            var dataTableObj = false;
            var documentReadyFunctions = [];

            function documentReady(fn) {
                documentReadyFunctions.push(fn);
            }
        </script>
        {!! config('setting.header_content') !!}
    </head>

    <body>
        <!-- Layout wrapper -->
        <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
            <div class="layout-container">
                <!-- Navbar -->
                <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
                    <div class="container-xxl">
                        <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
                            <a href="{{ route('home') }}" class="app-brand-link gap-2">
                                <span class="avatar me-2">
                                    <img src="{{ config('setting.app_logo') }}" alt="{{ Config::get('setting.app_name') }}" class="rounded" />
                                </span>
                                <!--<span class="app-brand-text demo menu-text  fw-bold">{{ Config::get('setting.app_name') }}</span>-->
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
                <!-- Layout container -->
                <div class="layout-page">
                    <!-- Content wrapper -->
                    <div class="content-wrapper">
                        <!-- Menu -->
                        <aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu bg-menu-theme flex-grow-0">
                            <div class="container-xxl d-flex h-100">
                                <ul class="menu-inner">
                                    <li class="menu-item pjax {{ $general->routeMatchClass('home') }}">
                                        <a href="{{ route('home') }}" class="menu-link pjax">
                                            <i class="menu-icon tf-icons ti ti-mail"></i>
                                            <div data-i18n="Home">Home</div>
                                        </a>
                                    </li>
                                    <li class="menu-item pjax {{ $general->routeMatchClass('contact') }}">
                                        <a href="{{ route('contact') }}" class="menu-link pjax">
                                            <i class="menu-icon tf-icons ti ti-calendar"></i>
                                            <div data-i18n="Contact">Contact</div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </aside>
                        <!-- / Menu -->
                        <!-- Content -->
                        <div class="container-xxl flex-grow-1 container-p-y" id="main-container" data-layout="main">
                            <div id="main-content" data-title="@yield('title') | {{config('setting.app_name')}}">
                                {{ view('common/message_alert') }}
                                @yield('content')
                            </div>
                        </div>
                        <!--/ Content -->
                        <!-- Footer -->
                        <footer class="content-footer footer bg-footer-theme">
                            <div class="container-xxl">
                                <div class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
                                    <div>
                                        ©{{date('Y')}} , made by <a href="{{route('home')}}" target="_blank" class="fw-semibold">{{ config('setting.app_name') }}</a>
                                    </div>
                                    <div>
                                        <a target="_blank" href="page/terms-condition">Terms & Condition</a> |
                                        <a target="_blank" href="page/Cullen-Patrick">Cullen Patrick</a> |
                                        <a target="_blank" href="page/privacy-policy">Privacy Policy</a>
                                    </div>
                                </div>
                            </div>

                        </footer>
                        <!-- / Footer -->
                    </div>
                    <!--/ Content wrapper -->
                </div>
                <!--/ Layout container -->
            </div>
            <!-- Overlay -->
            <div class="layout-overlay layout-menu-toggle"></div>
            <!-- Drag Target Area To SlideIn Menu On Small Screens -->
            <div class="drag-target"></div>
        </div>
        <!--/ Layout wrapper -->
        <div class="modal fade" id="common-modal">
            <div class="modal-dialog">
                <div class="modal-content" id="common-modal-content">
                </div>
            </div>
        </div>
        <!-- Core JS -->
        {{view('common/cookie_consent')}}
        <!-- build:js assets/vendor/js/core.js -->
        <script src="theme/assets/vendor/libs/jquery/jquery.js"></script>
        <script src="theme/assets/vendor/libs/popper/popper.js"></script>
        <script src="theme/assets/vendor/js/bootstrap.js"></script>
        <script src="theme/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
        <script src="theme/assets/vendor/libs/node-waves/node-waves.js"></script>
        <script src="theme/assets/vendor/libs/hammer/hammer.js"></script>
        <script src="theme/assets/vendor/libs/typeahead-js/typeahead.js"></script>
        <script src="theme/assets/vendor/js/menu.js"></script>
        <!-- endbuild -->
        <!-- Main JS -->
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
        {!! config('setting.footer_content') !!}
    </body>

    </html>
<?php } ?>