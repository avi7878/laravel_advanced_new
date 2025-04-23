<?php
$model = auth()->user();

?>
<!-- Header -->
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="d-flex align-items-start align-items-sm-center gap-6 pb-4 border-bottom">
                <div class="d-flex flex-column align-items-center mt-4">
                    <img src="{{ $general->getFileUrl($model->image,'profile') }}" class="d-block w-px-100 h-px-100 rounded" alt="image" height="100px" width="100px" style="border-radius: 100%;">
                    <div class="button-wrapper mx-2 mt-2 text-center">
                        <a onclick="app.showModalView('admin/account/image')" for="upload" class="btn btn-primary text-white" tabindex="0"> Upload new photo
                            <i class="mx-1 icon-base bx bx-camera" ></i>
                        </a>
                    </div>
                </div>
                <div class="flex-grow-1 mt-3 mt-sm-5">
                    <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">
                            <h4 class="mb-2 mt-lg-7">{{ $model->first_name }} {{ $model->last_name }}</h4>
                            <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-4 mt-4">
                                <li class="list-inline-item"><i class="icon-base bx bx-envelope me-2 align-top"></i> {{ $model->email }}</li>
                            </ul>
                        </div>
                        <!-- <a onclick="app.showModalView('admin/account/image')" class=" noroute btn btn-primary text-white" style="background-color:#685dd8;">
                            <i class="ti ti-photo me-1"></i>Account
                        </a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ Header -->

<!-- Navbar pills -->
<div class="row">
    <div class="col-md-12">
        <div class="nav-align-top">
        <ul class="nav nav-pills flex-column flex-sm-row mb-6 gap-sm-0 gap-2">
            <li class="nav-item">
                <a class="nav-link pjax {{ $general->routeMatchClass('admin/account/update')}}" href="{{ route('admin/account/update') }}"><i class="icon-base bx bx-user icon-sm me-1_5"></i> Account</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pjax {{ $general->routeMatchClass('admin/account/password-change')}}" href="{{ route('admin/account/password-change') }}"><i class="icon-base bx bx-key icon-sm me-1_5"></i> Password Change</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pjax {{ $general->routeMatchClass('admin/account/tfa')}}" href="{{ route('admin/account/tfa') }}"><i class="icon-base bx bx-lock-alt icon-sm me-1_5"></i> Two Factor Authentication</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pjax {{ $general->routeMatchClass('admin/account/device')}}" href="{{ route('admin/account/device') }}"><i class="icon-base bx bx-devices icon-sm me-1_5"></i> Device</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pjax {{ $general->routeMatchClass('admin/account/user-activity')}} " href="{{ route('admin/account/user-activity') }}"><i class="icon-base bx bx-history icon-sm me-1_5"></i> Log</a>
            </li>
        </ul>
        </div>
    </div>
</div>