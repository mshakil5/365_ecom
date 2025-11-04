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
                                    <div class="col-6">
                                        <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" placeholder="" required>
                                    </div>
                                    <div class="col-3">
                                        <label class="form-label">Product Code <span class="text-danger">*</span></label>
                                        <input type="text" name="product_code" class="form-control" placeholder=""
                                            required>
                                    </div>
                                    <div class="col-3">
                                        <label class="form-label">Price <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" name="price" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Full Description</label>
                                <textarea name="full_description" class="form-control ckeditor-classic" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Composition</label>
                                <textarea name="composition" class="form-control ckeditor-classic"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Specifications</label>
                                <textarea name="specifications" class="form-control ckeditor-classic"></textarea>
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
                                <label class="form-label">Small Image</label>
                                <input type="file" name="small_image" class="form-control" accept="image/*"
                                    onchange="previewImage(event, '#preview-sm-image')">
                                <img id="preview-sm-image" src="#" alt="" class="avatar-md mt-3">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Feature Image</label>
                                <input type="file" name="feature_image" class="form-control" accept="image/*"
                                    onchange="previewImage(event, '#preview-image')">
                                <img id="preview-image" src="#" alt="" class="img-thumbnail rounded mt-3">
                            </div>
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
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Category <span class="text-danger">*</span>
                                        <button type="button" class="btn btn-sm badge rounded-pill bg-success"
                                            data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                            Add New Category
                                        </button>
                                    </label>
                                    <select name="category_id" class="form-select select2 category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Company <span class="text-danger">*</span>
                                        <button type="button" class="btn btn-sm badge rounded-pill bg-success"
                                            data-bs-toggle="modal" data-bs-target="#addCompanyModal">
                                            Add New Company
                                        </button>
                                    </label>
                                    <select name="company_id" class="form-select select2 company_id" required>
                                        <option value="">Select Company</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="is_customizable"
                                            value="1">
                                        <label class="form-check-label">Allow Customization</label>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3 d-none">
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

                                <div class="col-md-6 d-none">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="is_featured"
                                            value="1">
                                        <label class="form-check-label">Featured Product</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="is_trending"
                                            value="1" checked>
                                        <label class="form-check-label">Trending Product</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="show_in_frontend"
                                            value="1" checked>
                                        <label class="form-check-label">Show on Frontend</label>
                                    </div>
                                </div>

                                <div class="col-md-6 d-none">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="is_new_arrival"
                                            value="1">
                                        <label class="form-check-label">New Arrival</label>
                                    </div>
                                </div>

                                <div class="col-md-6 d-none">
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
                                        <label class="form-check-label">Best Selling Product</label>
                                    </div>
                                </div>

                                <div class="col-md-6 d-none">
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
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Tariff No</label>
                                    <input type="text" name="tariff_no" class="form-control">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Wash Degrees</label>
                                    <input type="number" name="wash_degrees" class="form-control">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-control select2">
                                        <option value="" disabled selected>Select Gender</option>
                                        <option value="Unisex">Unisex</option>
                                        <option value="Women's">Women's</option>
                                        <option value="Men's">Men's</option>
                                        <option value="Children">Children</option>
                                        <option value="Unknown">Unknown</option>
                                    </select>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">GSM</label>
                                    <input type="number" name="gsm" class="form-control">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Video Link</label>
                                    <input type="text" name="video_link" class="form-control">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Packaging</label>
                                    <input type="text" name="packaging" class="form-control">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Country of Origin</label>
                                    <input type="text" name="country_of_origin" class="form-control">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Gross Weight</label>
                                    <input type="number" step="0.01" name="gross_weight" class="form-control">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Net Weight</label>
                                    <input type="number" step="0.01" name="net_weight" class="form-control">
                                </div>

                                <div class="col-md-12 mb-3">
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
    @include('admin.modals.company')
    @include('admin.modals.tag')
@endsection

@section('script')
    @include('admin.modals.category_script')
    @include('admin.modals.company_script')
    @include('admin.modals.tag_script')
    <script>
        $(document).ready(function() {

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
                        reload();
                    },
                    error: function(xhr) {
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
@endsection