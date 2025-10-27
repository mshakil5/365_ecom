<div class="col-xl-3 col-lg-4 col-sm-6 col-12 mb-4">
    <article class="product-card border-around p-3" data-product-id="{{ $product->id }}">
        <div class="img-wrap position-relative mb-2">
            <img src="{{ $product->feature_image ?? $product->image }}" 
                 alt="{{ $product->product_name_api ?? $product->name }}" 
                 class="img-fluid" style="height:230px; object-fit:cover;">
        </div>

        <div class="product-title fw-bold mb-1">
            <a href="{{ route('product.show', $product->slug) }}" class="text-dark text-decoration-none">
                {{ $product->product_name_api ?? $product->name }}
            </a>
        </div>

        <div class="price-row d-flex gap-2 align-items-center mb-2">
            <div class="price-current fw-bold">£{{ number_format($product->price_single ?? $product->price, 2) }}</div>
        </div>

        <div class="meta-row d-flex justify-content-between align-items-center mb-2">
            <div class="actions d-flex gap-1">
                <a href="#offcanvas" class="btn btn-sm btn-primary add-to-cart"
                    data-product-id="{{ $product->id }}"
                    data-product-name="{{ $product->product_name_api ?? $product->name }}"
                    data-image="{{ $product->feature_image ?? $product->image }}">
                    Add
                </a>
                <button class="btn btn-sm btn-outline-secondary" title="Wishlist">
                    <i class="bi bi-heart"></i>
                </button>
            </div>
        </div>

        @if ($product->colors->isNotEmpty())
            <div class="swatches d-flex gap-1" aria-label="Available colors">
                @foreach($product->colors as $color)
                    <div class="swatch" style="background: {{ $color->hex ?? '#000' }}" title="{{ $color->name }}"></div>
                @endforeach
            </div>
        @endif
    </article>
</div>
