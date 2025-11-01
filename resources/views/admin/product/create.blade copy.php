@extends('admin.pages.master')

@section('title', 'Create Product')

@section('content')
    <div class="container-fluid">
        <form id="product-form" action="{{ route('store.product') }}" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-8">
                    @csrf
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
                                        <input type="text" name="name" class="form-control" placeholder="" required>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label">Product Code <span class="text-danger">*</span></label>
                                        <input type="text" name="code" class="form-control" placeholder="" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Short Description</label>
                                <textarea name="short_description" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Long Description</label>
                                <textarea name="long_description" class="form-control ckeditor-classic"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Categories --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Categories</h5>
                        </div>
                        <div class="card-body" id="category-container">
                            <div class="row category-row mb-4 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label">Category<span class="text-danger">*</span>
                                        <button type="button" class="btn btn-sm badge rounded-pill bg-success"
                                            data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                            Add New Category
                                        </button>
                                    </label>
                                    <select name="category[]" class="form-select category" required></select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Sub Category</label>
                                    <select name="subcategory[]" class="form-select subcategory"></select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Sub Sub Category</label>
                                    <select  name="subsubcategory[]" class="form-select subsubcategory"></select>
                                </div>

                                <div class="col-md-1 text-center">
                                    <button type="button" class="btn btn-success add-row">
                                        <i class="ri-add-line"></i>
                                    </button>
                                </div>
                            </div>
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
                                <img id="preview-image" src="#" alt="" class="img-thumbnail rounded mt-3">
                            </div>

                            <h5 class="fs-14 mb-1">Product Gallery</h5>
                            <p class="text-muted">Add Product Gallery Images.</p>

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
                            <button type="submit" class="btn btn-success px-4">Save Product</button>
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
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
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
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
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
                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
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
                                            <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="is_featured"
                                            value="1">
                                        <label class="form-check-label">Featured Product</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="is_trending"
                                            value="1">
                                        <label class="form-check-label">Trending Product</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="is_new_arrival"
                                            value="1">
                                        <label class="form-check-label">New Arrival</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="is_top_rated"
                                            value="1">
                                        <label class="form-check-label">Top Rated</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="is_popular"
                                            value="1">
                                        <label class="form-check-label">Popular Product</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="is_recent" value="1">
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
                                <input type="text" name="meta_title" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description</label>
                                <textarea name="meta_description" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Key Words(Comma Separated)</label>
                                <textarea name="meta_description" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Meta Image</label>
                                <input type="file" name="meta_image" class="form-control" accept="image/*"
                                    onchange="previewImage(event, '#preview-meta-image')">
                                <img id="preview-meta-image" src="#" alt=""
                                    class="img-thumbnail rounded mt-3">
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
                                    <input type="text" name="company" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category API</label>
                                    <input type="text" name="category_api" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Product Code API</label>
                                    <input type="text" name="product_code_api" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Product Name API</label>
                                    <input type="text" name="product_name_api" class="form-control">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Full Description</label>
                                    <textarea name="full_description" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Short Code</label>
                                    <input type="text" name="short_code" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tariff No</label>
                                    <input type="text" name="tariff_no" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">EAN</label>
                                    <input type="text" name="ean" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Wash Degrees</label>
                                    <input type="number" name="wash_degrees" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Gender</label>
                                    <input type="text" name="gender" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">GSM</label>
                                    <input type="number" name="gsm" class="form-control">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Composition</label>
                                    <textarea name="composition" class="form-control" rows="2"></textarea>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Specifications</label>
                                    <textarea name="specifications" class="form-control" rows="2"></textarea>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Colour Code</label>
                                    <input type="text" name="colour_code" class="form-control">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Colour Name</label>
                                    <input type="text" name="colour_name_api" class="form-control">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Pantone</label>
                                    <input type="text" name="pantone" class="form-control">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Hex Code</label>
                                    <input type="text" name="hex_code" class="form-control">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Size Name API</label>
                                    <input type="text" name="size_name_api" class="form-control">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Price (Single)</label>
                                    <input type="number" step="0.01" name="price_single" class="form-control">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Qty (Single)</label>
                                    <input type="number" name="qty_single" class="form-control">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Price (Pack)</label>
                                    <input type="number" step="0.01" name="price_pack" class="form-control">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Pack Qty</label>
                                    <input type="number" name="pack_qty" class="form-control">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Price (Carton)</label>
                                    <input type="number" step="0.01" name="price_caton" class="form-control">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Carton Qty</label>
                                    <input type="number" name="carton_qty" class="form-control">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Price (1K)</label>
                                    <input type="number" step="0.01" name="price_1k" class="form-control">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Quantity API</label>
                                    <input type="number" name="quantity_api" class="form-control">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">My Price</label>
                                    <input type="number" step="0.01" name="my_price" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Image</label>
                                    <input type="file" name="image" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Small Image</label>
                                    <input type="file" name="small_image" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Colour Image</label>
                                    <input type="file" name="colour_image" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">SM Colour Image</label>
                                    <input type="file" name="sm_colour_image" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Video Link</label>
                                    <input type="text" name="video_link" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Packaging</label>
                                    <input type="text" name="packaging" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Country of Origin</label>
                                    <input type="text" name="country_of_origin" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Gross Weight</label>
                                    <input type="number" step="0.01" name="gross_weight" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Net Weight</label>
                                    <input type="number" step="0.01" name="net_weight" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tax Code</label>
                                    <input type="text" name="tax_code" class="form-control">
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
            loadOptions('category', null, $('.category'));

            function loadOptions(type, id, target) {
                $.ajax({
                    url: "{{ route('get.data') }}",
                    type: "GET",
                    data: {
                        type: type,
                        id: id
                    },
                    success: function(res) {
                        let options =
                            `<option value="">Select ${type.charAt(0).toUpperCase() + type.slice(1)}</option>`;
                        res.forEach(item => {
                            options += `<option value="${item.id}">${item.name}</option>`;
                        });
                        target.html(options);
                    }
                });
            }

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
                loadOptions('category', null, newRow.find('.category')); // move this inside correctly
            });

            $(document).on('click', '.remove-row', function() {
                $(this).closest('.category-row').remove();
            });

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
                        <span class="flex-grow-1 ms-2">Loading...</span>
                    </span>
                `);

                var formData = new FormData($form[0]);

                dropzone.files.forEach(function(file, index) {
                    var color = $(file.previewElement).find('select[name="colors[]"]').val() || '';
                    formData.append(`gallery_images[${index}][file]`, file);
                    formData.append(`gallery_images[${index}][color]`, color);
                });

                $.ajax({
                    url: $form.attr('action'),
                    type: $form.attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        showSuccess(res.message);
                        $form[0].reset();
                        $button.prop('disabled', false).html(originalText);
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

        });
    </script>

    <script>
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