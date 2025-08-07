<nav class="layout-navbar shadow-none py-0 layout-navbar container-xxl bg-navbar-theme"
    id="layout-navbar">
    <div class="navbar-expand-lg navbar landing-navbar px-3 px-md-8">   
        <!-- <div class="navbar-nav-right d-flex align-items-center justify-content-end "> -->
            <div class="navbar-brand app-brand demo d-flex py-0 me-4 me-xl-8">
                <a href="{{ route('home') }}" class="app-brand-link d-inline-block gap-1 pjax">
                    <span class="avatar me-2">
                        <img src="{{ $general->getFileUrl(config('setting.app_logo'),'logo')}}"
                            alt="{{ config('setting.app_name') }}" class="rounded h-px-44" />
                    </span>
                    <!-- <span class="app-brand-text demo menu-text fw-bold text-heading">{{ config('setting.app_name') }}</span> -->
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse landing-nav-menu" id="navbarSupportedContent">
                <button class="navbar-toggler border-0 text-heading position-absolute end-0 top-0 scaleX-n1-rtl p-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="icon-base bx bx-x icon-lg"></i>
                </button>
                <ul class="navbar-nav me-auto">
                    <li class="nav-item active-menu" data-active_menu_links="home">
                        <a class="nav-link pjax" aria-current="page"
                            href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item active-menu" data-active_menu_links="contact">
                        <a class="nav-link  pjax" data-pjax-cache="true"
                            href="{{ route('contact') }} ">Contact</a>
                    </li>
                    <!--Advanced-->
                    <li class="nav-item">
                        <a href="{{ route('blog') }}" class="nav-link pjax">Blogs</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('plan') }}" class="nav-link pjax">Plans</a>
                    </li>
                    <?php if (!auth()->guest()) { ?>
                    <li class="nav-item">
                        <a href="{{ route('note') }}" class="nav-link pjax ">Notes</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('chat') }}" class="nav-link pjax">Chat</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('support') }}" class="nav-link">Support</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('billing-portal') }}" class="nav-link">Billing Portal</a>
                    </li>
                    <?php } ?>
                    <!--Advanced end -->
                    
                </ul>
                
            </div>
            <div class="navbar-nav d-flex align-items-center" id="navbar-collapse">
                    <ul class="navbar-nav flex-row align-items-center ms-auto">
                        <!-- User -->
                        <?php if ($sessionUser) { ?>
                        <li class="nav-item navbar-dropdown dropdown-user dropdown">
                            <a class="nav-link dropdown-toggle hide-arrow p-0 pjax" href="javascript:void(0);"
                                data-bs-toggle="dropdown">
                                <div class="avatar avatar-online">
                                    <img src="{{ $general->getFileUrl($sessionUser->image,'profile') }}" alt
                                        class="w-px-40 h-auto rounded-circle" />
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item pjax" href="{{ route('account/update') }}">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar avatar-online">
                                                    <img src="{{ $general->getFileUrl($sessionUser->image,'profile') }}" alt
                                                        class="rounded-circle" />
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <span
                                                    class="fw-semibold d-block">{{$sessionUser->first_name.' '.$sessionUser->last_name}}</span>
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
                                        <i class="icon-base bx bx-user icon-md me-3"></i>
                                        <span class="align-middle">My Account</span>
                                    </a>
                                </li>
                                <li>
                                    <div class="dropdown-divider"></div>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}">
                                        <i class="icon-base bx bx-power-off icon-md me-3"></i>
                                        <span class="align-middle">Log Out</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php } else { ?>
                        <li class="menu-item {{ $general->routeMatchClass('login')}}">
                            <a href="login" class="menu-link btn rounded-pill btn-primary text-white pjax">
                                <div data-i18n="Login">Login
                                    <span class="icon-base bx bx-log-in-circle icon-sm "></span>
                                </div>
                            </a>
                        </li>
                        <br>
                        <li class="menu-item {{ $general->routeMatchClass('register')}} ms-2">
                            <a href="register" class="menu-link btn rounded-pill btn-primary text-white pjax">
                                <div data-i18n="Register">Register <span class="icon-base bx bx-user icon-sm "></span></div>
                            </a>
                        </li>
                        <?php } ?>
                        <!--/ User -->
                    </ul>
            </div>
        <!-- </div> -->
    </div>
</nav>



