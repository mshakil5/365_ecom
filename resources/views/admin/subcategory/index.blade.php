@extends('admin.pages.master')
@section('title', 'Sub-Category')
@section('content')

    <div class="container-fluid" id="newBtnSection">
        <div class="row mb-3">
            <div class="col-auto">
                <button type="button" class="btn btn-primary" id="newBtn">
                    Add New Sub-Category
                </button>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="addThisFormContainer">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4 class="card-title mb-0 flex-grow-1" id="cardTitle">Add New Sub-Category</h4>
                    </div>
                    <div class="card-body">
                        <form id="createThisForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="codeid" name="id">

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <select id="category_id" name="category_id" class="form-select select2">
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Sub-Category Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Sub-Category Image</label>
                                    <input type="file" class="form-control" name="image" id="image"
                                        accept="image/*" onchange="previewImage(event, '#preview-image')">
                                    <img id="preview-image" src="#" alt="" class="img-thumbnail mt-3"
                                        style="max-width:300px;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Meta Title</label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Meta Description</label>
                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="2"></textarea>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Meta Keywords</label>
                                    <textarea class="form-control" id="meta_keywords" name="meta_keywords" rows="2"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" id="addBtn" class="btn btn-primary">Create</button>
                        <button type="button" id="FormCloseBtn" class="btn btn-light">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="contentContainer">
        <ul class="nav nav-tabs mb-3" id="subcategoryTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#list" role="tab">Sub-Category List</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#sort" role="tab">Sort Sub-Categories</a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- List Tab -->
            <div class="tab-pane fade show active" id="list" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Sub-Categories</h4>
                    </div>
                    <div class="card-body">
                        <table id="subCategoryTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sort Tab -->
            <div class="tab-pane fade" id="sort" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Sort Sub-Categories</h4>
                    </div>
                    <div class="card-body">

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs mb-3" id="categorySortTab" role="tablist">
                            @foreach ($categories as $index => $cat)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $index == 0 ? 'active' : '' }}"
                                        id="cat-tab-{{ $cat->id }}" data-bs-toggle="tab"
                                        data-bs-target="#cat-{{ $cat->id }}" type="button" role="tab">
                                        {{ $cat->name }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Tab content -->
                        <div class="tab-content" id="categorySortTabContent">
                            @foreach ($categories as $index => $cat)
                                <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}"
                                    id="cat-{{ $cat->id }}" role="tabpanel">
                                    @if ($cat->subCategories()->count() > 0)
                                        <small class="text-muted">Drag & drop rows to change order for
                                            {{ $cat->name }}</small>
                                        <table class="table table-bordered category-sortable"
                                            data-category-id="{{ $cat->id }}">
                                            <thead>
                                                <tr>
                                                    <th>Sl</th>
                                                    <th>Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($cat->subCategories()->orderBy('serial')->get() as $i => $sub)
                                                    <tr data-id="{{ $sub->id }}">
                                                        <td>{{ $i + 1 }}</td>
                                                        <td>{{ $sub->name }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p class="text-muted">No Sub-Category found for {{ $cat->name }}.</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {

            $("#addThisFormContainer").hide();

            $("#newBtn").click(function() {
                clearForm();
                $("#newBtn").hide();
                $("#addThisFormContainer").show(300);
            });

            $("#FormCloseBtn").click(function() {
                $("#addThisFormContainer").hide(200);
                $("#newBtn").show(100);
                clearForm();
            });

            var table = $('#subCategoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('subcategories.index') }}",
                columns: [{
                        data: 'serial',
                        name: 'serial',
                        orderable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $(document).on('change', '.toggle-status', function() {
                var id = $(this).data('id');
                var status = $(this).prop('checked') ? 1 : 0;
                $.post("{{ route('subcategories.toggleStatus') }}", {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    status: status
                }, function(res) {
                    table.ajax.reload();
                    showSuccess(res.message);
                });
            });

            $('.category-sortable').each(function() {
                var table = $(this);
                table.find('tbody').sortable({
                    placeholder: "ui-state-highlight",
                    cursor: "grab",
                    update: function() {
                        var order = [];
                        table.find('tbody tr').each(function(i, tr) {
                            order.push({
                                id: $(tr).data('id'),
                                serial: i + 1
                            });
                            $(tr).find('td:first').text(i + 1);
                        });

                        var categoryId = table.data('category-id');

                        $.ajax({
                            url: "{{ route('subcategories.updateSerial') }}",
                            method: "POST",
                            data: {
                                _token: '{{ csrf_token() }}',
                                order: order,
                                category_id: categoryId
                            },
                            success: function(res) {
                                showSuccess(res.message);
                            },
                            error: function(xhr) {
                                let msg = xhr.responseJSON?.message ??
                                    "Something went wrong!";
                                showError(msg);
                            }
                        });
                    }
                });
            });

            $("#addBtn").click(function() {
                var formData = new FormData($("#createThisForm")[0]);
                var url = $(this).val() == 'Create' ? "{{ route('subcategories.store') }}" :
                    "{{ route('subcategories.update') }}";

                $.ajax({
                    url: url,
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        showSuccess(res.message);
                        $("#addThisFormContainer").hide();
                        $("#addThisFormContainer").slideUp(300);
                        setTimeout(() => {
                            $("#newBtn").show(200);
                        }, 300);
                        table.ajax.reload();
                        clearForm();
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.errors ? Object.values(xhr.responseJSON
                            .errors)[0][0] : xhr.responseJSON?.message ?? "Error";
                        showError(msg);
                    }
                });
            });

            $(document).on('click', '.EditBtn', function() {
                var id = $(this).data('id');
                $.get("/admin/sub-category/" + id + "/edit", {}, function(res) {
                    $("#category_id").val(res.category_id).trigger('change');
                    $("#name").val(res.name);
                    $("#description").val(res.description);
                    $("#meta_title").val(res.meta_title);
                    $("#meta_description").val(res.meta_description);
                    $("#meta_keywords").val(res.meta_keywords);
                    $("#codeid").val(res.id);
                    $("#addBtn").val('Update').html('Update');
                    $("#cardTitle").text('Update Sub-Category');
                    if (res.image) $("#preview-image").attr('src', '/images/subcategory/' + res
                        .image);
                    else $("#preview-image").attr('src', '#');
                    $("#addThisFormContainer").show(300);
                    $("#newBtn").hide();
                });
            });

            function clearForm() {
                $("#createThisForm")[0].reset();
                $("#category_id").val('').trigger('change');
                $("#addBtn").val('Create').html('Create');
                $("#cardTitle").text('Add New Sub-Category');
                $("#preview-image").attr('src', '#');
            }
        });
    </script>

@endsection
