@extends('admin.pages.master')

@section('title', 'Create Product')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <form action="#" method="POST" enctype="multipart/form-data">
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
                                  <input type="text" name="name" class="form-control" placeholder="Enter product name" required>
                              </div>
                              <div class="col-4">
                                  <label class="form-label">Product Code <span class="text-danger">*</span></label>
                                  <input type="text" name="code" class="form-control" placeholder="Enter product code" required>
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
                                      <button type="button" class="btn btn-sm badge rounded-pill bg-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                        Add New Category
                                      </button>
                                </label>
                                <select class="form-select category"></select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Sub Category</label>
                                <select class="form-select subcategory"></select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Sub Sub Category</label>
                                <select class="form-select subsubcategory"></select>
                            </div>

                            <div class="col-md-1 text-center">
                                <button type="button" class="btn btn-success add-row">
                                    <i class="ri-add-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Gallery -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Product Gallery</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Feature Image</label>
                            <input type="file" name="feature_image" class="form-control" accept="image/*" onchange="previewImage(event, '#preview-image')">
                            <img id="preview-image" src="#" alt="" class="img-thumbnail rounded mt-3"
                                style="max-width: 300px;">
                        </div>

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
                                                <img data-dz-thumbnail class="img-fluid rounded d-block" src="#" alt="Product-Image" />
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
                                                @foreach($colors as $color)
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

                <!-- Meta Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Meta Data</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Meta Title</label>
                                <input type="text" name="meta_title" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Meta Keywords</label>
                                <input type="text" name="meta_keywords" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Meta Description</label>
                            <textarea name="meta_description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success px-4">Save Product</button>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Product Settings</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Brand</label>
                        <select name="brand_id" class="form-select">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Color</label>
                        <select name="color_id" class="form-select">
                            <option value="">Select Color</option>
                            @foreach($colors as $color)
                                <option value="{{ $color->id }}">{{ $color->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tags</label>
                        <select name="tags[]" class="form-select" multiple>
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="featured">
                        <label class="form-check-label" for="featured">Featured Product</label>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="is_trending" value="1" id="trending">
                        <label class="form-check-label" for="trending">Trending</label>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="is_new_arrival" value="1" id="newArrival">
                        <label class="form-check-label" for="newArrival">New Arrival</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.modals.category')
@endsection

@section('script')
@include('admin.modals.category_script')
<script>
    $(document).ready(function () {
        loadOptions('category', null, $('.category'));

        function loadOptions(type, id, target) {
            $.ajax({
                url: "{{ route('get.data') }}",
                type: "GET",
                data: { type: type, id: id },
                success: function (res) {
                    let options = `<option value="">Select ${type.charAt(0).toUpperCase() + type.slice(1)}</option>`;
                    res.forEach(item => { options += `<option value="${item.id}">${item.name}</option>`; });
                    target.html(options);
                }
            });
        }

        $(document).on('change', '.category', function () {
            let id = $(this).val();
            let $row = $(this).closest('.category-row');
            loadOptions('subcategory', id, $row.find('.subcategory'));
            $row.find('.subsubcategory').html('<option value="">Select Sub Sub Category</option>');
        });

        $(document).on('change', '.subcategory', function () {
            let id = $(this).val();
            let $row = $(this).closest('.category-row');
            loadOptions('subsubcategory', id, $row.find('.subsubcategory'));
        });

        $(document).on('click', '.add-row', function () {
            let newRow = $('.category-row:first').clone();
            newRow.find('select').val('');
            newRow.find('.subcategory, .subsubcategory').html('<option value="">Select</option>');

            newRow.find('.col-md-1').html(`
                <button type="button" class="btn btn-danger remove-row">
                    <i class="ri-delete-bin-6-line"></i>
                </button>
            `);

            $('#category-container').append(newRow);
            loadOptions('category', null, newRow.find('.category')); // load categories for new row
        });

        $(document).on('click', '.remove-row', function () {
            $(this).closest('.category-row').remove();
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
          this.on("addedfile", function(file) {
          });
          this.on("removedfile", function(file) {
          });
      }
  });

</script>
@endsection