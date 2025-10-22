@extends('admin.pages.master')
@section('title', 'Sub-Sub-Category')
@section('content')

    <div class="container-fluid" id="newBtnSection">
        <div class="row mb-3">
            <div class="col-auto">
                <button type="button" class="btn btn-primary" id="newBtn">Add New Sub-Sub-Category</button>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="addThisFormContainer">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4 class="card-title mb-0 flex-grow-1" id="cardTitle">Add New Sub-Sub-Category</h4>
                    </div>
                    <div class="card-body">
                        <form id="createThisForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="codeid" name="id">

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Sub-Category <span class="text-danger">*</span></label>
                                    <select id="sub_category_id" name="sub_category_id" class="form-select select2">
                                        <option value="">Select Sub-Category</option>
                                        @foreach ($subcategories as $sub)
                                            <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Sub-Sub-Category Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Image</label>
                                    <input type="file" class="form-control" name="image" id="image"
                                        accept="image/*" onchange="previewImage(event, '#preview-image')">
                                    <img id="preview-image" src="#" class="img-thumbnail mt-3"
                                        style="max-width:300px;">
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
        <ul class="nav nav-tabs mb-3" id="subSubCategoryTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#list" role="tab">Sub-Sub List</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#sort" role="tab">Sort Sub-Subs</a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- List Tab -->
            <div class="tab-pane fade show active" id="list" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Sub-Sub-Categories</h4>
                    </div>
                    <div class="card-body">
                        <table id="subSubCategoryTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Name</th>
                                    <th>Sub-Category</th>
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

            <div class="tab-pane fade" id="sort" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Sort Sub-Sub-Categories</h4>
                    </div>
                    <div class="card-body">

                        <!-- Nested Tabs for each Sub-Category -->
                        <ul class="nav nav-tabs mb-3" id="subCategorySortTab" role="tablist">
                            @foreach ($subcategories as $index => $sub)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $index == 0 ? 'active' : '' }}"
                                        id="sub-tab-{{ $sub->id }}" data-bs-toggle="tab"
                                        data-bs-target="#sub-{{ $sub->id }}" type="button" role="tab">
                                        {{ $sub->name }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Tab content for each Sub-Category -->
                        <div class="tab-content" id="subCategorySortTabContent">
                            @foreach ($subcategories as $index => $sub)
                                <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}"
                                    id="sub-{{ $sub->id }}" role="tabpanel">
                                    @if ($sub->subSubCategories->count() > 0)
                                        <small class="text-muted">Drag & drop rows to change order for
                                            {{ $sub->name }}</small>
                                        <table class="table table-bordered subsub-sortable"
                                            data-sub-category-id="{{ $sub->id }}">
                                            <thead>
                                                <tr>
                                                    <th>Sl</th>
                                                    <th>Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($sub->subSubCategories()->orderBy('serial')->get() as $subsub)
                                                    <tr data-id="{{ $subsub->id }}">
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $subsub->name }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p class="text-muted">No Sub-Sub-Categories for this Sub-Category.</p>
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

            var table = $('#subSubCategoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('subsubcategories.index') }}",
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
                        data: 'sub_category',
                        name: 'sub_category'
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
                $.post("{{ route('subsubcategories.toggleStatus') }}", {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    status: status
                }, function(res) {
                    table.ajax.reload();
                    showSuccess(res.message);
                });
            });

            $('.subsub-sortable').each(function() {
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
                        var subCategoryId = table.data('sub-category-id');
                        $.post("{{ route('subsubcategories.updateSerial') }}", {
                            _token: '{{ csrf_token() }}',
                            order: order,
                            sub_category_id: subCategoryId
                        }, function(res) {
                            showSuccess(res.message);
                        });
                    }
                });
            });

            $("#addBtn").click(function() {
                var formData = new FormData($("#createThisForm")[0]);
                var url = $(this).val() == 'Create' ? "{{ route('subsubcategories.store') }}" :
                    "{{ route('subsubcategories.update') }}";

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
                $.get("/admin/sub-sub-category/" + id + "/edit", {}, function(res) {
                    $("#sub_category_id").val(res.sub_category_id).trigger('change');
                    $("#name").val(res.name);
                    $("#description").val(res.description);
                    $("#codeid").val(res.id);
                    $("#addBtn").val('Update').html('Update');
                    $("#cardTitle").text('Update Sub-Sub-Category');
                    if (res.image) $("#preview-image").attr('src', '/images/subsubcategory/' + res
                        .image);
                    else $("#preview-image").attr('src', '#');
                    $("#addThisFormContainer").show(300);
                    $("#newBtn").hide();
                });
            });

            function clearForm() {
                $("#createThisForm")[0].reset();
                $("#sub_category_id").val('').trigger('change');
                $("#addBtn").val('Create').html('Create');
                $("#cardTitle").text('Add New Sub-Sub-Category');
                $("#preview-image").attr('src', '#');
            }
        });
    </script>
@endsection
