<aside id="layout-menu" class="layout-menu menu-vertical menu">
    <div class="app-brand demo">
        <a href="{{ route('admin/account/update') }}" class="app-brand-link">
            <span class="app-brand-logo demo"> 
            <span class="avatar me-2 ">
                <img src="{{ $general->getFileUrl(config('setting.app_logo')) }}" alt="{{ Config::get('setting.app_name') }}" class="rounded h-px-44" />
            </span>
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2">Demo</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="icon-base bx bx-chevron-left"></i>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        <li class="menu-item {{ $general->routeMatchClass(['admin/dashboard']) }}">
            <a href="{{ route('admin/dashboard') }}" class="menu-link pjax">
                <i class="menu-icon icon-base bx bx-home-smile"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>
        @if($sessionUser->hasPermission('admin_user'))
        <li class="menu-item {{ $general->routeMatchClass(['admin/user/create','admin/user/update','admin/user/view','admin/user']) }}">
            <a href="{{ route('admin/user') }}" class="menu-link pjax" data-pjax-cache="true">
                <i class="menu-icon icon-base bx bx-user"></i>
                <div data-i18n="Users">Users</div>
            </a>
        </li>
        @endif

        @if($sessionUser->hasPermission(['admin_setting','admin_seo','admin_admin','admin_device','admin_log','admin_page']))
        <li class="menu-item {{ $general->routeMatchClass(['admin/setting/update','admin/seo/meta','admin/seo/create','admin/seo/update','admin/seo/delete','admin/admin','admin/admin/create','admin/admin/update','admin/admin/view','admin/admin/delete','admin/device','admin/user-activity','admin/page','admin/page/view','admin/page/update','admin/page/delete'], 'open') }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle pjax">
                <i class="menu-icon icon-base bx bx-cog"></i>
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

                @if($sessionUser->hasPermission('admin_user_activity'))
                <li class="menu-item {{ $general->routeMatchClass(['admin/user-activity']) }}">
                    <a href="{{ route('admin/user-activity') }}" class="menu-link pjax" data-pjax-cache="true">
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
                <i class="menu-icon icon-base bx bx-log-out "></i>
                <div>Logout</div>
            </a>
        </li>
    </ul>
</aside>