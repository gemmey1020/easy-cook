
<style>
    /* === Easy Cook – Sidebar Pills (design-only) === */
    :root{
        --pill-active-from:#2e7d32;   /* deep green */
        --pill-active-to:#1b5e20;
        --pill-idle-from:#a9d6ba;     /* soft mint */
        --pill-idle-to:#87c89f;
        --pill-text:#1f3b2a;
    }

    .sidebar-wrapper {
        width: 96px;
        background: transparent;
        border-right: 0;
        position: relative;
    }

    .theme-logo .navbar-logo{
        max-height: 70px;
        margin: 12px 0 0;
        display:block;
    }

    .sidebar-wrapper .menu-categories{
        display:flex;
        flex-direction:column;
        gap:24px;
        padding: 8px 12px 24px;
        list-style:none;
        margin:0;
    }

    .sidebar-wrapper .menu-categories .menu .menu-toggle{
        display:block;
        text-decoration:none;
    }

    .sidebar-wrapper .base-menu{
        width: 72px;
        height: 130px;
        border-radius: 20px;
        display:flex;
        flex-direction:column;
        align-items:center;
        justify-content:center;
        gap:10px;
        padding:16px 8px;
        background: linear-gradient(180deg,var(--pill-idle-from),var(--pill-idle-to));
        box-shadow: 0 8px 20px rgba(0,0,0,.08);
        transition: transform .15s ease, box-shadow .2s ease, background .2s ease;
    }

    .sidebar-wrapper .base-menu .base-icons svg{
        width:28px;height:28px;
        stroke: var(--pill-text);
        opacity:.9;
        display:block;
    }

    .sidebar-wrapper .base-menu span{
        color: var(--pill-text);
        font-size:12px;
        line-height:1.1;
        text-align:center;
        white-space: normal !important;     /* allow wrapping instead of truncation */
        word-break: break-word;             /* keep long labels readable */
        max-width:100%;
    }

    /* hover lift */
    .sidebar-wrapper .menu:not(.active):hover .base-menu{
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(0,0,0,.12);
    }

    /* active state */
    .sidebar-wrapper .menu.active .base-menu{
        background: linear-gradient(180deg,var(--pill-active-from),var(--pill-active-to));
        color:#fff;
        box-shadow: 0 10px 26px rgba(46,125,50,.45);
    }
    .sidebar-wrapper .menu.active .base-menu .base-icons svg{ stroke:#fff; }
    .sidebar-wrapper .menu.active .base-menu span{ color:#fff; }

    /* collapse button bubble */
    .sidebarCollapseFixed{
        position:absolute; top:16px; right:-12px;
        width:36px; height:36px; border-radius:999px;
        background:#fff; display:flex; align-items:center; justify-content:center;
        box-shadow:0 4px 14px rgba(0,0,0,.12); cursor:pointer;
        z-index: 3;
    }
    .sidebarCollapseFixed svg{ width:18px;height:18px; }

    /* small screens / short heights */
    @media (max-height: 700px){
        .sidebar-wrapper .base-menu{ height:110px; }
    }
</style>

@php
    $dashboardHomeActive = request()->routeIs('dashboard.home');
    $discountOpsActive = request()->routeIs('discount-ops.*')
        || request()->routeIs('invitations.*')
        || request()->routeIs('coupons-v2.*');
@endphp

<!--  BEGIN SIDEBAR  -->
<div class="sidebar-wrapper sidebar-theme">

    <div class="theme-logo d-flex justify-content-center align-items-center">
        <a href="{{ url('/') }}" class="navbar-brand" title="Easy Cook">
            <img src="{{ url('/') }}/assets/img/logo.png" alt="Easy Cook" class="navbar-logo">
        </a>
    </div>

    <div class="sidebarCollapseFixed" title="Collapse sidebar">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="feather feather-arrow-left">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
    </div>

    <nav id="compactSidebar" aria-label="Sidebar Navigation">
        <ul class="menu-categories mt-4">
            <li class="menu {{ $dashboardHomeActive ? 'active' : '' }}">
                <a href="{{ route('dashboard.home') }}" data-active="{{ $dashboardHomeActive ? 'true' : 'false' }}" class="menu-toggle" title="Dashboard">
                    <div class="base-menu">
                        <div class="base-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-home">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                        </div>
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>

            {{-- Subscriptions --}}
            @if (App\Models\user_has_roles::where('user_id', Auth::id())->where('role_id', App\Models\Role::ADMIN_ROLE)->exists() || App\Models\user_has_roles::where('user_id', Auth::id())->where('role_id', App\Models\Role::SALES_ROLE)->exists())
                <li class="menu menu-single">
                    <a href="{{ route('subscriptions.index') }}" data-active="false" class="menu-toggle" title="Subscriptions">
                        <div class="base-menu">
                            <div class="base-icons">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-credit-card">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                            </div>
                            <span>Subscriptions</span>
                        </div>
                    </a>
                </li>
            @endif

            {{-- Assessments --}}
            @if (App\Models\user_has_roles::where('user_id', Auth::id())->where('role_id', App\Models\Role::ADMIN_ROLE)->exists() || App\Models\user_has_roles::where('user_id', Auth::id())->where('role_id', App\Models\Role::NUTRITION_SPECIALIST_ROLE)->exists())
                <li class="menu menu-single">
                    <a href="{{ route('assessments.index') }}" data-active="false" class="menu-toggle" title="Assessments">
                        <div class="base-menu">
                            <div class="base-icons">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-clipboard">
                                    <path d="M16 4H8a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path>
                                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                </svg>
                            </div>
                            <span>Assessments</span>
                        </div>
                    </a>
                </li>
            @endif

            {{-- Nutrition --}}
            @if (App\Models\user_has_roles::where('user_id', Auth::id())->where('role_id', App\Models\Role::ADMIN_ROLE)->exists() || App\Models\user_has_roles::where('user_id', Auth::id())->where('role_id', App\Models\Role::NUTRITION_SPECIALIST_ROLE)->exists())
                <li class="menu menu-single">
                    <a href="{{ route('nutrition.index') }}" data-active="false" class="menu-toggle" title="Nutrition">
                        <div class="base-menu">
                            <div class="base-icons">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round" class="feather feather-bowl-spoon">
                                    <path d="M2 12a10 10 0 0 0 20 0"></path>
                                    <line x1="15" y1="5" x2="21" y2="11"></line>
                                    <circle cx="20" cy="12" r="2"></circle>
                                </svg>
                            </div>
                            <span>Nutrition</span>
                        </div>
                    </a>
                </li>
            @endif

            {{-- Users --}}
            @if (App\Models\user_has_roles::where('user_id', Auth::id())->where('role_id', App\Models\Role::ADMIN_ROLE)->exists())
                <li class="menu menu-single">
                    <a href="{{ route('users.index') }}" data-active="false" class="menu-toggle" title="Users">
                        <div class="base-menu">
                            <div class="base-icons">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </div>
                            <span>Users</span>
                        </div>
                    </a>
                </li>
            @endif

            {{-- Sales --}}
            @if (App\Models\user_has_roles::where('user_id', Auth::id())->where('role_id', App\Models\Role::ADMIN_ROLE)->exists() || App\Models\user_has_roles::where('user_id', Auth::id())->where('role_id', App\Models\Role::SALES_ROLE)->exists() || App\Models\user_has_roles::where('user_id', Auth::id())->where('role_id', App\Models\Role::ACCOUNTANT_ROLE)->exists())
                <li class="menu menu-single">
                    <a href="{{ route('sales.index') }}" data-active="false" class="menu-toggle" title="Sales">
                        <div class="base-menu">
                            <div class="base-icons">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                    <polyline points="17 6 23 6 23 12"></polyline>
                                </svg>
                            </div>
                            <span>Sales</span>
                        </div>
                    </a>
                </li>
            @endif

            {{-- Discount Ops --}}
            @if (config('coupon_v2.admin_enabled') && (App\Models\user_has_roles::where('user_id', Auth::id())->where('role_id', App\Models\Role::ADMIN_ROLE)->exists() || App\Models\user_has_roles::where('user_id', Auth::id())->where('role_id', App\Models\Role::SALES_ROLE)->exists()))
                <li class="menu menu-single {{ $discountOpsActive ? 'active' : '' }}">
                    <a href="{{ route('discount-ops.index') }}" data-active="{{ $discountOpsActive ? 'true' : 'false' }}" class="menu-toggle" title="Discount Ops">
                        <div class="base-menu">
                            <div class="base-icons">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round" class="feather feather-tag">
                                    <path d="M20.59 13.41 11 3H4v7l9.59 9.59a2 2 0 0 0 2.82 0l4.18-4.18a2 2 0 0 0 0-2.82z"></path>
                                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                </svg>
                            </div>
                            <span>Discount Ops</span>
                        </div>
                    </a>
                </li>
            @endif

            {{-- BRNC --}}
            @if (App\Models\user_has_roles::where('user_id', Auth::id())->where('role_id', App\Models\Role::ADMIN_ROLE)->exists() || App\Models\user_has_roles::where('user_id', Auth::id())->where('role_id', App\Models\Role::SALES_ROLE)->exists())
                <li class="menu menu-single">
                    <a href="{{ route('brnc.index') }}" data-active="false" class="menu-toggle" title="BRNC">
                        <div class="base-menu">
                            <div class="base-icons">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase">
                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                </svg>
                            </div>
                            <span>BRNC</span>
                        </div>
                    </a>
                </li>
            @endif

            <li class="menu menu-single">
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="menu-toggle" title="Logout">
                    <div class="base-menu">
                        <div class="base-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-log-out">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                        </div>
                        <span>Logout</span>
                    </div>
                </a>
                <form id="logout-form" action="{{ route('dashboard.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>
</div>
<!--  END SIDEBAR  -->
