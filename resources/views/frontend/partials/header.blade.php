<header class="header-section d-lg-block d-none">

    <!-- Start Header Center Area -->
    <div class="header-center sticky-header">
        <div class="container">
            <div class="row d-flex justify-content-between align-items-center">
                <div class="col-3">
                    <!-- Logo Header -->
                    <div class="header-logo">

                        <a href="{{ route('home') }}">
                            <x-img path="images/company/{{ $company->company_logo }}" alt="{{ $company->company_name }}"
                                width="280" height="90" style="object-fit: contain; display: block;" />
                        </a>
                    </div>
                </div>
                <div class="col-6">
                    <div class="header-search">
                        <form action="{{ route('products.show', 'search-products') }}">
                            <div class="header-search-box default-search-style d-flex">
                                <input data-layout="desktop"
                                    class="searchInput default-search-style-input-box border-around border-right-none"
                                    type="search" name="query" placeholder="Search products..." required>
                                <button
                                    class="searchBtn default-search-style-input-btn position-absolute top-50 end-0 translate-middle-y"
                                    type="submit" aria-label="Search">
                                    <i class="icon-search"></i>
                                </button>

                                <button type="button"
                                    class="voiceSearchBtn position-absolute top-50 translate-middle-y"
                                    style="right: 75px;" aria-label="Voice Search">
                                    <i class="fa fa-microphone fa-lg"></i>
                                </button>
                                <div class="searchDropdown"
                                    style="display:none;position:absolute;top:100%;left:0;right:0; height:250px;overflow:auto;background:#fff; border:1px solid #ccc;z-index:1000; resize:vertical;min-height:100px;">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-3 text-end">
                    <!-- Start Header Action Icon -->
                    <ul class="header-action-icon">
                        <li>
                            <a href="{{ route('cart.index') }}">
                                <i class="icon-shopping-cart"></i>
                                <span class="header-action-icon-item-count cartCount">0</span>
                            </a>
                        </li>
                        <li class="has-user-dropdown">
                            <a href="#" aria-label="User account"><i class="icon-user"></i>
                                <span class="sr-only">User account</span>
                            </a>
                            <ul class="user-sub-menu">
                                @if (Auth::check())
                                    @if (Auth::user()->is_type == '1')
                                        <li><a href="{{ route('admin.dashboard') }}">My Dashboard</a></li>
                                    @else
                                        <li><a href="{{ route('user.dashboard') }}">My Dashboard</a></li>
                                        <li><a href="{{ route('orders.index') }}">My Orders</a></li>
                                        <li><a href="{{ route('user.profile') }}">My Profile</a></li>
                                    @endif
                                @else
                                    <li><a href="{{ route('login') }}">Log In</a></li>
                                    <li><a href="{{ route('register') }}">Register</a></li>

                                @endif
                            </ul>
                        </li>
                    </ul> <!-- End Header Action Icon -->
                </div>
            </div>
        </div>
    </div> <!-- End Header Center Area -->

    <!-- Start Bottom Area -->
    <div class="header-bottom">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- Header Main Menu -->
                    <div class="main-menu">
                        <nav>
                            <ul>
                                <li class="has-dropdown d-none">
                                    <a class="main-menu-link {{ request()->routeIs('home') ? 'active' : '' }}"
                                        href="{{ route('home') }}">Home</a>
                                </li>

                                @php
                                    $menucategories = \App\Models\Category::where('status', 1)
                                        ->whereHas('products', function ($query) {
                                            $query->where('status', 1);
                                        })
                                        ->get();
                                @endphp

                                @foreach ($menucategories as $category)
                                    <li class="has-dropdown">
                                        <a class="main-menu-link" href="{{ route('products.show', $category->slug) }}">
                                            {{ $category->name }}
                                            <i class="fa fa-angle-down" style="margin-left:5px;"></i>
                                        </a>
                                    </li>
                                @endforeach


                            </ul>
                        </nav>
                    </div> <!-- Header Main Menu Start -->
                </div>
            </div>
        </div>
    </div> <!-- End Bottom Area -->
</header> <!-- ...:::: End Header Section:::... -->



<!-- ...:::: Start Mobile Header Section:::... -->
<div class="mobile-header-section d-block d-lg-none">
    <!-- Start Mobile Header Wrapper -->
    <div class="mobile-header-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center">

                    <div class="mobile-header--left">
                        <a href="#mobile-menu-offcanvas" class="mobile-menu offcanvas-toggle"
                            aria-label="Open mobile menu">
                            <span class="mobile-menu-dash"></span>
                            <span class="mobile-menu-dash"></span>
                            <span class="mobile-menu-dash"></span>
                        </a>
                    </div>

                    <div class="mobile-header--center">
                        <a href="{{ route('home') }}" class="mobile-logo-link" aria-label="Go to homepage">
                            <x-img path="images/company/{{ $company->company_logo }}"
                                alt="{{ $company->company_name }}" class="mobile-logo-img"
                                style="width: 220px; height: 35px;" />
                        </a>
                    </div>

                    <div class="mobile-header--right">
                        <a href="{{ route('cart.index') }}" class="mobile-action-icon-link cartBtn mx-2"
                            aria-label="Go to cart">
                            <i class="icon-shopping-cart" style="color: #fff;"></i>
                            <span class="mobile-action-icon-item-count cartCount">0</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div> <!-- End Mobile Header Wrapper -->
</div> <!-- ...:::: Start Mobile Header Section:::... -->

<!-- ...:::: Start Offcanvas Mobile Menu Section:::... -->
<div id="mobile-menu-offcanvas" class="offcanvas offcanvas-leftside offcanvas-mobile-menu-section">
    <!-- Start Offcanvas Header -->
    <div class="offcanvas-header d-flex justify-content-end">
        <button class="offcanvas-close" aria-label="Close menu"><i class="fa fa-times"></i></button>
    </div> <!-- End Offcanvas Header -->
    <!-- Start Offcanvas Mobile Menu Wrapper -->
    <div class="offcanvas-mobile-menu-wrapper">
        <!-- Start Mobile Menu User Center -->
        <div class="mobile-menu-center">
            <form action="{{ route('products.show', 'search-products') }}" class="pb-3">
                <div class="header-search-box default-search-style d-flex">
                    <input data-layout="mobile"
                        class="searchInput default-search-style-input-box border-around border-right-none"
                        type="search" name="query" placeholder="Search products..." required>
                    <button
                        class="searchBtn default-search-style-input-btn position-absolute top-50 end-0 translate-middle-y"
                        type="submit" aria-label="Search">
                        <i class="icon-search"></i>
                    </button>

                    <button type="button" class="voiceSearchBtn position-absolute top-50 translate-middle-y"
                        style="right: 75px;" aria-label="Voice Search">
                        <i class="fa fa-microphone fa-lg"></i>
                    </button>
                    <div class="searchDropdown"
                        style="display:none;position:absolute;top:100%;left:0;right:0; height:250px;overflow:auto;background:#fff; border:1px solid #ccc;z-index:1000; resize:vertical;min-height:100px;">
                    </div>
                </div>
            </form>

            <!-- Start Header Action Icon -->
            <ul class="mobile-action-icon">
                <li class="mobile-action-icon-item">
                    <a href="{{ route('cart.index') }}" class="mobile-action-icon-link cartBtn" aria-label="Cart">
                        <i class="icon-shopping-cart"></i>
                        <span class="mobile-action-icon-item-count cartCount">0</span>
                    </a>
                </li>

                <li class="has-mobile-user-dropdown">
                    <a href="#" class="mobile-action-icon-link" aria-label="User"><i
                            class="icon-user"></i></a>
                    <!-- Header Top Menu's Dropdown -->
                    <ul class="mobile-user-sub-menu">
                        @if (Auth::check())
                            <li><a href="{{ route('user.dashboard') }}">My Dashboard</a></li>
                            <li><a href="{{ route('orders.index') }}">My Orders</a></li>
                            <li><a href="{{ route('user.profile') }}">My Profile</a></li>
                        @else
                            <li><a href="{{ route('login') }}">Log In</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @endif
                    </ul>
                </li>


            </ul> <!-- End Header Action Icon -->
        </div> <!-- End Mobile Menu User Center -->
        <!-- Start Mobile Menu Bottom -->
        <div class="mobile-menu-bottom">
            <!-- Start Mobile Menu Nav -->
            <div class="offcanvas-menu">
                <ul>



                    <li>
                        <a href="{{ route('home') }}"><span>Home</span></a>
                    </li>

                    @foreach ($menucategories as $category)
                        <li class="has-submenu">
                            <a href="{{ route('products.show', $category->slug) }}" class="category-link">
                                <span>{{ $category->name }}</span>
                                <i class="fa fa-angle-down"></i> {{-- keep the arrow icon for style --}}
                            </a>
                            {{-- Empty submenu for styling and JS toggle --}}
                            <ul class="mobile-sub-menu" style="display: none;"></ul>
                        </li>
                    @endforeach
                </ul>
            </div> <!-- End Mobile Menu Nav -->

            <!-- Mobile Manu Mail Address -->
            <a class="mobile-menu-email icon-text-end" href="mailto:{{ $company->email1 }}"><i
                    class="fa fa-envelope-o"> {{ $company->email1 }}</i></a>

            <!-- Mobile Manu Social Link -->
            <ul class="mobile-menu-social">
                @if ($company->facebook)
                    <li><a href="{{ $company->facebook }}" class="facebook" target="_blank"><i
                                class="fa fa-facebook"></i></a></li>
                @endif
                @if ($company->twitter)
                    <li>
                        <a href="{{ $company->twitter }}" class="svg-round ebay" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                                <path
                                    d="M606 189.5l-54.8 109.9-54.9-109.9h-37.5l10.9 20.6c-11.5-19-35.9-26-63.3-26-31.8 0-67.9 8.7-71.5 43.1h33.7c1.4-13.8 15.7-21.8 35-21.8 26 0 41 9.6 41 33v3.4c-12.7 0-28 .1-41.7 .4-42.4 .9-69.6 10-76.7 34.4 1-5.2 1.5-10.6 1.5-16.2 0-52.1-39.7-76.2-75.4-76.2-21.3 0-43 5.5-58.7 24.2v-80.6h-32.1v169.5c0 10.3-.6 22.9-1.1 33.1h31.5c.7-6.3 1.1-12.9 1.1-19.5 13.6 16.6 35.4 24.9 58.7 24.9 36.9 0 64.9-21.9 73.3-54.2-.5 2.8-.7 5.8-.7 9 0 24.1 21.1 45 60.6 45 26.6 0 45.8-5.7 61.9-25.5 0 6.6 .3 13.3 1.1 20.2h29.8c-.7-8.2-1-17.5-1-26.8v-65.6c0-9.3-1.7-17.2-4.8-23.8l61.5 116.1-28.5 54.1h35.9L640 189.5zM243.7 313.8c-29.6 0-50.2-21.5-50.2-53.8 0-32.4 20.6-53.8 50.2-53.8 29.8 0 50.2 21.4 50.2 53.8 0 32.3-20.4 53.8-50.2 53.8zm200.9-47.3c0 30-17.9 48.4-51.6 48.4-25.1 0-35-13.4-35-25.8 0-19.1 18.1-24.4 47.2-25.3 13.1-.5 27.6-.6 39.4-.6zm-411.9 1.6h128.8v-8.5c0-51.7-33.1-75.4-78.4-75.4-56.8 0-83 30.8-83 77.6 0 42.5 25.3 74 82.5 74 31.4 0 68-11.7 74.4-46.1h-33.1c-12 35.8-87.7 36.7-91.2-21.6zm95-21.4H33.3c6.9-56.6 92.1-54.7 94.4 0z" />
                            </svg>
                        </a>
                    </li>
                @endif
                @if ($company->youtube)
                    <li><a href="{{ $company->youtube }}" class="youtube" target="_blank"><i
                                class="fa fa-youtube"></i></a></li>
                @endif
                @if ($company->instagram)
                    <li><a href="{{ $company->instagram }}" class="instagram" target="_blank"><i
                                class="fa fa-instagram"></i></a></li>
                @endif
            </ul>


            <div class="mobile-menu-customer-support">
                <div class="mobile-menu-customer-support-text">
                    <span>Customer Support</span>
                    <a class="mobile-menu-customer-support-text-phone"
                        href="tel:{{ $company->phone1 }}">{{ $company->phone1 }}</a>
                </div>
            </div>
        </div> <!-- End Mobile Menu Bottom -->
    </div> <!-- End Offcanvas Mobile Menu Wrapper -->
</div> <!-- ...:::: End Offcanvas Mobile Menu Section:::... -->

<style>
    .svg-round {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 1px solid #000;
        background-color: white;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".has-submenu > .category-link").forEach(function(categoryLink) {
            categoryLink.addEventListener("click", function(e) {
                e.preventDefault();

                let parentLi = categoryLink.parentElement;
                let submenu = categoryLink.nextElementSibling;
                let isActive = parentLi.classList.contains("active");

                document.querySelectorAll(".has-submenu").forEach(function(item) {
                    if (item !== parentLi) {
                        item.classList.remove("active");
                        let otherSubmenu = item.querySelector(".mobile-sub-menu");
                        if (otherSubmenu) otherSubmenu.style.display = "none";
                    }
                });

                if (isActive) {
                    parentLi.classList.remove("active");
                    submenu.style.display = "none";
                } else {
                    parentLi.classList.add("active");
                    submenu.style.display = "block";
                }
            });
        });
    });
</script>