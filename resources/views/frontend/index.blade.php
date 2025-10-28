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
    </div>

    <div class="hero-area mt-5">
      <div class="container">
        <div class="row g-4 align-items-stretch">

          <div class="col-lg-6 d-flex">
            <a href="#" class="w-100 text-decoration-none text-dark">
              <div class="hero-card hero-left w-100" data-aos="fade-up" data-aos-delay="50">
                <x-img 
                      path="images/meta_image/{{ $heroSection1->meta_image ?? '' }}" 
                      alt="" 
                  />
                <h4>{{ $heroSection1->short_title }}</h4>
                <p>{{ $heroSection1->long_title ?? '' }}</p>
              </div>
            </a>
          </div>

          <div class="col-lg-6 d-flex flex-column justify-content-between">
            <a href="#" class="text-decoration-none text-dark mb-4">
              <div class="hero-card hero-right" data-aos="fade-up" data-aos-delay="100">
                <x-img 
                    path="images/meta_image/{{ $heroSection2->meta_image ?? '' }}" 
                    alt="" 
                />
                <h4>{{ $heroSection2->short_title }}</h4>
                <p>{{ $heroSection2->long_title ?? '' }}</p>
              </div>
            </a>

            <a href="#" class="text-decoration-none text-dark">
              <div class="hero-card hero-right" data-aos="fade-up" data-aos-delay="150">
                <x-img 
                    path="images/meta_image/{{ $heroSection3->meta_image ?? '' }}" 
                    alt="" 
                />
                <h4>{{ $heroSection3->short_title }}</h4>
                <p>{{ $heroSection3->long_title ?? '' }}</p>
              </div>
            </a>
          </div>

        </div>
      </div>
    </div>

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
                    @foreach($categories as $index => $category)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <a href="{{ route('category.show', $category->slug) }}" class="product-catagory-single" data-aos="fade-up" data-aos-delay="{{ $index * 200 }}">
                                <div class="product-catagory-img">
                                <x-img 
                                    :path="'images/category/' . ($category->image ?? '')"
                                    :alt="$category->description ?? ''" 
                                    class="img-fluid" 
                                    style="height: 100px; object-fit: cover;" 
                                    :loading="$index < 4 ? 'eager' : 'lazy'" 
                                />
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

    <div class="shop-section mt-5">
        <div class="container">
            <div class="section-content d-flex justify-content-between align-items-center flex-wrap mb-3">
                <h3 class="section-title" data-aos="fade-up" data-aos-delay="0">Shop By Sector</h3>
            </div>

            <div class="sector-slider d-flex flex-row flex-nowrap overflow-auto" data-aos="fade-up" data-aos-delay="50">
                @foreach($sectors as $sector)
                    <div class="sector-card me-3" style="min-width: 250px;">
                        <a href="#" class="text-decoration-none text-dark">
                            <div class="sector-card-inner">
                                <img src="{{ asset('images/sector/' . ($sector->image ?? '')) }}" alt="{{ $sector->name }}" class="img-fluid">
                                <h5 class="mt-2 text-center">{{ $sector->name }}</h5>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @include('frontend.partials.brands')

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
