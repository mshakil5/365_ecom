@extends('frontend.pages.master')

@section('content')
    <div class="product-catagory-section mt-5">
        <div class="section-content-gap">
            <div class="container">
                <div class="row">
                    <div class="section-content">
                        <h3 class="section-title" data-aos="fade-up" data-aos-delay="50">{{ $heroTitle->short_title }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hero-area mt-5">
        <div class="container">
            <div class="row g-4 align-items-stretch">

                <div class="col-lg-6 d-flex">
                    <a href="#" class="w-100 text-decoration-none text-dark">
                        <div class="hero-card hero-left w-100" data-aos="fade-up" data-aos-delay="50">
                            <x-img path="images/meta_image/{{ $heroSection1->meta_image ?? '' }}" alt="" />
                            <h4>{{ $heroSection1->short_title }}</h4>
                            <p>{{ $heroSection1->long_title ?? '' }}</p>
                        </div>
                    </a>
                </div>

                <div class="col-lg-6 d-flex flex-column justify-content-between">
                    <a href="#" class="text-decoration-none text-dark mb-4">
                        <div class="hero-card hero-right" data-aos="fade-up" data-aos-delay="100">
                            <x-img path="images/meta_image/{{ $heroSection2->meta_image ?? '' }}" alt="" />
                            <h4>{{ $heroSection2->short_title }}</h4>
                            <p>{{ $heroSection2->long_title ?? '' }}</p>
                        </div>
                    </a>

                    <a href="#" class="text-decoration-none text-dark">
                        <div class="hero-card hero-right" data-aos="fade-up" data-aos-delay="150">
                            <x-img path="images/meta_image/{{ $heroSection3->meta_image ?? '' }}" alt="" />
                            <h4>{{ $heroSection3->short_title }}</h4>
                            <p>{{ $heroSection3->long_title ?? '' }}</p>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </div>

    @if ($categories->count() > 0)
        <div class="product-catagory-section mt-5">
            <div class="section-content-gap">
                <div class="container">
                    <div class="row">
                        <div class="section-content">
                            <h3 class="section-title" data-aos="fade-up" data-aos-delay="50">Bestselling Categories</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="product-catagory-wrapper">
                <div class="container">
                    <div class="row">
                        @foreach ($categories as $index => $category)
                            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                <a href="{{ route('products.show', $category->slug) }}" class="product-catagory-single"
                                    data-aos="fade-up" data-aos-delay="{{ $index * 200 }}">
                                    <div class="product-catagory-img">
                                        <x-img :path="'images/category/' . ($category->image ?? '')" :alt="$category->description ?? ''" class="img-fluid"
                                            style="height: 100px; object-fit: cover;" :loading="$index < 4 ? 'eager' : 'lazy'" />
                                    </div>
                                    <div class="product-catagory-content">
                                        <h4 class="product-catagory-title">{{ $category->name }}</h4>
                                        <span
                                            class="product-catagory-items d-none">({{ $category->product_count ?? $category->products->count() }}
                                            Items)</span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($sectors->count() > 0)
        <div class="shop-section mt-5">
            <div class="container">
                <div class="section-content d-flex justify-content-between align-items-center flex-wrap mb-3">
                    <h3 class="section-title" data-aos="fade-up" data-aos-delay="0">Shop By Sector</h3>
                </div>

                <div class="sector-slider d-flex flex-row flex-nowrap overflow-auto" data-aos="fade-up" data-aos-delay="50">
                    @foreach ($sectors as $sector)
                        <div class="sector-card me-3" style="min-width: 250px;">
                            <a href="#" class="text-decoration-none text-dark">
                                <div class="sector-card-inner">
                                    <x-img :path="'images/sector/' . ($sector->image ?? '')" :alt="$sector->name ?? ''" class="img-fluid"
                                        style="height: 100px; object-fit: cover;" />
                                    <h5 class="mt-2 text-center">{{ $sector->name }}</h5>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @include('frontend.partials.brands')

    @if ($categories->count() > 0)
        <div class="product-tab-section section-top-gap-100">
            <div class="section-content-gap">
                <div class="container">
                    <div class="row">
                        <div
                            class="section-content d-flex justify-content-between align-items-md-center align-items-start flex-md-row flex-column">
                            <h3 class="section-title me-3" data-aos="fade-up" data-aos-delay="0">Products</h3>
                            <ul class="tablist nav product-tab-btn row g-2" data-aos="fade-up" data-aos-delay="400">
                                @foreach ($categories->take(10) as $index => $category)
                                    <li class="col-4 col-md-auto" role="presentation">
                                        <a class="nav-link w-100 {{ $index === 0 ? 'active' : '' }}" data-bs-toggle="tab"
                                            href="#tab-{{ $category->id }}" role="tab"
                                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                            {{ $category->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="product-tab-wrapper" data-aos="fade-up" data-aos-delay="50">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="tab-content tab-animate-zoom">
                                @foreach ($categories->take(10) as $index => $category)
                                    <div class="tab-pane {{ $index === 0 ? 'show active' : '' }}"
                                        id="tab-{{ $category->id }}">
                                        <div class="product-default-slider product-default-slider-4grids-1row">
                                            @foreach ($category->products as $product)
                                                @include('frontend.partials.single_product', [
                                                    'product' => $product,
                                                ])
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($trendingProducts->count() > 0)
        <div class="shop-section mt-5">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="section-title mb-0">Trending Products</h3>
                    <a href="{{ route('products.show', 'trending') }}" class="show">View All</a>
                </div>

                <div class="row">
                    @foreach ($trendingProducts as $trendingProduct)
                        @include('frontend.partials.single_product', ['product' => $trendingProduct])
                    @endforeach
                </div>

            </div>
        </div>
    @endif

    @if ($bestSellingProducts->count() > 0)
        <div class="shop-section mt-5">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="section-title mb-0">Best Selling Products</h3>
                    <a href="{{ route('products.show', 'best-selling') }}" class="show">View All</a>
                </div>

                <div class="row">
                    @foreach ($bestSellingProducts as $bestSellingProduct)
                        @include('frontend.partials.single_product', ['product' => $bestSellingProduct])
                    @endforeach
                </div>

            </div>
        </div>
    @endif
@endsection