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
                {{ view('admin/layouts/component/main_sidebar',compact('sessionUser')) }}
                <!-- / Menu -->
                <!-- Layout container -->
                <div class="layout-page">
                    <!-- Navbar -->
                    {{ view('admin/layouts/component/main_navbar',compact('sessionUser')) }}
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