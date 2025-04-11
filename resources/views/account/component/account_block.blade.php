<!-- Header -->
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">

                <div class="flex-shrink-0 mt-n2 mx-sm-0" style="display: flex; justify-content: center; align-items: flex-end;position: relative;">
                    <img src="{{ $general->getFileUrl($model->image,'profile') }}" alt="image" height="100px" width="100px" style="border-radius: 100%;">
                    <fa-camera style="position: absolute;top: 1px;right: 0;left: 80px; background-color: #7367f0; padding: 1px 24px 5px 3px; border-radius: 70%; color: white;"><i class="ti ti-camera" onclick="app.showModalView('account/image')">
                        </i> <fa-camera>
                </div>
                <div class="flex-grow-1 mt-3 mt-sm-5">
                    <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">
                            <h4>{{ $model->first_name }} {{ $model->last_name }}</h4>
                            <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                <li class="list-inline-item"><i class="ti ti-mail"></i> {{ $model->email }}</li>
                            </ul>
                        </div>
                       
                        </a>
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
        <ul class="nav nav-pills flex-column flex-sm-row mb-4">
            <li class="nav-item">
                <a class="nav-link pjax {{ $general->routeMatchClass('account/update') }}" href="account/update"><i class="ti-xs ti ti-user-check me-1"></i>Account</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pjax {{ $general->routeMatchClass('account/password-change')}}" href="account/password-change"><i class="ti-xs ti ti-key me-1"></i> Password Change</a>
            </li>

            <li class="nav-item">
                <a class="nav-link pjax {{ $general->routeMatchClass('account/tfa')}}" href="account/tfa"><i class="ti-xs ti ti-lock me-1"></i> Two Factor Authentication</a>
            </li>

            <li class="nav-item">
                <a class="nav-link pjax {{ $general->routeMatchClass('account/device')}}" href="{{ route('account/device') }}"><i class="ti-xs ti ti-bell me-1"></i> Device</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pjax {{ $general->routeMatchClass('account/user-activity')}} " href="{{ route('account/user-activity') }}"><i class="ti-xs ti ti-link me-1"></i> Activity</a>
            </li>
        </ul>
    </div>
</div>