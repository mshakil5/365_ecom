<div id="offcanvas" class="offcanvas offcanvas-rightside offcanvas-add-cart-section">
    <div class="offcanvas-header d-flex justify-content-between align-items-center">
        <div class="offcanvas-success-message">
            <i class="fa fa-check-circle text-success"></i> 
            <span class="text-success">Added to Cart</span>
        </div>
        <button class="offcanvas-close" aria-label="Close menu"><i class="fa fa-times"></i></button>
    </div> 

    <div class="offcanvas-product-wrapper text-center p-3">
        <h4 class="offcanvas-title mb-3" id="product-name"></h4>
        <div class="offcanvas-product-content mb-3">
            <div class="product-add-to-cart-btn">
                <img id="product-image" src="" alt="Product Image" class="offcanvas-product-image img-fluid">
            </div>
        </div>

        <div class="">
            <div class="product-add-to-cart-btn">
                <a href="{{ route('cart.index') }}" class="">
                    View Cart
                </a>
            </div>
        </div>
    </div>
</div>

<div class="offcanvas-overlay"></div>