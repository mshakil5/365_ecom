<div class="app-menu navbar-menu">
    <div class="navbar-brand-box">
        <a href="{{ route('dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img  src="{{ asset('images/company/' . $company->company_logo) }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img  src="{{ asset('images/company/' . $company->company_logo) }}" alt="" height="40">
            </span>
        </a>
        <a href="{{ route('dashboard') }}" class="logo logo-light">
            <span class="logo-sm">
                <img  src="{{ asset('images/company/' . $company->company_logo) }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img  src="{{ asset('images/company/' . $company->company_logo) }}" alt="" height="40">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">

                <li class="nav-item d-none">
                    <a class="nav-link menu-link" href="#sidebarMultilevel" data-bs-toggle="collapse"
                        role="button" aria-expanded="false" aria-controls="sidebarMultilevel">
                        <i class="ri-share-line"></i> <span data-key="t-multi-level">Multi Level</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarMultilevel">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="#" class="nav-link" data-key="t-level-1.1"> Level 1.1 </a>
                            </li>
                            <li class="nav-item">
                                <a href="#sidebarAccount" class="nav-link" data-bs-toggle="collapse"
                                    role="button" aria-expanded="false" aria-controls="sidebarAccount"
                                    data-key="t-level-1.2"> Level
                                    1.2
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarAccount">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="#" class="nav-link" data-key="t-level-2.1">
                                                Level 2.1 </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#sidebarCrm" class="nav-link"
                                                data-bs-toggle="collapse" role="button"
                                                aria-expanded="false" aria-controls="sidebarCrm"
                                                data-key="t-level-2.2"> Level 2.2
                                            </a>
                                            <div class="collapse menu-dropdown" id="sidebarCrm">
                                                <ul class="nav nav-sm flex-column">
                                                    <li class="nav-item">
                                                        <a href="#" class="nav-link"
                                                            data-key="t-level-3.1"> Level 3.1
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#" class="nav-link"
                                                            data-key="t-level-3.2"> Level 3.2
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                        <i class="ri-dashboard-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('allApiProducts') || Route::is('api_products.index') || Route::is('create.product') || Route::is('allcategory') || Route::is('subcategories.index') || Route::is('subsubcategories.index') || Route::is('brands.index') || Route::is('productmodel.index') || Route::is('groups.index') || Route::is('units.index') || Route::is('tags.index') || Route::is('sizes.index') || Route::is('colors.index') || Route::is('types.index') || Route::is('warranties.index') ? 'active' : '' }}"
                      href="#sidebarAllProducts" data-bs-toggle="collapse" role="button"
                      aria-expanded="true" aria-controls="sidebarAllProducts">
                        <i class="ri-shopping-bag-3-line"></i> <span>Product Management</span>
                    </a>

                    <div class="collapse menu-dropdown {{ Route::is('allApiProducts') || Route::is('api_products.index') || Route::is('create.product') || Route::is('allcategory') || Route::is('subcategories.index') || Route::is('subsubcategories.index') || Route::is('brands.index') || Route::is('productmodel.index') || Route::is('groups.index') || Route::is('units.index') || Route::is('tags.index') || Route::is('sizes.index') || Route::is('colors.index') || Route::is('types.index') || Route::is('warranties.index') ? 'show' : '' }}"
                        id="sidebarAllProducts">
                        <ul class="nav nav-sm flex-column">

                            <li class="nav-item">
                                <a href="{{ route('allApiProducts') }}"
                                  class="nav-link {{ Route::is('allApiProducts') ? 'active' : '' }}">
                                   Products (Api)
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('api_products.index') }}"
                                  class="nav-link {{ Route::is('api_products.index') ? 'active' : '' }}">
                                   Api Sources
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('create.product') }}"
                                  class="nav-link {{ Route::is('create.product') ? 'active' : '' }}">
                                   Create Product
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('allcategory') }}"
                                  class="nav-link {{ Route::is('allcategory') ? 'active' : '' }}">
                                    Category
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('subcategories.index') }}"
                                  class="nav-link {{ Route::is('subcategories.index') ? 'active' : '' }}">
                                    Sub Category
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('subsubcategories.index') }}"
                                  class="nav-link {{ Route::is('subsubcategories.index') ? 'active' : '' }}">
                                    Sub Sub Category
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('brands.index') }}"
                                  class="nav-link {{ Route::is('brands.index') ? 'active' : '' }}">
                                    Brand
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('productmodel.index') }}"
                                  class="nav-link {{ Route::is('productmodel.index') ? 'active' : '' }}">
                                    Model
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('groups.index') }}"
                                  class="nav-link {{ Route::is('groups.index') ? 'active' : '' }}">
                                    Group
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('units.index') }}"
                                  class="nav-link {{ Route::is('units.index') ? 'active' : '' }}">
                                    Unit
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('tags.index') }}"
                                  class="nav-link {{ Route::is('tags.index') ? 'active' : '' }}">
                                    Tag
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('sizes.index') }}"
                                  class="nav-link {{ Route::is('sizes.index') ? 'active' : '' }}">
                                    Size
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('colors.index') }}"
                                  class="nav-link {{ Route::is('colors.index') ? 'active' : '' }}">
                                    Color
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('types.index') }}"
                                  class="nav-link {{ Route::is('types.index') ? 'active' : '' }}">
                                    Type
                                </a>
                            </li>

                            <li class="nav-item d-none">
                                <a href="{{ route('warranties.index') }}"
                                  class="nav-link {{ Route::is('warranties.index') ? 'active' : '' }}">
                                    Warranty
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>

                <!-- Contact Messages -->
                <li class="nav-item">
                    <a href="{{ route('contacts.index') }}" class="nav-link {{ Route::is('contacts.index') ? 'active' : '' }}">
                        <i class="ri-mail-open-line"></i>
                        <span>Contact Messages</span>
                    </a>
                </li>

                <!-- Customers -->
                <li class="nav-item">
                    <a href="{{ route('user.index') }}" class="nav-link {{ Route::is('user.index') ? 'active' : '' }}">
                        <i class="ri-user-3-line"></i>
                        <span>Customers</span>
                    </a>
                </li>

                <!-- Settings Dropdown -->
                @php
                    $settingsActive = Route::is(
                        'admin.companyDetails',
                        'admin.company.seo-meta',
                        'admin.aboutUs',
                        'admin.privacy-policy',
                        'admin.terms-and-conditions',
                        'faq.index',
                        'admin.mail-body',
                        'contactemails.index',
                        'sections.index',
                        'allslider',
                        'admin.home-footer',
                        'admin.copyright'
                    );
                @endphp

                <li class="nav-item">
                    <a class="nav-link menu-link {{ $settingsActive ? 'active' : '' }}" 
                      href="#sidebarSettings" data-bs-toggle="collapse" role="button" 
                      aria-expanded="{{ $settingsActive ? 'true' : 'false' }}" 
                      aria-controls="sidebarSettings">
                        <i class="ri-settings-3-line"></i> <span>Settings</span>
                    </a>

                    <div class="collapse menu-dropdown {{ $settingsActive ? 'show' : '' }}" id="sidebarSettings">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.companyDetails') }}" 
                                  class="nav-link {{ Route::is('admin.companyDetails') ? 'active' : '' }}">Company Details</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.company.seo-meta') }}" 
                                  class="nav-link {{ Route::is('admin.company.seo-meta') ? 'active' : '' }}">SEO</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.aboutUs') }}" 
                                  class="nav-link {{ Route::is('admin.aboutUs') ? 'active' : '' }}">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.privacy-policy') }}" 
                                  class="nav-link {{ Route::is('admin.privacy-policy') ? 'active' : '' }}">Privacy Policy</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.terms-and-conditions') }}" 
                                  class="nav-link {{ Route::is('admin.terms-and-conditions') ? 'active' : '' }}">Terms & Conditions</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.mail-body') }}" 
                                  class="nav-link {{ Route::is('admin.mail-body') ? 'active' : '' }}">Mail Body</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.home-footer') }}" 
                                  class="nav-link {{ Route::is('admin.home-footer') ? 'active' : '' }}">Home Footer</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.copyright') }}" 
                                  class="nav-link {{ Route::is('admin.copyright') ? 'active' : '' }}">Copyright</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('contactemails.index') }}" 
                                  class="nav-link {{ Route::is('contactemails.index') ? 'active' : '' }}">Contact Email</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('sections.index') }}" 
                                  class="nav-link {{ Route::is('sections.index') ? 'active' : '' }}">Section Settings</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('faq.index') }}" 
                                  class="nav-link {{ Route::is('faq.index') ? 'active' : '' }}">FAQ</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('allslider') }}" 
                                  class="nav-link {{ Route::is('allslider') ? 'active' : '' }}">Sliders
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</div>