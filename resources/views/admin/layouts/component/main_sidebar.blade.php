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
            <a href="{{ route('admin/user') }}" class="menu-link pjax" data-pjax-cache="true">
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
                    <a href="{{ route('admin/seo/meta') }}" class="menu-link pjax" data-pjax-cache="true">
                        <div data-i18n="Seo Meta">Seo Meta</div>
                    </a>
                </li>
                @endif

                @if($sessionUser->hasPermission('admin_admin'))
                <li class="menu-item {{ $general->routeMatchClass(['admin/admin/create','admin/admin/update','admin/admin/view','admin/admin']) }}">
                    <a href="{{ route('admin/admin') }}" class="menu-link pjax" data-pjax-cache="true">
                        <div data-i18n="Admin">Admin</div>
                    </a>
                </li>
                @endif

                @if($sessionUser->hasPermission('admin_device'))
                <li class="menu-item {{ $general->routeMatchClass(['admin/device']) }}">
                    <a href="{{ route('admin/device') }}" class="menu-link pjax" data-pjax-cache="true">
                        <div data-i18n="Device">Device</div>
                    </a>
                </li>
                @endif

                @if($sessionUser->hasPermission('admin_log'))
                <li class="menu-item {{ $general->routeMatchClass(['admin/activity']) }}">
                    <a href="{{ route('admin/activity') }}" class="menu-link pjax" data-pjax-cache="true">
                        <div data-i18n="Activity">Activity</div>
                    </a>
                </li>
                @endif

                @if($sessionUser->hasPermission('admin_page'))
                <li class="menu-item {{ $general->routeMatchClass(['admin/page/update','admin/page/view','admin/page']) }}">
                    <a href="{{ route('admin/page') }}" class="menu-link pjax" data-pjax-cache="true">
                        <div data-i18n="Pages">Pages</div>
                    </a>
                </li>
                @endif

                @if($sessionUser->hasPermission('admin_emailtemplate'))
                <li class="menu-item {{ $general->routeMatchClass(['admin/email-template/update','admin/email-template/view','admin/email-template']) }}">
                    <a href="{{ route('admin/email-template') }}" class="menu-link pjax" data-pjax-cache="true">
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