<header class="th-header header-layout3">
    <div class="sticky-wrapper">
        <div class="sticky-active">
            <!-- Main Menu Area -->
            <div class="menu-area">
                <div class="container th-container" id="media_removal">
                    <div class="row align-items-center justify-content-between ">
                        <div class="col-auto">
                            <div class="header-logo">
                                <a href="/"><img style="height:110px" src="{{ url('/') }}/assets/img/logo.png"
                                        alt="Easy Cook" /></a>
                            </div>
                        </div>
                        <div class="col-auto">
                            @php
                                use Illuminate\Support\Str;
                                $currentUrl = request()->path();
                            @endphp
                            <nav class="main-menu d-none d-lg-inline-block">
                                <ul>
                                    <li><a href="/" class="{{ $currentUrl === '/' ? 'active' : '' }}">Home</a></li>
                                    <li><a href="{{ url('/') }}/about" class="{{ Str::contains($currentUrl, 'about') ? 'active' : '' }}">About Us</a></li>
                                    <li><a href="{{ url('/') }}/plans" class="{{ Str::contains($currentUrl, 'plans') ? 'active' : '' }}">Plans</a></li>
                                    <li><a href="{{ url('/') }}/recipes" class="{{ Str::contains($currentUrl, 'recipes') ? 'active' : '' }}">Easy Recipes</a></li>
                                    <li><a href="{{ url('/') }}/easy-tips" class="{{ Str::contains($currentUrl, 'easy-tips') ? 'active' : '' }}">Easy Tips</a></li>
                                    <li><a href="{{ url('/') }}/blogs" class="{{ Str::contains($currentUrl, 'blogs') ? 'active' : '' }}">Blogs</a></li>
                                    <li><a href="{{ url('/') }}/contact" class="{{ Str::contains($currentUrl, 'contact') ? 'active' : '' }}">Contact Us</a></li>
                                </ul>
                            </nav>
                            <button id="toggleTheme" class=" toggleTheme my-2 mx-3 icon-btn d-lg-none d-inline-block d-lg-none">
                                <i  class=" themeIcon far fa-brightness"></i>
                            </button>

                            <button type="button" class="th-menu-toggle d-inline-block d-lg-none">
                                <i class="far fa-bars"></i>
                            </button>
                        </div>
                        <div class="col-auto d-none d-xl-block">
                            <div class="header-button">
                                <button id="toggleTheme" class="toggleTheme icon-btn" data-hover-icon="fa-moon">
                                    <i class=" themeIcon far fa-brightness"></i>
                                </button>
                                <button type="button" class="icon-btn searchBoxToggler">
                                    <i class="far fa-search"></i>
                                </button>
                                <button type="button" class="icon-btn sideMenuToggler d-none d-xl-block">
                                    <i class="far fa-cart-shopping"></i>
                                    <span class="badge">0</span>
                                </button>
                                <div class="dropdown d-none d-xl-block">
                                    <button class="icon-btn dropdown-toggle" type="button" id="userDropdown"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="far fa-user"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                        @if (Auth::check())
                                        <!-- If User is Authenticated -->
                                        {{-- <li><a class="dropdown-item" href="#">Profile</a></li> --}}
                                        <li><a class="dropdown-item" href="{{ route('user-dashboard') }}">User
                                                Panel</a>
                                        </li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit"
                                                    class="dropdown-item text-danger">Logout</button>
                                            </form>
                                        </li>
                                        @else
                                        <!-- If User is NOT Authenticated -->
                                        <li><a class="dropdown-item" href="{{ route('login_web') }}">Login</a></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas__stikcy--toolbar ">
        <ul class="d-flex justify-content-between justify-content-sm-evenly ">
            <li class="text-center">
                <a class="offcanvas__stikcy--toolbar__btn" href="/">
                    <i class="far fa-home"></i>
                    <span class="offcanvas__stikcy--toolbar__label">Home</span>
                </a>
            </li>
            <li class="offcanvas__stikcy--toolbar__list text-center">
                <a class="offcanvas__stikcy--toolbar__btn" href="plans">
                    <i class="fa-solid fa-utensils"></i>
                    <span class="offcanvas__stikcy--toolbar__label">Plans</span>
                </a>
            </li>
            <li class="header-button">
            <div class="dropup">
                <button class="icon-btn dropdown-toggle" type="button" id="userDropdown"
                    data-bs-toggle="dropdown" aria-expanded="true">
                    <i class="far fa-user"></i>
                </button>
    <ul class="dropdown-menu dropdown-menu-end"  aria-labelledby="userDropdown">
        @if (Auth::check())
        <!-- If User is Authenticated -->
        {{-- <li><a class="dropdown-item" href="#">Profile</a></li> --}}
        <li><a class="dropdown-item" href="{{ route('user-dashboard') }}">User Panel</a></li>
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item text-danger">Logout</button>
            </form>
        </li>
        @else
        <!-- If User is NOT Authenticated -->
        <li><a class="dropdown-item" href="{{ route('login_web') }}">Login</a></li>
        @endif
    </ul>
</div>

                <button type="button" class="icon-btn  d-block searchBoxToggler border border-success">
                    <i class="far fa-search"></i>
                </button>
                <button type="button" class="icon-btn  sideMenuToggler d-block border border-success">
                    <i class="far fa-cart-shopping"></i>
                    <span class="badge">0</span>
                </button>

        </li>

        </ul>
    </div>


</header>
<style>
.icon-btn {
    background: none;
    border: none;
    cursor: pointer;
    transition: transform 0.3s ease, color 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.icon-btn:hover {
    animation: heartbeat 1.2s infinite;
}
.main-menu ul li a.active {

        font-weight: bold;
        border-bottom: 2px solid #258a43;
        color: #258a43 !important;
        font-weight: 800 !important;
}


@keyframes heartbeat {
    0% { transform: scale(1); }
    25% { transform: scale(1.1); }
    50% { transform: scale(1); }
    75% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

</style>

@push('scripts')

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


@endpush
<script>
document.querySelectorAll(".toggleTheme").forEach(button => {
    let themeIcons = button.querySelectorAll(".themeIcon");


    button.setAttribute("data-current-icon", "fa-brightness");
    button.setAttribute("data-hover-icon", "fa-moon");


    button.addEventListener("click", function () {
        let currentIcon = this.getAttribute("data-current-icon");
        let hoverIcon = this.getAttribute("data-hover-icon");

        document.querySelectorAll(".themeIcon").forEach(icon => {
            icon.classList.replace(currentIcon, hoverIcon);
        });

        // تحديث القيم
        document.querySelectorAll(".toggleTheme").forEach(btn => {
            btn.setAttribute("data-current-icon", hoverIcon);
            btn.setAttribute("data-hover-icon", currentIcon);
        });
    });

    // تغيير الأيقونة عند الهافر لكل الأزرار
    button.addEventListener("mouseenter", function () {
        document.querySelectorAll(".themeIcon").forEach(icon => {
            icon.classList.replace(
                this.getAttribute("data-current-icon"),
                this.getAttribute("data-hover-icon")
            );
        });
    });

    // إعادة الأيقونة الأصلية عند إزالة الهافر لكل الأزرار
    // button.addEventListener("mouseleave", function () {
    //     document.querySelectorAll(".themeIcon").forEach(icon => {
    //         icon.classList.replace(
    //             this.getAttribute("data-hover-icon"),
    //             this.getAttribute("data-current-icon")
    //         );
    //     });
    // });
});
</script>
