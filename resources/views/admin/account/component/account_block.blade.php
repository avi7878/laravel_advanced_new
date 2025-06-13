<?php
$model = auth()->user();
?>
<!-- Navbar pills -->
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
