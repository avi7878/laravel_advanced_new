<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" data-bg-class="bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin/dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <span class="avatar ">
                    <img src="{{ $general->getFileUrl(config('setting.app_logo'),'logo') }}"
                        alt="{{ config('setting.app_name') }}" class="rounded h-px-44 " />
                </span>
                <!-- <span class="avatar ">
                    <img src="{{ $general->getFileUrl(config('setting.app_logo'),'logo') }}"
                        alt="{{ config('setting.app_name') }}" class="rounded h-px-44 app-logo-full" />
                </span> -->
                
                <!-- <img src="{{ $general->getFileUrl(config('setting.app_favicon'),'logo') }}"
                 alt="App Icon"
                 class="rounded h-px-44 app-logo-icon d-none" /> -->
            </span>
            <!-- <span class="app-brand-text demo menu-text fw-bold ms-2">{{ config('setting.app_name') }}</span> -->
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="icon-base bx bx-chevron-left"></i>
        </a>
    </div>
    <div class="menu-divider mt-0"></div>
    <div class="menu-inner-shadow" style="display: none;"></div>
    <ul class="menu-inner py-1 ps ps--active-y">
        <li class="menu-item active-menu" data-active_menu_links="admin/dashboard">
            <a href="{{ route('admin/dashboard') }}" class="menu-link pjax">
                <i class="menu-icon icon-base bx bx-home-smile"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>
        @if($sessionUser->hasPermission('admin_user'))
        <li
            class="menu-item active-menu" data-active_menu_links="admin/user,admin/user/create,admin/user/view,admin/user/update">
            <a href="{{ route('admin/user') }}" class="menu-link pjax" data-pjax-cache="true" >
                <i class="menu-icon icon-base bx bx-user"></i>
                <div data-i18n="Users">Users</div>
            </a>
        </li>
        @endif
         @if($sessionUser->hasPermission('admin_plan'))
        <li class="menu-item active-menu" data-active_menu_links="admin/plan,admin/plan/create,admin/plan/view,admin/plan/update">
            <a href="{{ route('admin/plan') }}" class="menu-link pjax" data-pjax-cache="true">
                <i class="menu-icon icon-base bx bx-file"></i>
                <div data-i18n="Plans">Plans</div>
            </a>
        </li>
        @endif

        @if($sessionUser->hasPermission('admin_product'))
        <li class="menu-item active-menu" data-active_menu_links="admin/product,admin/product/create,admin/product/view,admin/product/update">
            <a href="{{ route('admin/product') }}" class="menu-link pjax" data-pjax-cache="true">
                <i class="menu-icon icon-base bx bx-box"></i>
                <div data-i18n="Products">Products</div>
            </a>
        </li>
        @endif

         @if($sessionUser->hasPermission('admin_transaction'))
        <li class="menu-item active-menu" data-active_menu_links="admin/transaction,admin/transaction/create,admin/transaction/view,admin/transaction/update">
            <a href="{{ route('admin/transaction') }}" class="menu-link pjax" data-pjax-cache="true">
                <i class="menu-icon icon-base bx bx-credit-card"></i>
                <div data-i18n="Transactions">Transactions</div>
            </a>
        </li>
        @endif

         @if($sessionUser->hasPermission('admin_order'))
        <li class="menu-item active-menu" data-active_menu_links="admin/order,admin/order/create,admin/order/view,admin/order/update">
            <a href="{{ route('admin/order') }}" class="menu-link pjax" data-pjax-cache="true">
                <i class="menu-icon icon-base bx bx-credit-card"></i>
                <div data-i18n="Orders">Orders</div>
            </a>
        </li>
        @endif
        
        @if ($sessionUser->hasPermission('admin_notes'))
        <li
            class="menu-item active-menu" data-active_menu_links="admin/notes/create,admin/notes/update,admin/notes/view,admin/notes">
            <a href="{{ route('admin/notes') }}" class="menu-link pjax" data-pjax-cache="true">
                <i class="menu-icon icon-base bx bx-note"></i>
                <div data-i18n="Notes">Notes</div>
            </a>
        </li>
        @endif
                        
        @if ($sessionUser->hasPermission(['admin_blog', 'admin_blog_category']))
        <li
            class="menu-item active-menu" data-pjax-class="open">
            <a href="javascript:void(0);" class="menu-link menu-toggle pjax">
                <i class="menu-icon icon-base bx bx-notepad"></i>

                <div data-i18n="Blog">Blog</div>
            </a>
            <ul class="menu-sub ">
                @if ($sessionUser->hasPermission('admin_blog'))
                <li
                    class="menu-item active-menu" data-active_menu_links="admin/blog/create,admin/blog/update,admin/blog/view,admin/blog/index">
                    <a href="admin/blog/index" class="menu-link pjax">
                        <div data-i18n="Blog">Blog</div>
                    </a>
                </li>
                @endif
                @if ($sessionUser->hasPermission('admin_blog_category'))
                <li
                    class="menu-item active-menu" data-active_menu_links="admin/blog_category/create,admin/blog_category/update,admin/blog_category/index">
                    <a href="{{ route('admin/blog_category/index') }}" class="menu-link pjax" data-pjax-cache="true">
                        <div data-i18n="Blog Category">Blog Category</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif
        @if($sessionUser->hasPermission(['admin_setting', 'admin_seo', 'admin_admin', 'admin_device', 'admin_user_activity', 'admin_page', 'admin_emailtemplate']))
        <li
            class="menu-item active-menu" data-active_menu_class="open">
            <a href="javascript:void(0);"
                class="menu-link menu-toggle pjax">
                <i class="menu-icon icon-base bx bx-cog"></i>
                <div data-i18n="Setting">Setting</div>
            </a>
            <ul class="menu-sub">
                @if($sessionUser->hasPermission('admin_setting'))
                <li class="menu-item active-menu" data-active_menu_links="admin/setting/update">
                    <a href="{{ route('admin/setting/update') }}" class="menu-link pjax">
                        <div data-i18n="Setting">Setting</div>
                    </a>
                </li>
                @endif

                @if($sessionUser->hasPermission('admin_seo'))
                <li
                    class="menu-item active-menu" data-active_menu_links="admin/seo/create,admin/seo/update,admin/seo/meta">
                    <a href="{{ route('admin/seo/meta') }}" class="menu-link pjax" data-pjax-cache="true" data-active_menu_links="admin/seo/create">
                        <div data-i18n="Seo Meta">Seo Meta</div>
                    </a>
                </li>
                @endif

                @if($sessionUser->hasPermission('admin_admin'))
                <li
                    class="menu-item active-menu" data-active_menu_links="admin/admin,admin/admin/create,admin/admin/view,admin/admin/update">
                    <a href="{{ route('admin/admin') }}" class="menu-link pjax" data-pjax-cache="true">
                        <div data-i18n="Admin">Admin</div>
                    </a>
                </li>
                @endif

                @if($sessionUser->hasPermission('admin_device'))
                <li class="menu-item active-menu" data-active_menu_links="admin/device">
                    <a href="{{ route('admin/device') }}" class="menu-link pjax" data-pjax-cache="true">
                        <div data-i18n="Device">Device</div>
                    </a>
                </li>
                @endif

                @if($sessionUser->hasPermission('admin_user_activity'))
                <li class="menu-item active-menu" data-active_menu_links="admin/user-activity">
                    <a href="{{ route('admin/user-activity') }}" class="menu-link pjax" data-pjax-cache="true">
                        <div data-i18n="Activity">Activity</div>
                    </a>
                </li>
                @endif

                @if($sessionUser->hasPermission('admin_page'))
                <li class="menu-item active-menu" data-active_menu_links="admin/page,admin/page/update">
                    <a href="{{ route('admin/page') }}" class="menu-link pjax" data-pjax-cache="true">
                        <div data-i18n="Pages">Pages</div>
                    </a>
                </li>
                @endif

                @if($sessionUser->hasPermission('admin_emailtemplate'))
                <li class="menu-item active-menu" data-active_menu_links="admin/email-template,admin/email-template/update">
                    <a href="{{ route('admin/email-template') }}" class="menu-link pjax" data-pjax-cache="true">
                        <div data-i18n="Email Template">Email Template</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif
        <li class="menu-item">
            <a href="{{ route('admin/auth/logout') }}" class="menu-link">
                <i class="menu-icon" style="font-size:12px;"><svg class="dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width:22px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg></i>
                <div>Logout</div>
            </a>
        </li>
    </ul>
</aside>