@extends('frontend.pages.master')

@section('content')

    <div class="breadcrumb-section">
        <div class="breadcrumb-wrapper">
            <div class="container">
                <div class="row">
                    <div
                        class="col-12 d-flex justify-content-between justify-content-md-between  align-items-center flex-md-row flex-column">
                        <h3 class="breadcrumb-title"></h3>
                        <div class="breadcrumb-nav">
                            <nav aria-label="breadcrumb">
                                <ul>
                                    <li><a href="{{ route('home') }}">Home</a></li>
                                    <li aria-current="page">{{ $product->name }}</li>
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
                    <div class="product-details-gallery-area d-flex align-items-center flex-row-reverse">
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

                <div class="col-md-6">
                    <div class="product-details-content-area">
                        <div class="product-details-text">
                            <h4 class="title">{{ $product->name }}</h4>
                            <div class="price">
                                {{-- <del>$49.99</del> --}}
                                £{{ number_format($product->price, 2) }} <span class="text-black">excl VAT</span>
                            </div>
                            <p class="product-meta small mt-2">
                                {{ $product->product_code ? "Code: {$product->product_code}" : '' }}
                                @if ($product->category)
                                    | Category: {{ $product->category->name }}
                                @endif
                                @if ($product->company)
                                    | Company: {{ $product->company->name }}
                                @endif
                            </p>
                        </div>

                        <div class="pricing-table-container scroll-gradient-wrapper">
                            <div class="shadow shadow--left"></div>
                            <div class="shadow shadow--right"></div>

                            <div class="pricing-table scroll-gradient-element">
                                <div class="tab-links d-flex">
                                    @foreach ($prices as $category => $items)
                                        <h3 class="tab-link {{ $loop->first ? 'active' : '' }}"
                                            data-tab="{{ Str::slug($category) }}">
                                            {{ $category }}
                                        </h3>
                                    @endforeach
                                </div>

                                <div class="tab-panes">
                                    @foreach ($prices as $category => $items)
                                        <div id="{{ Str::slug($category) }}"
                                            class="tab-content {{ $loop->first ? 'active' : '' }}">
                                            <table class="table table-striped">
                                                <tbody>
                                                    <tr>
                                                        <th>Qty</th>
                                                        <td>1</td>
                                                        @foreach ($items as $item)
                                                            <td>{{ $item->min_max_qty }}</td>
                                                        @endforeach
                                                        <td><a href="#">500+</a></td>
                                                    </tr>

                                                    <tr>
                                                        <th>Price</th>
                                                        <td>£{{ number_format($product->price, 2) }}</td>
                                                        <!-- Base price -->
                                                        @foreach ($items as $item)
                                                            @php
                                                                $discounted = $item->discount_percent
                                                                    ? $product->price *
                                                                        (1 - $item->discount_percent / 100)
                                                                    : $product->price;
                                                            @endphp
                                                            <td>£{{ number_format($discounted, 2) }}</td>
                                                        @endforeach
                                                        <td><a href="#">Contact Us</a></td>
                                                    </tr>

                                                    <tr style="color:#6c757d; font-style:italic;">
                                                        <th>Discount</th>
                                                        <td></td>
                                                        @foreach ($items as $item)
                                                            <td>{{ $item->discount_percent ? '-' . $item->discount_percent . '%' : '' }}
                                                            </td>
                                                        @endforeach
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="product-details-variable">
                            <h4 class="title">Available Options</h4>

                            @php
                                $colors = $product->variants->pluck('color')->filter()->unique('id');
                            @endphp

                            @if ($colors->count() > 0)
                                <div class="variable-single-item">
                                    <span>Select Color</span>
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
                                $variants = $product->variants;
                                $sizes = $variants->pluck('size')->filter()->unique('id');
                            @endphp

                            @if ($sizes->count() > 0)
                                <style>
                                    .size-qty-container {
                                        display: flex;
                                        flex-wrap: wrap;
                                        gap: 10px;
                                        justify-content: flex-start;
                                        align-items: flex-end;
                                    }

                                    .size-box {
                                        width: 55px;
                                        text-align: center;
                                    }

                                    .size-box .size-name {
                                        font-weight: 600;
                                        margin-bottom: 3px;
                                        font-size: 0.9em;
                                    }

                                    .size-box button {
                                        border: none;
                                        background: none;
                                        padding: 0;
                                        font-size: 1.1em;
                                        cursor: pointer;
                                        color: #333;
                                        line-height: 1;
                                    }

                                    .size-box button:hover {
                                        color: #0d6efd;
                                    }

                                    .size-box input {
                                        width: 45px;
                                        height: 32px;
                                        text-align: center;
                                        border: 1px solid #ccc;
                                        border-radius: 4px;
                                        font-size: 14px;
                                        font-weight: 500;
                                        padding: 0;
                                        margin: 2px 0;
                                    }

                                    .size-box input:focus {
                                        outline: none;
                                        border-color: #0d6efd;
                                    }

                                    input[type=number]::-webkit-inner-spin-button,
                                    input[type=number]::-webkit-outer-spin-button {
                                        -webkit-appearance: none;
                                        margin: 0;
                                    }

                                    input[type=number] {
                                        -moz-appearance: textfield;
                                    }
                                </style>

                                <div class="variable-single-item mb-3">
                                    <span class="d-block mb-2 fw-semibold">Select Sizes:</span>

                                    <div class="size-qty-container">
                                        @foreach ($sizes as $size)
                                                  @php
                                                      $variant = $variants->where('size_id', $size->id)->first();
                                                  @endphp
                                            <div class="size-box">
                                                <div class="size-name">{{ $size->name }}</div>
                                                <button type="button" class="plus-btn">+</button>
                                                <input type="number"
                                                  name="sizes[{{ $size->id }}]"
                                                  value="0"
                                                  min="0"
                                                  class="qty-input"
                                                  data-slug="{{ $product->slug }}"
                                                  data-size-id="{{ $size->id }}"
                                                  data-ean="{{ $variant->ean ?? '' }}">
                                                <button type="button" class="minus-btn">−</button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <script>
                                    document.querySelectorAll('.plus-btn').forEach(btn => {
                                        btn.addEventListener('click', () => {
                                            const input = btn.nextElementSibling;
                                            input.value = parseInt(input.value || 0) + 1;
                                        });
                                    });
                                    document.querySelectorAll('.minus-btn').forEach(btn => {
                                        btn.addEventListener('click', () => {
                                            const input = btn.previousElementSibling;
                                            if (parseInt(input.value) > 0) input.value = parseInt(input.value) - 1;
                                        });
                                    });
                                </script>
                            @endif

                            <div class="d-flex align-items-center mt-3">
                                <div class="product-add-to-cart-btn">
                                    <a href="#" class="offcanvas-toggle add-to-cart-btn"
                                        data-product-id="{{ $product->id }}"
                                        data-product-name="{{ $product->name }}"
                                        data-image="{{ $product->feature_image }}"
                                        data-action="cart">
                                        Add To Cart
                                    </a>
                                </div>

                                @if($product->is_customizable == 1)
                                <div class="product-add-to-cart-btn ms-2">
                                    <a href="#" class="offcanvas-toggle add-to-cart-btn"
                                        data-product-id="{{ $product->id }}"
                                        data-product-name="{{ $product->name }}"
                                        data-image="{{ $product->feature_image }}"
                                        data-action="customize">
                                        Customise
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="product-details-text pt-3">

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
                                    style="display:flex;align-items:center;border:1px solid #ccc;padding:10px; margin-bottom:10px;border-radius:5px;">
                                    <i class="fa fa-water" style="font-size:24px;color:#17a2b8;margin-right:10px;"></i>
                                    <span style="font-size:16px;">Wash Degrees: {{ $product->wash_degrees }}</span>
                                </div>
                            @endif

                            @if ($product->gender)
                                <div
                                    style="display:flex;align-items:center;border:1px solid #ccc;padding:10px;margin-bottom:10px;border-radius:5px;">
                                    <i class="fa fa-user" style="font-size:24px;color:#fd7e14;margin-right:10px;"></i>
                                    <span style="font-size:16px;">Gender: {{ $product->gender }}</span>
                                </div>
                            @endif

                            @if ($product->tariff_no)
                                <div
                                    style="display:none;align-items:center;border:1px solid #ccc;padding:10px;margin-bottom:10px;border-radius:5px;">
                                    <i class="fa fa-hashtag" style="font-size:24px;color:#20c997;margin-right:10px;"></i>
                                    <span style="font-size:16px;">Tariff No: {{ $product->tariff_no }}</span>
                                </div>
                            @endif

                            @if ($product->packaging)
                                <div
                                    style="display:flex;align-items:center;border:1px solid #ccc;padding:10px;margin-bottom:10px;border-radius:5px;">
                                    <i class="fa fa-box" style="font-size:24px;color:#0d6efd;margin-right:10px;"></i>
                                    <span style="font-size:16px;">Packaging: {{ $product->packaging }}</span>
                                </div>
                            @endif

                            @if ($product->video_link)
                                <div style="margin-bottom:15px;">
                                    <label style="font-weight:500;">Video Preview:</label>
                                    <video width="100%" height="auto" controls
                                        style="border-radius:5px; border:1px solid #ccc;">
                                        <source src="{{ $product->video_link }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            @endif

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
                    <div class="product-details-content-tab-wrapper" data-aos="fade-up" data-aos-delay="0">

                        <!-- Tab Buttons -->
                        <ul class="nav tablist product-details-content-tab-btn d-flex justify-content-center">
                            <li><a class="nav-link active" data-bs-toggle="tab" href="#description">
                                    <h5>Description</h5>
                                </a></li>
                            <li><a class="nav-link" data-bs-toggle="tab" href="#review">
                                    <h5>Reviews (2)</h5>
                                </a></li>
                        </ul>

                        <!-- Tab Contents -->
                        <div class="product-details-content-tab">
                            <div class="tab-content">

                                <!-- Description -->
                                <div class="tab-pane active show" id="description">
                                    <div class="single-tab-content-item">
                                        <p>
                                            This stylish cotton t-shirt is perfect for everyday wear.
                                            It features a soft texture, classic fit, and durable stitching.
                                            Pair it with jeans or shorts for a casual look.
                                        </p>
                                    </div>
                                </div>

                                <!-- Reviews -->
                                <div class="tab-pane" id="review">
                                    <div class="single-tab-content-item">
                                        <div class="reviews">
                                            <h3>Reviews (2)</h3>

                                            <div class="review">
                                                <div class="row no-gutters">
                                                    <div class="col-auto">
                                                        <h4><a href="#">John Doe</a></h4>
                                                        <div class="ratings-container">
                                                            <div class="ratings">
                                                                <div class="ratings-val" style="width: 80%;"></div>
                                                            </div>
                                                        </div>
                                                        <span class="review-date">2 days ago</span>
                                                    </div>
                                                    <div class="col">
                                                        <h4>Great Quality!</h4>
                                                        <div class="review-content">
                                                            <p>Nice fabric and perfect fit. Definitely recommended!</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="review">
                                                <div class="row no-gutters">
                                                    <div class="col-auto">
                                                        <h4><a href="#">Jane Smith</a></h4>
                                                        <div class="ratings-container">
                                                            <div class="ratings">
                                                                <div class="ratings-val" style="width: 100%;"></div>
                                                            </div>
                                                        </div>
                                                        <span class="review-date">5 days ago</span>
                                                    </div>
                                                    <div class="col">
                                                        <h4>Perfect!</h4>
                                                        <div class="review-content">
                                                            <p>Excellent color and comfort. Will buy again.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="review-form mt-3">
                                            <h4>Submit a Review</h4>
                                            <form id="reviewForm">
                                                <div class="row">
                                                    <div class="form-group col-6">
                                                        <label for="reviewTitle">Title</label>
                                                        <input type="text" id="reviewTitle" class="form-control"
                                                            placeholder="Review Title">
                                                    </div>
                                                    <div class="form-group col-6">
                                                        <label for="reviewRating">Rating</label>
                                                        <select id="reviewRating" class="form-control">
                                                            <option>5 Stars</option>
                                                            <option>4 Stars</option>
                                                            <option>3 Stars</option>
                                                            <option>2 Stars</option>
                                                            <option>1 Star</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="reviewDescription">Description</label>
                                                    <textarea id="reviewDescription" class="form-control" rows="3" placeholder="Your review"></textarea>
                                                </div>
                                                <button type="submit" class="form-submit-btn">Submit Review</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tags -->
                            <p class="my-3">
                                <strong style="font-size: 16px;">Tags:</strong>
                                <a href="#" class="tag-item">T-Shirt</a>
                                <a href="#" class="tag-item">Cotton</a>
                                <a href="#" class="tag-item">Casual</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($relatedProducts->count() > 0)
        <div class="product-tab-section section-top-gap-100">
            <div class="section-content-gap">
                <div class="container">
                    <div class="row">
                        <div
                            class="section-content d-flex justify-content-between align-items-md-center align-items-start flex-md-row flex-column">
                            <h3 class="section-title" data-aos="fade-up" data-aos-delay="0">
                                Related Products
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="product-tab-wrapper" data-aos="fade-up" data-aos-delay="50">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="product-default-slider product-default-slider-4grids-1row">
                                @foreach ($relatedProducts as $related)
                                    @include('frontend.partials.single_product', ['product' => $related])
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        .scroll-gradient-wrapper {
            position: relative;
            overflow-x: auto;
            padding: 0 20px;
        }

        .scroll-gradient-element {
            display: flex;
            flex-direction: column;
            min-width: 100%;
        }

        .tab-links {
            border-bottom: 2px solid #ddd;
            margin-bottom: 10px;
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
            transition: all 0.2s;
        }

        .tab-link.active {
            background: #fff;
            border-bottom: 2px solid #fff;
            font-weight: 600;
            color: #007bff;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .table {
            min-width: 700px;
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
    <script>
        document.querySelectorAll('.tab-link').forEach(link => {
            link.addEventListener('click', function() {
                const tab = this.dataset.tab;

                // deactivate other tabs
                document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

                // activate this tab
                this.classList.add('active');
                document.getElementById(tab).classList.add('active');
            });
        });
    </script>

@endsection