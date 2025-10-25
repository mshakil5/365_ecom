@extends('admin.pages.master')

@section('title', 'Edit Product')

@section('content')
    <div class="container-fluid">
        <form id="product-form" action="{{ route('update.product', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-8">
                    {{-- Product Information --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Product Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-8">
                                        <label class="form-label">Product Title <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label">Product Code <span class="text-danger">*</span></label>
                                        <input type="text" name="code" class="form-control" value="{{ $product->code }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Short Description</label>
                                <textarea name="short_description" class="form-control" rows="3">{{ $product->short_description }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Long Description</label>
                                <textarea name="long_description" class="form-control ckeditor-classic">{{ $product->long_description }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Categories --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Categories</h5>
                        </div>
                        <div class="card-body" id="category-container">
                            @php
                                $categoryProducts = $product->categories ?? [];
                            @endphp
                            
                            @foreach($categoryProducts as $index => $categoryProduct)
                            <div class="row category-row mb-4 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label">Category<span class="text-danger">*</span>
                                        <button type="button" class="btn btn-sm badge rounded-pill bg-success"
                                            data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                            Add New Category
                                        </button>
                                    </label>
                                    <select name="category[]" class="form-select category" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ $categoryProduct->pivot->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Sub Category</label>
                                    <select name="subcategory[]" class="form-select subcategory">
                                        <option value="">Select Sub Category</option>
                                        @foreach($subCategories as $subCategory)
                                            @if($subCategory->category_id == $categoryProduct->pivot->category_id)
                                            <option value="{{ $subCategory->id }}" 
                                                {{ $categoryProduct->pivot->sub_category_id == $subCategory->id ? 'selected' : '' }}>
                                                {{ $subCategory->name }}
                                            </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Sub Sub Category</label>
                                    <select name="subsubcategory[]" class="form-select subsubcategory">
                                        <option value="">Select Sub Sub Category</option>
                                        @foreach($subSubCategories as $subSubCategory)
                                            @if($subSubCategory->sub_category_id == $categoryProduct->pivot->sub_category_id)
                                            <option value="{{ $subSubCategory->id }}" 
                                                {{ $categoryProduct->pivot->sub_sub_category_id == $subSubCategory->id ? 'selected' : '' }}>
                                                {{ $subSubCategory->name }}
                                            </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-1 text-center">
                                    @if($index === 0)
                                    <button type="button" class="btn btn-success add-row">
                                        <i class="ri-add-line"></i>
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-danger remove-row">
                                        <i class="ri-delete-bin-6-line"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                            @endforeach

                            @if(count($categoryProducts) === 0)
                            <div class="row category-row mb-4 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label">Category<span class="text-danger">*</span>
                                        <button type="button" class="btn btn-sm badge rounded-pill bg-success"
                                            data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                            Add New Category
                                        </button>
                                    </label>
                                    <select name="category[]" class="form-select category" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Sub Category</label>
                                    <select name="subcategory[]" class="form-select subcategory">
                                        <option value="">Select Sub Category</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Sub Sub Category</label>
                                    <select name="subsubcategory[]" class="form-select subsubcategory">
                                        <option value="">Select Sub Sub Category</option>
                                    </select>
                                </div>

                                <div class="col-md-1 text-center">
                                    <button type="button" class="btn btn-success add-row">
                                        <i class="ri-add-line"></i>
                                    </button>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Gallery --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Product Gallery</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Feature Image</label>
                                <input type="file" name="feature_image" class="form-control" accept="image/*"
                                    onchange="previewImage(event, '#preview-image')">
                                @if($product->feature_image)
                                <div class="mt-2">
                                    <img id="preview-image" src="{{ asset('images/products/' . $product->feature_image) }}" 
                                         alt="Feature Image" class="img-thumbnail rounded" style="max-height: 200px;">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remove_feature_image" value="1" id="removeFeatureImage">
                                        <label class="form-check-label text-danger" for="removeFeatureImage">
                                            Remove feature image
                                        </label>
                                    </div>
                                </div>
                                @else
                                <img id="preview-image" src="#" alt="" class="img-thumbnail rounded mt-3" style="display:none;">
                                @endif
                            </div>

                            <h5 class="fs-14 mb-1">Product Gallery</h5>
                            <p class="text-muted">Add Product Gallery Images.</p>

                            {{-- Existing Gallery Images --}}
                            @if($product->productImages && $product->productImages->count() > 0)
                            <div class="mb-3">
                                <h6 class="mb-2">Existing Images:</h6>
                                <div class="row">
                                    @foreach($product->productImages as $image)
                                    <div class="col-md-3 mb-3">
                                        <div class="card">
                                            <img src="{{ asset('images/products/' . $image->image) }}" 
                                                 class="card-img-top" alt="Product Image" style="height: 150px; object-fit: cover;">
                                            <div class="card-body p-2">
                                                <select class="form-select form-select-sm mb-2" name="existing_image_colors[{{ $image->id }}]">
                                                    <option value="">No color</option>
                                                    @foreach ($colors as $color)
                                                        <option value="{{ $color->id }}" 
                                                            {{ $image->color_id == $color->id ? 'selected' : '' }}>
                                                            {{ $color->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="remove_images[]" value="{{ $image->id }}" 
                                                           id="removeImage{{ $image->id }}">
                                                    <label class="form-check-label text-danger" for="removeImage{{ $image->id }}">
                                                        Remove
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div class="dropzone" id="product-gallery-dropzone">
                                <div class="fallback">
                                    <input name="file" type="file" multiple="multiple">
                                </div>
                                <div class="dz-message needsclick">
                                    <div class="mb-3">
                                        <i class="display-4 text-muted ri-upload-cloud-2-fill"></i>
                                    </div>
                                    <h5>Drop files here or click to upload.</h5>
                                </div>
                            </div>

                            <ul class="list-unstyled mb-0" id="dropzone-preview">
                                <li class="mt-2" id="dropzone-preview-list">
                                    <div class="border rounded">
                                        <div class="d-flex p-2 align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-sm bg-light rounded">
                                                    <img data-dz-thumbnail class="img-fluid rounded d-block" src="#"
                                                        alt="Product-Image" />
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="pt-1">
                                                    <h5 class="fs-14 mb-1" data-dz-name>&nbsp;</h5>
                                                    <p class="fs-13 text-muted mb-0" data-dz-size></p>
                                                    <strong class="error text-danger" data-dz-errormessage></strong>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0 ms-3 d-flex flex-column">
                                                <select class="form-select form-select-sm mb-2" name="colors[]">
                                                    <option value="">No color</option>
                                                    @foreach ($colors as $color)
                                                        <option value="{{ $color->id }}">{{ $color->name }}</option>
                                                    @endforeach
                                                </select>
                                                <button data-dz-remove class="btn btn-sm btn-danger">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success px-4">Update Product</button>
                            <a href="{{ route('products.index') }}" class="btn btn-light px-4">Cancel</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    {{-- Product Settings --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Product Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Brand
                                        <button type="button" class="btn btn-sm badge rounded-pill bg-success"
                                            data-bs-toggle="modal" data-bs-target="#addBrandModal">
                                            Add New Brand
                                        </button>
                                    </label>
                                    <select name="brand_id" class="form-select select2 brand_id">
                                        <option value="">Select Brand</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}" 
                                                {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Unit
                                        <button type="button" class="btn btn-sm badge rounded-pill bg-success"
                                            data-bs-toggle="modal" data-bs-target="#addUnitModal">
                                            Add New Unit
                                        </button>
                                    </label>
                                    <select name="unit_id" class="form-select select2 unit_id">
                                        <option value="">Select Unit</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}" 
                                                {{ $product->unit_id == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Group
                                        <button type="button" class="btn btn-sm badge rounded-pill bg-success"
                                            data-bs-toggle="modal" data-bs-target="#addGroupModal">
                                            Add New Group
                                        </button>
                                    </label>
                                    <select name="group_id" class="form-select select2 group_id">
                                        <option value="">Select Group</option>
                                        @foreach ($groups as $group)
                                            <option value="{{ $group->id }}" 
                                                {{ $product->group_id == $group->id ? 'selected' : '' }}>
                                                {{ $group->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tags
                                        <button type="button" class="btn btn-sm badge rounded-pill bg-success"
                                            data-bs-toggle="modal" data-bs-target="#addTagModal">
                                            Add New Tag
                                        </button>
                                    </label>
                                    <select name="tags[]" class="form-select select2 tags" multiple>
                                        @foreach ($tags as $tag)
                                            <option value="{{ $tag->id }}" 
                                                {{ in_array($tag->id, $product->tags->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                {{ $tag->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                                            {{ $product->is_featured ? 'checked' : '' }}>
                                        <label class="form-check-label">Featured Product</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="is_trending" value="1"
                                            {{ $product->is_trending ? 'checked' : '' }}>
                                        <label class="form-check-label">Trending Product</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="is_new_arrival" value="1"
                                            {{ $product->is_new_arrival ? 'checked' : '' }}>
                                        <label class="form-check-label">New Arrival</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="is_top_rated" value="1"
                                            {{ $product->is_top_rated ? 'checked' : '' }}>
                                        <label class="form-check-label">Top Rated</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="is_popular" value="1"
                                            {{ $product->is_popular ? 'checked' : '' }}>
                                        <label class="form-check-label">Popular Product</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="is_recent" value="1"
                                            {{ $product->is_recent ? 'checked' : '' }}>
                                        <label class="form-check-label">Recent Product</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Meta Data --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Meta Data</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Meta Title</label>
                                <input type="text" name="meta_title" class="form-control" value="{{ $product->meta_title }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description</label>
                                <textarea name="meta_description" class="form-control" rows="3">{{ $product->meta_description }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Key Words (Comma Separated)</label>
                                <textarea name="meta_keywords" class="form-control" rows="3">{{ $product->meta_keywords }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Meta Image</label>
                                <input type="file" name="meta_image" class="form-control" accept="image/*"
                                    onchange="previewImage(event, '#preview-meta-image')">
                                @if($product->meta_image)
                                <div class="mt-2">
                                    <img id="preview-meta-image" src="{{ asset('images/products/' . $product->meta_image) }}" 
                                         alt="Meta Image" class="img-thumbnail rounded" style="max-height: 150px;">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remove_meta_image" value="1" id="removeMetaImage">
                                        <label class="form-check-label text-danger" for="removeMetaImage">
                                            Remove meta image
                                        </label>
                                    </div>
                                </div>
                                @else
                                <img id="preview-meta-image" src="#" alt="" class="img-thumbnail rounded mt-3" style="display:none;">
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Extra Data --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Additional Data</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Company</label>
                                    <input type="text" name="company" class="form-control" value="{{ $product->company }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category API</label>
                                    <input type="text" name="category_api" class="form-control" value="{{ $product->category_api }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Product Code API</label>
                                    <input type="text" name="product_code_api" class="form-control" value="{{ $product->product_code_api }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Product Name API</label>
                                    <input type="text" name="product_name_api" class="form-control" value="{{ $product->product_name_api }}">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Full Description</label>
                                    <textarea name="full_description" class="form-control" rows="3">{{ $product->full_description }}</textarea>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Short Code</label>
                                    <input type="text" name="short_code" class="form-control" value="{{ $product->short_code }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tariff No</label>
                                    <input type="text" name="tariff_no" class="form-control" value="{{ $product->tariff_no }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">EAN</label>
                                    <input type="text" name="ean" class="form-control" value="{{ $product->ean }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Wash Degrees</label>
                                    <input type="number" name="wash_degrees" class="form-control" value="{{ $product->wash_degrees }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Gender</label>
                                    <input type="text" name="gender" class="form-control" value="{{ $product->gender }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">GSM</label>
                                    <input type="number" name="gsm" class="form-control" value="{{ $product->gsm }}">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Composition</label>
                                    <textarea name="composition" class="form-control" rows="2">{{ $product->composition }}</textarea>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Specifications</label>
                                    <textarea name="specifications" class="form-control" rows="2">{{ $product->specifications }}</textarea>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Colour Code</label>
                                    <input type="text" name="colour_code" class="form-control" value="{{ $product->colour_code }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Colour Name</label>
                                    <input type="text" name="colour_name_api" class="form-control" value="{{ $product->colour_name_api }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Pantone</label>
                                    <input type="text" name="pantone" class="form-control" value="{{ $product->pantone }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Hex Code</label>
                                    <input type="text" name="hex_code" class="form-control" value="{{ $product->hex_code }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Size Name API</label>
                                    <input type="text" name="size_name_api" class="form-control" value="{{ $product->size_name_api }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Price (Single)</label>
                                    <input type="number" step="0.01" name="price_single" class="form-control" value="{{ $product->price_single }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Qty (Single)</label>
                                    <input type="number" name="qty_single" class="form-control" value="{{ $product->qty_single }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Price (Pack)</label>
                                    <input type="number" step="0.01" name="price_pack" class="form-control" value="{{ $product->price_pack }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Pack Qty</label>
                                    <input type="number" name="pack_qty" class="form-control" value="{{ $product->pack_qty }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Price (Carton)</label>
                                    <input type="number" step="0.01" name="price_caton" class="form-control" value="{{ $product->price_caton }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Carton Qty</label>
                                    <input type="number" name="carton_qty" class="form-control" value="{{ $product->carton_qty }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Price (1K)</label>
                                    <input type="number" step="0.01" name="price_1k" class="form-control" value="{{ $product->price_1k }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Quantity API</label>
                                    <input type="number" name="quantity_api" class="form-control" value="{{ $product->quantity_api }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">My Price</label>
                                    <input type="number" step="0.01" name="my_price" class="form-control" value="{{ $product->my_price }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Image</label>
                                    <input type="file" name="image" class="form-control">
                                    @if($product->image)
                                    <small class="text-muted">Current: {{ $product->image }}</small>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Small Image</label>
                                    <input type="file" name="small_image" class="form-control">
                                    @if($product->small_image)
                                    <small class="text-muted">Current: {{ $product->small_image }}</small>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Colour Image</label>
                                    <input type="file" name="colour_image" class="form-control">
                                    @if($product->colour_image)
                                    <small class="text-muted">Current: {{ $product->colour_image }}</small>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">SM Colour Image</label>
                                    <input type="file" name="sm_colour_image" class="form-control">
                                    @if($product->sm_colour_image)
                                    <small class="text-muted">Current: {{ $product->sm_colour_image }}</small>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Video Link</label>
                                    <input type="text" name="video_link" class="form-control" value="{{ $product->video_link }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Packaging</label>
                                    <input type="text" name="packaging" class="form-control" value="{{ $product->packaging }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Country of Origin</label>
                                    <input type="text" name="country_of_origin" class="form-control" value="{{ $product->country_of_origin }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Gross Weight</label>
                                    <input type="number" step="0.01" name="gross_weight" class="form-control" value="{{ $product->gross_weight }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Net Weight</label>
                                    <input type="number" step="0.01" name="net_weight" class="form-control" value="{{ $product->net_weight }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tax Code</label>
                                    <input type="text" name="tax_code" class="form-control" value="{{ $product->tax_code }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @include('admin.modals.category')
    @include('admin.modals.brand')
    @include('admin.modals.unit')
    @include('admin.modals.group')
    @include('admin.modals.tag')
@endsection

@section('script')
    @include('admin.modals.category_script')
    @include('admin.modals.brand_script')
    @include('admin.modals.unit_script')
    @include('admin.modals.tag_script')
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2();

            // Load options for dynamic category selects
            function loadOptions(type, id, target) {
                $.ajax({
                    url: "{{ route('get.data') }}",
                    type: "GET",
                    data: {
                        type: type,
                        id: id
                    },
                    success: function(res) {
                        let options = `<option value="">Select ${type.charAt(0).toUpperCase() + type.slice(1)}</option>`;
                        res.forEach(item => {
                            options += `<option value="${item.id}">${item.name}</option>`;
                        });
                        target.html(options);
                    }
                });
            }

            // Initialize category dropdowns for existing rows
            $('.category-row').each(function() {
                const $row = $(this);
                const categoryId = $row.find('.category').val();
                const subcategoryId = $row.find('.subcategory').val();
                
                if (categoryId) {
                    loadOptions('subcategory', categoryId, $row.find('.subcategory'));
                    
                    // After loading subcategories, set the selected value
                    setTimeout(() => {
                        $row.find('.subcategory').val(subcategoryId).trigger('change');
                    }, 100);
                }
            });

            $(document).on('change', '.category', function() {
                let id = $(this).val();
                let $row = $(this).closest('.category-row');
                loadOptions('subcategory', id, $row.find('.subcategory'));
                $row.find('.subsubcategory').html('<option value="">Select Sub Sub Category</option>');
            });

            $(document).on('change', '.subcategory', function() {
                let id = $(this).val();
                let $row = $(this).closest('.category-row');
                loadOptions('subsubcategory', id, $row.find('.subsubcategory'));
            });

            $(document).on('click', '.add-row', function() {
                let newRow = $('.category-row:first').clone();
                newRow.find('select').val('');
                newRow.find('.subcategory, .subsubcategory').html('<option value="">Select</option>');
                newRow.find('.col-md-1').html(`
                    <button type="button" class="btn btn-danger remove-row">
                        <i class="ri-delete-bin-6-line"></i>
                    </button>
                `);

                $('#category-container').append(newRow);
                
                // Re-initialize Select2 for new row
                newRow.find('.select2').select2();
                
                // Load categories for new row
                loadOptions('category', null, newRow.find('.category'));
            });

            $(document).on('click', '.remove-row', function() {
                if ($('.category-row').length > 1) {
                    $(this).closest('.category-row').remove();
                } else {
                    showError('At least one category row is required.');
                }
            });

            // Form submission
            $('#product-form').on('submit', function(e) {
                e.preventDefault();

                var $form = $(this);
                var $button = $form.find('button[type="submit"]');
                var originalText = $button.html();

                $button.prop('disabled', true).html(`
                    <span class="d-flex align-items-center">
                        <span class="spinner-border flex-shrink-0" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </span>
                        <span class="flex-grow-1 ms-2">Updating...</span>
                    </span>
                `);

                var formData = new FormData($form[0]);

                // Add Dropzone files to form data
                if (typeof dropzone !== 'undefined') {
                    dropzone.files.forEach(function(file, index) {
                        var color = $(file.previewElement).find('select[name="colors[]"]').val() || '';
                        formData.append(`gallery_images[${index}][file]`, file);
                        formData.append(`gallery_images[${index}][color]`, color);
                    });
                }

                $.ajax({
                    url: $form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        showSuccess(res.message);
                        $button.prop('disabled', false).html(originalText);
                        setTimeout(() => {
                            window.location.href = "{{ route('products.index') }}";
                        }, 1500);
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            let firstError = Object.values(xhr.responseJSON.errors)[0][0];
                            showError(firstError);
                        } else {
                            showError(xhr.responseJSON?.message ?? "Something went wrong!");
                        }
                        $button.prop('disabled', false).html(originalText);
                        console.error(xhr.responseText);
                    }
                });
            });

            // Image preview function
            window.previewImage = function(event, previewId) {
                const input = event.target;
                const preview = $(previewId)[0];
                
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        $(preview).show();
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            };
        });

        // Dropzone initialization
        Dropzone.autoDiscover = false;

        var dropzonePreviewNode = document.querySelector("#dropzone-preview-list");
        dropzonePreviewNode.id = "";
        var previewTemplate = dropzonePreviewNode.parentNode.innerHTML;
        dropzonePreviewNode.parentNode.removeChild(dropzonePreviewNode);

        var dropzone = new Dropzone("#product-gallery-dropzone", {
            url: "#",
            method: "post",
            previewTemplate: previewTemplate,
            previewsContainer: "#dropzone-preview",
            autoProcessQueue: false,
            addRemoveLinks: false,
            acceptedFiles: "image/*",
            init: function() {
                this.on("addedfile", function(file) {});
                this.on("removedfile", function(file) {});
            }
        });
    </script>
@endsection