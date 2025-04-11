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