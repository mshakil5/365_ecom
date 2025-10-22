@extends('frontend.pages.master')

@section('content')

<style>

@media (max-width: 768px) {
    .zoom-image-hover {
        pointer-events: none;
    }
}


    .progress-circle {
        width: 113px;
        height: 113px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
    }
    .circle-1 { background-color: #d1d1d1; }
    .circle-2 { background-color: #85c041; }
    .circle-3 { background-color: #008000; }

    .modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: none;
        z-index: 9999;
        overflow-y: auto;
        padding: 40px 0;
    }

    .modal-content {
        background: #fff;
        border-radius: 5px;
        animation: fadeIn 0.4s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }

    .tag-item {
      display: inline-block;
      background-color: #f1f1f1;
      color: #333;
      border-radius: 4px;
      padding: 3px 8px;
      font-size: 14px;
      text-decoration: none;
  }
</style>

<div class="breadcrumb-section">
    <div class="breadcrumb-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center flex-md-row flex-column">
                    
                    <!-- Static Page Title -->
                    <h3 class="breadcrumb-title">Product Details</h3>
                    
                    <div class="breadcrumb-nav">
                        <nav aria-label="breadcrumb">
                            <ul>
                                <li><a href="#">Home</a></li>
                                <li aria-current="page">{{ $product->product_name_api }}</li>
                            </ul>
                        </nav>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<div class="product-details-section mt-2">
    <div class="container">
        <div class="row">

            <div class="col-md-6">
                <div class="product-details-gallery-area d-flex align-items-center flex-row-reverse" data-aos="fade-up"  data-aos-delay="0">
                    <div class="product-large-image product-large-image-vertical ml-15">


                        <div class="product-image-large-single zoom-image-hover">
                            <img src="{{ $product->image }}" alt="Feature Image">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="product-details-content-area" data-aos="fade-up" data-aos-delay="200">
                    <div class="product-details-text">
                        <h4 class="title">{{ $product->product_name_api }}</h4>
                        <div class="price">     
                            @if($product->del_price > $product->price_single)
                                <del>£{{ number_format($product->del_price, 2) }}</del>
                            @endif
                            £{{ number_format($product->price_single, 2) }} <span class="text-black">excl VAT</span>
                            <input type="hidden" value="{{ $product->price_single }}" id="product_price">
                        </div>
                    </div>

                    <div class="product-details-variable">
                    </div>

                    <div class="product-details-variable">

                        <div class="product-block product-block--sales-point">
                            <ul class="sales-points">
                            <li class="sales-point">
                                <span class="icon-and-text inventory--low">
                                <span class="icon icon--inventory"></span>
                                <span class="d-none" data-product-inventory="" data-threshold="2" data-enabled="false">{{ number_format($product->quantity_api, 0) }} items left</span>
                                </span>
                            </li>
                            </ul>
                        </div>


                    <div class="d-flex align-items-center">
                        <div class="variable-single-item">
                            <span>Quantity</span>
                            <div class="product-variable-quantity">
                                <input min="1" max="{{ $product->quantity_api }}" value="1" type="number" class="quantity-input">
                            </div>
                        </div>
                    </div>

                    <div class="product-details-meta mb-20">
                        <ul>
                            <li><a href="#"><i class="icon-heart"></i>Add to wishlist</a></li>
                        </ul>
                    </div>
                    

                    <div class="product-details-social">
                        <ul>
                            <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::fullUrl()) }}"  class="facebook" target="_blank">
                                <i class="fa fa-facebook"></i>
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="product-details-content-tab-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="product-details-content-tab-wrapper" data-aos="fade-up"  data-aos-delay="0">
                    <ul class="nav tablist product-details-content-tab-btn d-flex justify-content-center">
                        <li><a class="nav-link active" data-bs-toggle="tab" href="#description">
                                <h5>Description</h5>
                            </a></li>
                        <li><a class="nav-link" data-bs-toggle="tab" href="#review">
                            </a></li>
                    </ul>

                    <div class="product-details-content-tab">
                        <div class="tab-content">
                            <div class="tab-pane active show" id="description">
                                <div class="single-tab-content-item">
                                    
                                    {!! $product->full_description !!}

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

@endsection