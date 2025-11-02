@extends('admin.pages.master')
@section('title', 'Product Details')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row gx-lg-5">
                            <div class="col-xl-4 col-md-8 mx-auto">
                                <div class="product-img-slider sticky-side-div">
                                    @if ($product->feature_image)
                                        <div class="p-2 rounded bg-light text-center">
                                            <img src="{{ $product->feature_image }}" alt="Product Image"
                                                class="img-fluid d-block mx-auto" />
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <!-- end col -->

                            <!-- Product Details Section -->
                            <div class="col-xl-8">
                                <div class="mt-xl-0 mt-5">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <h4>{{ $product->name ?? 'Product Name' }}</h4>
                                            <div class="hstack gap-3 flex-wrap">
                                                @if ($product->company)
                                                    <div><a href="#"
                                                            class="text-primary d-block">{{ $product->company->name }}</a>
                                                    </div>
                                                    <div class="vr"></div>
                                                @endif
                                                <div class="text-muted">Product Code : <span
                                                        class="text-body fw-medium">{{ $product->product_code }}</span>
                                                </div>
                                                <div class="vr"></div>
                                                <div class="text-muted">Source : <span
                                                        class="text-body fw-medium">{{ $product->product_source == 1 ? 'Manual' : 'API' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="d-none">
                                                <a href="#" class="btn btn-light" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Edit">
                                                    <i class="ri-pencil-fill align-bottom"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Product Stats -->
                                    <div class="row mt-4">
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="p-2 border border-dashed rounded">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                            <i class="ri-money-dollar-circle-fill"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted mb-1">Base Price :</p>
                                                        <h5 class="mb-0">£{{ number_format($product->price, 2) }}</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end col -->
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="p-2 border border-dashed rounded">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                            <i class="ri-eye-fill"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted mb-1">Views :</p>
                                                        <h5 class="mb-0">{{ number_format($product->views) }}</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end col -->
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="p-2 border border-dashed rounded">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                            <i class="ri-stack-fill"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted mb-1">Total Stock :</p>
                                                        <h5 class="mb-0">{{ $product->variants->sum('stock_quantity') }}
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end col -->
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="p-2 border border-dashed rounded">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                            <i class="ri-checkbox-circle-fill"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted mb-1">Status :</p>
                                                        <h5 class="mb-0">
                                                            <span
                                                                class="badge bg-{{ $product->status ? 'success' : 'danger' }}">
                                                                {{ $product->status ? 'Active' : 'Inactive' }}
                                                            </span>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end col -->
                                    </div>

                                    <!-- Sizes & Colors -->
                                    <div class="row mt-4">
                                        <div class="col-xl-6">
                                            <div class="mt-4">
                                                <h5 class="fs-14">Available Sizes :</h5>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @php
                                                        $sizes = $product->variants
                                                            ->pluck('size')
                                                            ->filter()
                                                            ->unique('id');
                                                    @endphp
                                                    @foreach ($sizes as $size)
                                                        @php
                                                            $sizeStock = $product->variants
                                                                ->where('size_id', $size->id)
                                                                ->sum('stock_quantity');
                                                            $isAvailable = $sizeStock > 0;
                                                        @endphp
                                                        <div data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                            data-bs-placement="top"
                                                            title="{{ $isAvailable ? $sizeStock . ' Items Available' : 'Out of Stock' }}">
                                                            <input type="radio" class="btn-check" name="productsize-radio"
                                                                id="productsize-{{ $size->id }}"
                                                                {{ !$isAvailable ? 'disabled' : '' }}>
                                                            <label
                                                                class="btn btn-soft-{{ $isAvailable ? 'primary' : 'secondary' }} avatar-xs rounded-circle p-0 d-flex justify-content-center align-items-center"
                                                                for="productsize-{{ $size->id }}">
                                                                {{ $size->name }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end col -->

                                        <div class="col-xl-6">
                                            <div class="mt-4">
                                                <h5 class="fs-14">Available Colors :</h5>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @php
                                                        $colors = $product->variants
                                                            ->pluck('color')
                                                            ->filter()
                                                            ->unique('id');
                                                    @endphp
                                                    @foreach ($colors as $color)
                                                        @php
                                                            $colorStock = $product->variants
                                                                ->where('color_id', $color->id)
                                                                ->sum('stock_quantity');
                                                            $isAvailable = $colorStock > 0;
                                                        @endphp
                                                        <div data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                            data-bs-placement="top"
                                                            title="{{ $color->name }} ({{ $isAvailable ? $colorStock . ' Available' : 'Out of Stock' }})">
                                                            <button type="button"
                                                                class="btn avatar-xs p-0 d-flex align-items-center justify-content-center border rounded-circle fs-20 {{ !$isAvailable ? 'disabled' : '' }}"
                                                                style="color: {{ $color->hex ?? '#6c757d' }} !important;">
                                                                <i class="ri-checkbox-blank-circle-fill"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end col -->
                                    </div>
                                    <!-- end row -->

                                    <!-- Product Description -->
                                    @if ($product->full_description)
                                        <div class="mt-4 text-muted">
                                            <h5 class="fs-14">Description :</h5>
                                            <p>{!! $product->full_description !!}</p>
                                        </div>
                                    @endif

                                    <!-- Product Specifications -->
                                    <div class="product-content mt-5">
                                        <h5 class="fs-14 mb-3">Product Specifications :</h5>
                                        <nav>
                                            <ul class="nav nav-tabs nav-tabs-custom nav-success" id="nav-tab"
                                                role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="nav-specs-tab" data-bs-toggle="tab"
                                                        href="#nav-specs" role="tab" aria-controls="nav-specs"
                                                        aria-selected="true">Specifications</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="nav-variants-tab" data-bs-toggle="tab"
                                                        href="#nav-variants" role="tab" aria-controls="nav-variants"
                                                        aria-selected="false">Variants</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="nav-images-tab" data-bs-toggle="tab"
                                                        href="#nav-images" role="tab" aria-controls="nav-images"
                                                        aria-selected="false">Images</a>
                                                </li>
                                            </ul>
                                        </nav>
                                        <div class="tab-content border border-top-0 p-4" id="nav-tabContent">
                                            <!-- Specifications Tab -->
                                            <div class="tab-pane fade show active" id="nav-specs" role="tabpanel"
                                                aria-labelledby="nav-specs-tab">
                                                <div class="table-responsive">
                                                    <table class="table mb-0">
                                                        <tbody>
                                                            @if ($product->category)
                                                                <tr>
                                                                    <th scope="row" style="width: 200px;">Category</th>
                                                                    <td>{{ $product->category->name }}</td>
                                                                </tr>
                                                            @endif
                                                            @if ($product->company)
                                                                <tr>
                                                                    <th scope="row">Company</th>
                                                                    <td>{{ $product->company->name }}</td>
                                                                </tr>
                                                            @endif
                                                            @if ($product->composition)
                                                                <tr>
                                                                    <th scope="row">Composition</th>
                                                                    <td>{!! $product->composition !!}</td>
                                                                </tr>
                                                            @endif
                                                            @if ($product->gender)
                                                                <tr>
                                                                    <th scope="row">Gender</th>
                                                                    <td>{{ $product->gender }}</td>
                                                                </tr>
                                                            @endif
                                                            @if ($product->gsm)
                                                                <tr>
                                                                    <th scope="row">GSM</th>
                                                                    <td>{{ $product->gsm }}</td>
                                                                </tr>
                                                            @endif
                                                            @if ($product->country_of_origin)
                                                                <tr>
                                                                    <th scope="row">Country of Origin</th>
                                                                    <td>{{ $product->country_of_origin }}</td>
                                                                </tr>
                                                            @endif
                                                            @if ($product->tariff_no)
                                                                <tr>
                                                                    <th scope="row">Tariff No</th>
                                                                    <td>{{ $product->tariff_no }}</td>
                                                                </tr>
                                                            @endif
                                                            @if ($product->wash_degrees)
                                                                <tr>
                                                                    <th scope="row">Wash Degrees</th>
                                                                    <td>{{ $product->wash_degrees }}</td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <!-- Variants Tab -->
                                            <div class="tab-pane fade" id="nav-variants" role="tabpanel"
                                                aria-labelledby="nav-variants-tab">
                                                <div class="table-responsive">
                                                    <table class="table table-hover mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Variant Code</th>
                                                                <th>Color</th>
                                                                <th>Size</th>
                                                                <th>EAN</th>
                                                                <th>Stock</th>
                                                                <th>Price Single</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($product->variants as $variant)
                                                                <tr>
                                                                    <td>{{ $variant->variant_short_code ?? $variant->short_code }}
                                                                    </td>
                                                                    <td>
                                                                        @if ($variant->color)
                                                                            <span class="badge"
                                                                                style="background-color: {{ $variant->color->hex ?? '#6c757d' }}; color: white;">
                                                                                {{ $variant->color->name }}
                                                                            </span>
                                                                        @else
                                                                            <span class="text-muted">N/A</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $variant->size->name ?? 'N/A' }}</td>
                                                                    <td>{{ $variant->ean ?? 'N/A' }}</td>
                                                                    <td>
                                                                        <span
                                                                            class="badge bg-{{ $variant->stock_quantity > 0 ? 'success' : 'danger' }}">
                                                                            {{ $variant->stock_quantity }}
                                                                        </span>
                                                                    </td>
                                                                    <td>${{ number_format($variant->price_single ?? $product->price, 2) }}
                                                                    </td>
                                                                    <td>
                                                                        <span
                                                                            class="badge bg-{{ $variant->is_active ? 'success' : 'danger' }}">
                                                                            {{ $variant->is_active ? 'Active' : 'Inactive' }}
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <!-- Images Tab -->
                                            <div class="tab-pane fade" id="nav-images" role="tabpanel"
                                                aria-labelledby="nav-images-tab">
                                                <div class="row">
                                                    @foreach ($product->images as $image)
                                                        <div class="col-md-3 mb-3">
                                                            <div class="card h-100">
                                                                <img src="{{ $image->image_path }}" class="card-img-top"
                                                                    alt="Product Image"
                                                                    style="height: 150px; object-fit: cover;">
                                                                <div
                                                                    class="card-body p-2 text-center d-flex justify-content-between align-items-center">
                                                                    <div>
                                                                        <small class="text-muted">Type:
                                                                            {{ ucfirst($image->image_type) }}</small>
                                                                        @if ($image->is_primary)
                                                                            <span
                                                                                class="badge bg-primary ms-1">Primary</span>
                                                                        @endif
                                                                    </div>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-warning d-none"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#editImageTypeModal{{ $image->id }}">
                                                                        Edit Type
                                                                    </button>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <!-- Modal -->
                                                        <div class="modal fade"
                                                            id="editImageTypeModal{{ $image->id }}" tabindex="-1"
                                                            aria-labelledby="editImageTypeModalLabel{{ $image->id }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <form class="update-image-type-form"
                                                                    data-id="{{ $image->id }}">
                                                                    @csrf
                                                                    <input type="hidden" name="image_id"
                                                                        value="{{ $image->id }}">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="editImageTypeModalLabel{{ $image->id }}">
                                                                                Update Image Type</h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <select name="image_type" class="form-select"
                                                                                required>
                                                                                <option value="">Select Type</option>
                                                                                <option value="front"
                                                                                    {{ $image->image_type == 'front' ? 'selected' : '' }}>
                                                                                    Front</option>
                                                                                <option value="back"
                                                                                    {{ $image->image_type == 'back' ? 'selected' : '' }}>
                                                                                    Back</option>
                                                                                <option value="right"
                                                                                    {{ $image->image_type == 'right' ? 'selected' : '' }}>
                                                                                    Right</option>
                                                                                <option value="left"
                                                                                    {{ $image->image_type == 'left' ? 'selected' : '' }}>
                                                                                    Left</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="submit"
                                                                                class="btn btn-primary">Update
                                                                                Type</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end product-content -->
                                </div>
                            </div>
                            <div class="col-2 mt-2">
                                <a href="{{ url()->previous() }}" class="btn btn-primary"> Back</a>
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.update-image-type-form').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);
                let imageId = form.data('id');
                let imageType = form.find('select[name="image_type"]').val();
                let token = form.find('input[name="_token"]').val();

                $.ajax({
                    url: "{{ route('admin.product.image.update') }}",
                    type: "POST",
                    data: {
                        _token: token,
                        image_id: imageId,
                        image_type: imageType
                    },
                    success: function(res) {
                        if (res.success) {
                            showSuccess(res.message);
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON) {
                            let first = Object.values(xhr.responseJSON.errors)[0][0];
                            showError(first);
                        } else {
                            showError(xhr.responseJSON?.message ?? 'Something went wrong');
                        }
                    }
                });
            });
        });
    </script>
@endsection