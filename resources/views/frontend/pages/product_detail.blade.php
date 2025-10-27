@extends('frontend.pages.master')

@section('content')
    <!-- Product Details Section -->
    <div class="product-details-section mt-2">
        <div class="container">
            <div class="row">
                <!-- Product Gallery -->
                <div class="col-md-6">
                    <div class="product-details-gallery-area d-flex align-items-center flex-row-reverse">
                        {{-- Large images --}}
                        <div class="product-large-image product-large-image-vertical ml-15">
                            @foreach ($product->images as $image)
                                <div class="product-image-large-single zoom-image-hover">
                                    <img src="{{ $image->image_path }}" alt="{{ $product->name }}"
                                        class="{{ $image->is_primary ? 'main-image' : '' }}">
                                </div>
                            @endforeach
                            @if ($product->feature_image && !$product->images->contains('image_path', $product->feature_image))
                                <div class="product-image-large-single zoom-image-hover">
                                    <img src="{{ $product->feature_image }}" alt="{{ $product->name }}" class="main-image">
                                </div>
                            @endif
                        </div>

                        {{-- Thumbnails --}}
                        <div class="product-image-thumb product-image-thumb-vertical pos-relative">
                            @foreach ($product->images as $image)
                                <div class="product-image-thumb-single">
                                    <img class="img-fluid" src="{{ $image->image_path }}" alt="{{ $product->name }}">
                                </div>
                            @endforeach
                            @if ($product->feature_image && !$product->images->contains('image_path', $product->feature_image))
                                <div class="product-image-thumb-single">
                                    <img class="img-fluid" src="{{ $product->feature_image }}" alt="{{ $product->name }}">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>


                <!-- Product Details -->
                <div class="col-md-6">
                    <div class="product-details-content-area">
                        <div class="product-details-text">
                            <h4 class="title">{{ $product->nam }}</h4>
                            <div class="price">
                                {{-- <del>$49.99</del> --}}
                                £{{ number_format($product->price, 2) }} <span class="text-black">excl VAT</span>
                            </div>
                        </div>

                        <div class="product-details-variable">
                            <h4 class="title">Available Options</h4>

                            @php
                                $colors = $product->variants->pluck('color')->filter()->unique('id');
                            @endphp

                            @if ($colors->count() > 0)
                                <div class="variable-single-item">
                                    <span>Color</span>
                                    <div class="product-variable-color">
                                        @foreach ($colors as $color)
                                            <label>
                                                <input type="radio" name="color" value="{{ $color->id }}"
                                                    {{ $loop->first ? 'checked' : '' }}>
                                                <span class="product-color" title="{{ $color->name ?? 'Color' }}"
                                                    style="background-color: {{ $color->hex ?? '#ccc' }};">
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif


                            @php
                                $sizes = $product->variants->pluck('size')->filter()->unique('id');
                            @endphp

                            @if ($sizes->count() > 0)
                                <div class="variable-single-item mb-3">
                                    <span class="d-block mb-2 fw-semibold">Size:</span>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($sizes as $size)
                                            <label class="size-option">
                                                <input type="radio" name="size" value="{{ $size->id }}"
                                                    {{ $loop->first ? 'checked' : '' }}>
                                                <span>{{ $size->name ?? 'N/A' }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif


                            <div class="d-flex align-items-center mt-3">
                                <div class="variable-single-item me-3">
                                    <span>Quantity</span>
                                    <div class="product-variable-quantity">
                                        <input min="1" value="1" type="number" class="quantity-input">
                                    </div>
                                </div>

                                <div class="product-add-to-cart-btn">
                                    <a href="#" class="offcanvas-toggle">Add To Cart</a>
                                </div>
                            </div>
                        </div>
                        <div class="product-details-text pt-3">

                            <div class="pricing-table mt-4">
                                <div class="tab">
                                    <h3 class="tab-link tab-link-first active" data-tab="Blank">Blank Pricing</h3>
                                    <h3 class="tab-link" data-tab="Print">Print</h3>
                                    <h3 class="tab-link" data-tab="Embroidery">Embroidery</h3>
                                    <h3 class="tab-link tab-link-last" data-tab="HighStitch">High Stitch Count</h3>
                                </div>

                                <div class="tab-panes">
                                    <!-- Blank Tab -->
                                    <div id="Blank" class="tab-content active">
                                        <table class="table table-striped">
                                            <tbody>
                                                <tr>
                                                    <th>Qty</th>
                                                    <td>1-7</td>
                                                    <td>8-14</td>
                                                    <td>15-39</td>
                                                    <td>40-99</td>
                                                    <td>100-249</td>
                                                    <td>250-499</td>
                                                    <td>500+</td>
                                                </tr>
                                                <tr>
                                                    <th>Price</th>
                                                    <td>$14.95</td>
                                                    <td>$13.46</td>
                                                    <td>$12.68</td>
                                                    <td>$12.11</td>
                                                    <td>$10.12</td>
                                                    <td>$8.50</td>
                                                    <td><a href="#">Contact Us</a></td>
                                                </tr>
                                                <tr>
                                                    <th>Discount</th>
                                                    <td></td>
                                                    <td>-10%</td>
                                                    <td>-15%</td>
                                                    <td>-19%</td>
                                                    <td>-32%</td>
                                                    <td>-43%</td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Print Tab -->
                                    <div id="Print" class="tab-content">
                                        <table class="table table-striped">
                                            <tbody>
                                                <tr>
                                                    <th>Qty</th>
                                                    <td>1</td>
                                                    <td>2-9</td>
                                                    <td>10-34</td>
                                                    <td>35-99</td>
                                                    <td>100-249</td>
                                                    <td>250-499</td>
                                                    <td>500+</td>
                                                </tr>
                                                <tr>
                                                    <th>Price</th>
                                                    <td>$7.99</td>
                                                    <td>$5.99</td>
                                                    <td>$4.50</td>
                                                    <td>$3.25</td>
                                                    <td>$2.75</td>
                                                    <td>$2.25</td>
                                                    <td><a href="#">Contact Us</a></td>
                                                </tr>
                                                <tr>
                                                    <th>Discount</th>
                                                    <td></td>
                                                    <td>-25%</td>
                                                    <td>-44%</td>
                                                    <td>-59%</td>
                                                    <td>-66%</td>
                                                    <td>-72%</td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Embroidery Tab -->
                                    <div id="Embroidery" class="tab-content">
                                        <table class="table table-striped">
                                            <tbody>
                                                <tr>
                                                    <th>Qty</th>
                                                    <td>1</td>
                                                    <td>2-9</td>
                                                    <td>10-39</td>
                                                    <td>40-99</td>
                                                    <td>100-249</td>
                                                    <td>250-499</td>
                                                    <td>500+</td>
                                                </tr>
                                                <tr>
                                                    <th>Price</th>
                                                    <td>$8.49</td>
                                                    <td>$6.25</td>
                                                    <td>$4.99</td>
                                                    <td>$3.75</td>
                                                    <td>$3.25</td>
                                                    <td>$2.50</td>
                                                    <td><a href="#">Contact Us</a></td>
                                                </tr>
                                                <tr>
                                                    <th>Discount</th>
                                                    <td></td>
                                                    <td>-26%</td>
                                                    <td>-41%</td>
                                                    <td>-56%</td>
                                                    <td>-62%</td>
                                                    <td>-71%</td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- High Stitch Tab -->
                                    <div id="HighStitch" class="tab-content">
                                        <table class="table table-striped">
                                            <tbody>
                                                <tr>
                                                    <th>Qty</th>
                                                    <td>1</td>
                                                    <td>2-9</td>
                                                    <td>10-39</td>
                                                    <td>40-99</td>
                                                    <td>100-249</td>
                                                    <td>250-499</td>
                                                    <td>500+</td>
                                                </tr>
                                                <tr>
                                                    <th>Price</th>
                                                    <td>$16.00</td>
                                                    <td>$11.50</td>
                                                    <td>$9.00</td>
                                                    <td>$6.50</td>
                                                    <td>$5.50</td>
                                                    <td>$4.50</td>
                                                    <td><a href="#">Contact Us</a></td>
                                                </tr>
                                                <tr>
                                                    <th>Discount</th>
                                                    <td></td>
                                                    <td>-28%</td>
                                                    <td>-44%</td>
                                                    <td>-59%</td>
                                                    <td>-66%</td>
                                                    <td>-72%</td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <p>{{ $product->full_description ?? ($product->short_description ?? 'No description available.') }}
                            </p>

                            @if ($product->composition)
                                <div
                                    style="display:flex;align-items:center;border:1px solid #ccc;padding:10px;margin-bottom:10px;border-radius:5px;">
                                    <i class="fa fa-tshirt" style="font-size:24px;color:#007bff;margin-right:10px;"></i>
                                    <span style="font-size:16px;">Composition: {{ $product->composition }}</span>
                                </div>
                            @endif

                            @if ($product->gsm)
                                <div
                                    style="display:flex;align-items:center;border:1px solid #ccc;padding:10px;margin-bottom:10px;border-radius:5px;">
                                    <i class="fa fa-weight" style="font-size:24px;color:#ff9800;margin-right:10px;"></i>
                                    <span style="font-size:16px;">GSM: {{ $product->gsm }}</span>
                                </div>
                            @endif

                            @if ($product->country_of_origin)
                                <div
                                    style="display:flex;align-items:center;border:1px solid #ccc;padding:10px;margin-bottom:10px;border-radius:5px;">
                                    <i class="fa fa-globe" style="font-size:24px;color:green;margin-right:10px;"></i>
                                    <span style="font-size:16px;">Country of Origin:
                                        {{ $product->country_of_origin }}</span>
                                </div>
                            @endif

                            @if ($product->wash_degrees)
                                <div
                                    style="display:flex;align-items:center;border:1px solid #ccc;padding:10px;border-radius:5px;">
                                    <i class="fa fa-water" style="font-size:24px;color:#17a2b8;margin-right:10px;"></i>
                                    <span style="font-size:16px;">Wash Degrees: {{ $product->wash_degrees }}</span>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .tab {
            display: flex;
            border-bottom: 2px solid #ddd;
            margin-bottom: 15px;
        }

        .tab-link {
            padding: 10px 20px;
            cursor: pointer;
            background: #f8f8f8;
            border: 1px solid #ddd;
            border-bottom: none;
            margin-right: 5px;
            font-size: 15px;
            border-radius: 5px 5px 0 0;
        }

        .tab-link.active {
            background: #fff;
            border-bottom: 2px solid white;
            font-weight: 600;
            color: #007bff;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .size-option {
            position: relative;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 6px 14px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            background-color: #fff;
            transition: all 0.2s ease;
        }

        .size-option input {
            display: none;
        }

        .size-option:hover {
            border-color: #007bff;
            color: #007bff;
        }

        .size-option input:checked+span {
            color: #fff;
            background-color: #007bff;
            border-radius: 6px;
            padding: 6px 14px;
        }
    </style>

@endsection
