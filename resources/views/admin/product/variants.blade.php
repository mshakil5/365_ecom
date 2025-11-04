@extends('admin.pages.master')

@section('title', 'Manage Product Variants - ' . $product->name)

@section('content')
<div class="container-fluid">
    <div class="col-2 mb-2">
        <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
    </div>

    <form id="variants-form" action="{{ route('products.variants.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="accordion" id="productAccordion">

            {{-- Variants Section --}}
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingVariants">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseVariants" aria-expanded="true">
                        {{ $product->name }} Variants
                    </button>
                </h2>
                <div id="collapseVariants" class="accordion-collapse collapse show" data-bs-parent="#productAccordion">
                    <div class="accordion-body">

                        <div class="d-flex justify-content-end mb-3">
                            <button type="button" class="btn btn-sm btn-success" id="add-variant">
                                <i class="ri-add-line"></i> Add Variant
                            </button>
                        </div>

                        <div id="variants-container">
                            @foreach($variants as $index => $variant)
                            <div class="variant-row border rounded p-3 mb-3">
                                <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Color</label>
                                        <select name="variants[{{ $index }}][color_id]" class="form-select">
                                            <option value="">No Color</option>
                                            @foreach($colors as $color)
                                            <option value="{{ $color->id }}" {{ $variant->color_id == $color->id ? 'selected' : '' }}>
                                                {{ $color->name }} ({{ $color->code }})
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Size</label>
                                        <select name="variants[{{ $index }}][size_id]" class="form-select">
                                            <option value="">No Size</option>
                                            @foreach($sizes as $size)
                                            <option value="{{ $size->id }}" {{ $variant->size_id == $size->id ? 'selected' : '' }}>
                                                {{ $size->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Short Code</label>
                                        <input type="text" name="variants[{{ $index }}][short_code]" 
                                            class="form-control" value="{{ $variant->short_code }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">EAN</label>
                                        <input type="text" name="variants[{{ $index }}][ean]" 
                                            class="form-control" value="{{ $variant->ean }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Stock Quantity</label>
                                        <input type="number" name="variants[{{ $index }}][stock_quantity]" 
                                            class="form-control" value="{{ $variant->stock_quantity }}">
                                    </div>
                                </div>
                                
                                <div class="row mt-2">
                                    <div class="col-md-3">
                                        <label class="form-label">Price Single</label>
                                        <input type="number" step="0.01" name="variants[{{ $index }}][price_single]" 
                                            class="form-control" value="{{ $variant->price_single }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">My Price</label>
                                        <input type="number" step="0.01" name="variants[{{ $index }}][my_price]" 
                                            class="form-control" value="{{ $variant->my_price }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Quantity</label>
                                        <input type="number" name="variants[{{ $index }}][quantity]" 
                                            class="form-control" value="{{ $variant->quantity }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label d-block">Status</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" 
                                                name="variants[{{ $index }}][is_active]" value="1" 
                                                {{ $variant->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label">Active</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="button" class="btn btn-sm btn-danger mt-2 remove-variant">
                                    <i class="ri-delete-bin-line"></i> Remove
                                </button>
                            </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

            {{-- Product Images Section --}}
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingImages">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseImages">
                        Product Images
                    </button>
                </h2>
                <div id="collapseImages" class="accordion-collapse collapse" data-bs-parent="#productAccordion">
                    <div class="accordion-body">
                        <div class="d-flex justify-content-end mb-3">
                            <button type="button" class="btn btn-sm btn-success" id="add-image">
                                <i class="ri-add-line"></i> Add New Image
                            </button>
                        </div>

                        <div class="mb-4">
                            <h6>Add New Images</h6>
                            <div id="new-images-container"></div>
                        </div>

                        @if($productImages->count() > 0)
                        <div class="mb-4">
                            <h6>Existing Images</h6>
                            <div class="row" id="existing-images-container">
                                @foreach($productImages as $image)
                                <div class="col-md-3 mb-3 existing-image">
                                    <div class="card">
                                        <img src="{{ asset($image->image_path) }}" class="card-img-top existing-image-preview" 
                                            alt="Product Image" style="height: 150px; object-fit: cover;">
                                        <div class="card-body">
                                            <p class="card-text small">
                                                <strong>Color:</strong> {{ $image->color->name ?? 'No Color' }}<br>
                                                <strong>Type:</strong> {{ $image->image_type }}<br>
                                            </p>
                                            <div class="d-flex justify-content-between">
                                                <button type="button" class="btn btn-sm btn-outline-warning edit-image" 
                                                        data-image-id="{{ $image->id }}"
                                                        data-color-id="{{ $image->color_id }}"
                                                        data-image-type="{{ $image->image_type }}"
                                                        data-is-primary="{{ $image->is_primary }}">
                                                    <i class="ri-edit-line"></i> Edit
                                                </button>
                                                <div class="form-check">
                                                    <input class="form-check-input delete-image-checkbox" 
                                                           type="checkbox" 
                                                           name="delete_images[]" 
                                                           value="{{ $image->id }}"
                                                           id="delete_image_{{ $image->id }}">
                                                    <label class="form-check-label text-danger" for="delete_image_{{ $image->id }}">
                                                        Delete
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-success px-4"><i class="ri-save-line"></i> Update Variants & Images</button>
                <a href="{{ url()->previous() }}" class="btn btn-primary px-4"><i class="ri-arrow-left-line"></i> Back</a>
            </div>
        </div>
    </form>
</div>

{{-- Image Edit Modal --}}
<div class="modal fade" id="editImageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit-image-form">
                    <input type="hidden" id="edit_image_id">
                    <div class="mb-3">
                        <label class="form-label">Color</label>
                        <select id="edit_color_id" class="form-select">
                            <option value="">No Color</option>
                            @foreach($colors as $color)
                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image Type</label>
                        <select id="edit_image_type" class="form-select">
                            <option value="model">Model</option>
                            <option value="front">Front</option>
                            <option value="back">Back</option>
                            <option value="swatch">Swatch</option>
                            <option value="general">General</option>
                            <option value="right">Right</option>
                            <option value="left">Left</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="save-image-changes">Save Changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        let variantIndex = {{ $variants->count() }};
        let imageIndex = 0;

        $('#add-variant').click(function() {
            const html = `
                <div class="variant-row border rounded p-3 mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Color</label>
                            <select name="variants[${variantIndex}][color_id]" class="form-select">
                                <option value="">No Color</option>
                                @foreach($colors as $color)
                                <option value="{{ $color->id }}">{{ $color->name }} ({{ $color->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Size</label>
                            <select name="variants[${variantIndex}][size_id]" class="form-select">
                                <option value="">No Size</option>
                                @foreach($sizes as $size)
                                <option value="{{ $size->id }}">{{ $size->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Short Code</label>
                            <input type="text" name="variants[${variantIndex}][short_code]" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">EAN</label>
                            <input type="text" name="variants[${variantIndex}][ean]" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Stock Quantity</label>
                            <input type="number" name="variants[${variantIndex}][stock_quantity]" class="form-control" value="0">
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <label class="form-label">Price Single</label>
                            <input type="number" step="0.01" name="variants[${variantIndex}][price_single]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">My Price</label>
                            <input type="number" step="0.01" name="variants[${variantIndex}][my_price]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="variants[${variantIndex}][quantity]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label d-block">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                    name="variants[${variantIndex}][is_active]" value="1" checked>
                                <label class="form-check-label">Active</label>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-variant">
                        <i class="ri-delete-bin-line"></i> Remove
                    </button>
                </div>
            `;
            $('#variants-container').prepend(html);
            variantIndex++;
        });

        $('#add-image').click(function() {
            const html = `
                <div class="image-row border rounded p-3 mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Color (Optional)</label>
                            <select name="images[${imageIndex}][color_id]" class="form-select">
                                <option value="">No Color</option>
                                @foreach($colors as $color)
                                <option value="{{ $color->id }}">{{ $color->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Image Type</label>
                            <select name="images[${imageIndex}][image_type]" class="form-select">
                                <option value="model">Model</option>
                                <option value="front">Front</option>
                                <option value="back">Back</option>
                                <option value="swatch">Swatch</option>
                                <option value="general">General</option>
                                <option value="right">Right</option>
                                <option value="left">Left</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Image</label>
                            <input type="file" name="images[${imageIndex}][image]" class="form-control image-upload" accept="image/*">
                            <div class="image-preview mt-2" style="display: none;">
                                <img src="" alt="Preview" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-image">
                        <i class="ri-delete-bin-line"></i> Remove
                    </button>
                </div>
            `;
            $('#new-images-container').prepend(html);
            imageIndex++;
        });

        $(document).on('change', '.image-upload', function() {
            const file = this.files[0];
            const preview = $(this).siblings('.image-preview');
            const previewImg = preview.find('img');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.attr('src', e.target.result);
                    preview.show();
                }
                reader.readAsDataURL(file);
            } else {
                preview.hide();
            }
        });

        const editImageModal = new bootstrap.Modal(document.getElementById('editImageModal'));
        let currentEditingImage = null;

        $(document).on('click', '.edit-image', function() {
            const imageId = $(this).data('image-id');
            const colorId = $(this).data('color-id');
            const imageType = $(this).data('image-type');
            
            $('#edit_image_id').val(imageId);
            $('#edit_color_id').val(colorId || '');
            $('#edit_image_type').val(imageType);
            
            currentEditingImage = $(this).closest('.existing-image');
            editImageModal.show();
        });

        $('#save-image-changes').click(function() {
            const imageId = $('#edit_image_id').val();
            const colorId = $('#edit_color_id').val();
            const imageType = $('#edit_image_type').val();

            if (currentEditingImage) {
                currentEditingImage.find('.card-text').html(`
                    <strong>Color:</strong> ${$('#edit_color_id option:selected').text() || 'No Color'}<br>
                    <strong>Type:</strong> ${imageType}
                `);

                currentEditingImage.find('.edit-image')
                    .data('color-id', colorId)
                    .data('image-type', imageType);
            }

            editImageModal.hide();
            showSuccess('Image details updated successfully');
        });

        $(document).on('click', '.remove-variant', function() {
            $(this).closest('.variant-row').remove();
        });

        $(document).on('click', '.remove-image', function() {
            $(this).closest('.image-row').remove();
        });

        $('#variants-form').on('submit', function(e) {
            e.preventDefault();
            
            const $form = $(this);
            const $button = $form.find('button[type="submit"]');
            const originalText = $button.html();

            $button.prop('disabled', true).html(`
                <span class="d-flex align-items-center">
                    <span class="spinner-border flex-shrink-0" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </span>
                    <span class="flex-grow-1 ms-2">Loading...</span>
                </span>
            `);

            const formData = new FormData($form[0]);

            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    showSuccess(res.message);
                    $button.prop('disabled', false).html(originalText);
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    let msg = "Something went wrong!";

                    if (xhr.responseJSON) {
                        if (xhr.status === 422 && xhr.responseJSON.errors) {
                            msg = Object.values(xhr.responseJSON.errors)[0][0];
                        }
                        else if (xhr.responseJSON.error) {
                            msg = xhr.responseJSON.error;
                        }
                        else if (xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                    } else if (xhr.responseText) {
                        msg = xhr.responseText;
                    } else {
                        msg = `${xhr.status} ${xhr.statusText}`;
                    }
                    showError(msg);
                    $button.prop('disabled', false).html(originalText);
                    console.error('AJAX error:', xhr);
                }
            });
        });
    });
</script>
@endsection