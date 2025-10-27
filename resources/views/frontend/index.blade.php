@extends('frontend.pages.master')

@section('content')

    @if (count($sliders) > 0)
        <div id="carouselExampleCaptions" class="carousel slide d-none" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach ($sliders as $index => $slider)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ asset('images/slider/' . $slider->image) }}" class="d-block w-100"
                            alt="{{ $slider->title }}" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                        <div class="carousel-caption text-white d-none d-md-block">
                            @if ($slider->sub_title)
                                <h5>{{ $slider->sub_title }}</h5>
                            @endif
                            @if ($slider->title)
                                <h2 class="text-white">{{ $slider->title }}</h2>
                            @endif
                            <p>{{ $slider->description }}</p>
                            @if ($slider->link)
                                <a href="{{ $slider->link }}" class="hero-button">Shopping Now</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="product-catagory-section mt-5">
        <div class="section-content-gap">
            <div class="container">
                <div class="row">
                    <div class="section-content">
                        <h3 class="section-title" data-aos="fade-up" data-aos-delay="50">Popular Categories</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="product-catagory-wrapper">
            <div class="container">
                <div class="row">
                    @foreach($categories as $index => $category)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <a href="{{ route('category.show', $category->slug) }}" class="product-catagory-single" data-aos="fade-up" data-aos-delay="{{ $index * 200 }}">
                                <div class="product-catagory-img">
                                    @if ($category->image)
                                        <img src="{{ asset('images/category/' . $category->image) }}" alt="{{ $category->description ?? '' }}" class="img-fluid" style="height: 100px; object-fit: cover;" loading="{{ $index < 4 ? 'eager' : 'lazy' }}">
                                    @endif
                                </div>
                                <div class="product-catagory-content">
                                    <h4 class="product-catagory-title">{{ $category->name }}</h4>
                                    <span class="product-catagory-items d-none">({{ $category->product_count ?? $category->products->count() }} Items)</span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="product-tab-section section-top-gap-100">
        <div class="section-content-gap">
            <div class="container">
                <div class="row">
                    <div class="section-content d-flex justify-content-between align-items-md-center align-items-start flex-md-row flex-column">
                        <h3 class="section-title me-3" data-aos="fade-up" data-aos-delay="0">Products</h3>
                        <ul class="tablist nav product-tab-btn row g-2" data-aos="fade-up" data-aos-delay="400">
                            @foreach($categories->take(10) as $index => $category)
                                <li class="col-4 col-md-auto" role="presentation">
                                    <a class="nav-link w-100 {{ $index === 0 ? 'active' : '' }}"
                                      data-bs-toggle="tab"
                                      href="#tab-{{ $category->id }}"
                                      role="tab"
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
                            @foreach($categories->take(10) as $index => $category)
                                <div class="tab-pane {{ $index === 0 ? 'show active' : '' }}" id="tab-{{ $category->id }}">
                                    <div class="product-default-slider product-default-slider-4grids-1row">
                                        @foreach($category->products as $product)
                                            @include('frontend.partials.single_product', ['product' => $product])
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

    <div class="shop-section mt-5">
        <div class="container">
            <h3 class="section-title mb-4">Latest Products</h3>

            <div class="row">
                @foreach ($latestProducts as $latestproduct)
                    @include('frontend.partials.single_product', ['product' => $latestproduct])
                @endforeach
            </div>

        </div>
    </div>


    <div class="shop-section mt-5">
        <div class="container">
            <h3 class="section-title mb-4">Trending Products</h3>

            <div class="row">
                @foreach ($trendingProducts as $trendingProduct)
                    @include('frontend.partials.single_product', ['product' => $trendingProduct])
                @endforeach
            </div>

        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --card-radius: 14px;
            --muted: #6c757d;
            --accent: #0d6efd;
            --shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
        }

        body {
            font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            background: #f6f8fb;
            color: #111827;
        }

        .page-header {
            padding: 36px 0 18px;
        }

        .shop-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 18px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
        }

        @media (max-width: 1200px) {
            .product-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 992px) {
            .product-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 720px) {
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 420px) {
            .product-grid {
                grid-template-columns: 1fr;
            }
        }

        .product-card {
            background: #fff;
            border-radius: var(--card-radius);
            padding: 12px;
            box-shadow: var(--shadow);
            transition: transform .18s ease, box-shadow .18s ease;
            display: flex;
            flex-direction: column;
            min-height: 320px;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        }

        .img-wrap {
            width: 100%;
            aspect-ratio: 1/1;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(180deg, #fafbff 0%, #f5f7fb 100%);
            margin-bottom: 10px;
            position: relative;
        }

        .img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .badge-top {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 6px 8px;
            font-size: 12px;
            border-radius: 8px;
            background: rgba(13, 110, 253, 0.08);
            color: var(--accent);
            font-weight: 600;
            backdrop-filter: blur(2px);
        }

        .product-title {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 6px;
            color: #0f1724;
        }

        .price-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .price-current {
            font-weight: 700;
            font-size: 1.05rem;
        }

        .price-old {
            color: var(--muted);
            text-decoration: line-through;
            font-size: 0.9rem;
        }

        .swatches {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-top: auto;
        }

        .swatch {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 1px 0 rgba(0, 0, 0, 0.06);
            cursor: pointer;
            transition: transform .12s;
            display: inline-block;
        }

        .swatch:hover {
            transform: scale(1.12);
        }

        .meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-top: 10px;
        }

        .actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-ghost {
            border: 1px solid #e6e9ef;
            background: transparent;
            padding: 6px 9px;
            border-radius: 8px;
            font-size: 0.92rem;
        }

        .rating {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #f59e0b;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .topbar-select {
            min-width: 220px;
        }

        .muted {
            color: var(--muted);
            font-size: 0.9rem;
        }

        .flex-gap {
            display: flex;
            gap: 10px;
            align-items: center;
        }
    </style>

    <div class="shop-section mt-5 d-none">
        <div class="container">
            <h3 class="section-title mb-4">Products</h3>

            <div class="row" id="productGrid"></div>

            <div class="text-center my-3">
                <button id="see-more-btn" class="btn btn-success" data-page="1">See More</button>
            </div>
        </div>
    </div>

@endsection

@section('script')
    {{-- <script>
  $(document).ready(function(){
      const colors = ['#0d6efd', '#000000', '#f97316', '#198754', '#6f42c1', '#fd7e14'];

      function loadProducts(){
          $.ajax({
              url: "{{ route('products.latest') }}",
              success: function(res){
                  res.products.forEach(product => {
                      let swatchesHtml = '';
                      let shuffledColors = [...colors].sort(() => 0.5 - Math.random()).slice(0, 3);
                      shuffledColors.forEach(color => {
                          swatchesHtml += `<div class="swatch" style="background:${color}" title="${color}"></div>`;
                      });

                      let html = `
                      <div class="col-xl-3 col-lg-4 col-sm-6 col-12 mb-4">
                          <article class="product-card border-around p-3" data-product-id="${product.id}">
                              <div class="img-wrap position-relative mb-2">
                                  ${product.is_new ? '<span class="badge-top position-absolute top-0 start-0 bg-success text-white px-2 py-1">New</span>' : ''}
                                  <img src="${product.image}" alt="${product.product_name_api}" class="img-fluid" style="height:230px; object-fit:cover;">
                              </div>
                              <div class="product-title fw-bold mb-1">
                                  <a href="/product/${product.id}" class="text-dark text-decoration-none">${product.product_name_api}</a>
                              </div>
                              <div class="price-row d-flex gap-2 align-items-center mb-2">
                                  <div class="price-current fw-bold">£${parseFloat(product.price_single).toFixed(2)}</div>
                                  ${product.del_price && product.del_price > product.price_single ? `<div class="price-old text-muted text-decoration-line-through">£${parseFloat(product.del_price).toFixed(2)}</div>` : ''}
                              </div>
                              <div class="muted mb-2">${product.short_desc || ''}</div>
                              <div class="meta-row d-flex justify-content-between align-items-center mb-2">
                                  <div class="rating text-warning"><i class="bi bi-star-fill"></i> ${product.rating || 4.5}</div>
                                  <div class="actions d-flex gap-1">
                                      <button class="btn btn-sm btn-primary">Add</button>
                                      <button class="btn btn-sm btn-outline-secondary" title="Wishlist"><i class="bi bi-heart"></i></button>
                                  </div>
                              </div>
                              <div class="swatches d-flex gap-1" aria-label="Available colors">
                                  ${swatchesHtml}
                              </div>
                          </article>
                      </div>`;
                      $('#productGrid').append(html);
                  });
              }
          });
      }

      loadProducts();

      $('#see-more-btn').click(function(){
          loadProducts();
      });
  });
</script> --}}
@endsection
