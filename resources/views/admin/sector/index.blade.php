@extends('admin.pages.master')
@section('title', 'Sector')
@section('content')

    <div class="container-fluid" id="newBtnSection">
        <div class="row mb-3">
            <div class="col-auto">
                <button type="button" class="btn btn-primary" id="newBtn">
                    Add New Sector
                </button>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="addThisFormContainer">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1" id="cardTitle">Add New Sector</h4>
                    </div>
                    <div class="card-body">
                        <form id="createThisForm">
                            @csrf
                            <input type="hidden" id="codeid" name="codeid">

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Sector Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" placeholder=""></textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Sector Image</label>
                                    <input type="file" class="form-control" id="image" accept="image/*"
                                        onchange="previewImage(event, '#preview-image')">
                                    <img id="preview-image" src="#" alt="" class="img-thumbnail rounded mt-3"
                                        style="max-width: 300px;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Meta Title</label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title"
                                        placeholder="Enter meta title">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Meta Description</label>
                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="2"
                                        placeholder="Enter meta description"></textarea>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Meta Keywords</label>
                                    <textarea class="form-control" id="meta_keywords" name="meta_keywords" rows="2" placeholder="Enter meta keywords"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" id="addBtn" class="btn btn-primary">
                            Create
                        </button>
                        <button type="button" id="FormCloseBtn" class="btn btn-light">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="contentContainer">

        <ul class="nav nav-tabs mb-3" id="sectorTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="list-tab" data-bs-toggle="tab" href="#list" role="tab">Sector
                    List</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="sort-tab" data-bs-toggle="tab" href="#sort" role="tab">Sort Sectors</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="list" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Sectors</h4>
                    </div>
                    <div class="card-body">
                        <table id="sectorTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Name</th>
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
                        <h4 class="card-title mb-0">Sort Sectors</h4>
                        <small class="text-muted">Drag & drop rows to change order</small>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody id="sortable">
                                @foreach ($sectors as $sector)
                                    <tr data-id="{{ $sector->id }}">
                                        <td>{{ $sector->serial }}</td>
                                        <td>{{ $sector->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
            $("#sortable").sortable({
                placeholder: "ui-state-highlight",
                cursor: "grab",
                forcePlaceholderSize: true,
                opacity: 0.8,
                update: function(event, ui) {
                    var order = $(this).sortable('toArray', {
                        attribute: 'data-id'
                    });
                    $.ajax({
                        url: "{{ route('sectors.updateOrder') }}",
                        method: "POST",
                        data: {
                            _token: '{{ csrf_token() }}',
                            order: order
                        },
                        success: function(res) {
                            showSuccess(res.message);
                            $("#sortable tr").each(function(index) {
                                $(this).find("td:first").text(index + 1);
                            });
                            reloadTable('#sectorTable');
                        },
                        error: function(xhr) {
                            let message = xhr.responseJSON?.message ??
                                "Something went wrong!";
                            showError(message);
                        }
                    });
                }
            });

            // Optional: make cursor pointer for drag handle
            $("#sortable i.ri-drag-move-2-line").css("cursor", "grab");
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#sectorTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('allsector') }}",
                columns: [{
                        data: 'serial',
                        name: 'serial',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
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
                var sector_id = $(this).data('id');
                var status = $(this).prop('checked') ? 1 : 0;

                $.ajax({
                    url: '/admin/sector-status',
                    method: "POST",
                    data: {
                        sector_id: sector_id,
                        status: status,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(d) {
                        reloadTable('#sectorTable');
                        showSuccess(d.message);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        showError('Failed to update status');
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $("#addThisFormContainer").hide();
            $("#newBtn").click(function() {
                clearform();
                $("#newBtn").hide(100);
                $("#addThisFormContainer").show(300);

            });
            $("#FormCloseBtn").click(function() {
                $("#addThisFormContainer").hide(200);
                $("#newBtn").show(100);
                clearform();
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            //
            var url = "{{ URL::to('/admin/sector') }}";
            var upurl = "{{ URL::to('/admin/sector-update') }}";

            $("#addBtn").click(function() {

                //create
                if ($(this).val() == 'Create') {
                    var form_data = new FormData();
                    form_data.append("name", $("#name").val());
                    form_data.append("description", $("#description").val());
                    form_data.append("meta_title", $("#meta_title").val());
                    form_data.append("meta_description", $("#meta_description").val());
                    form_data.append("meta_keywords", $("#meta_keywords").val());

                    var featureImgInput = document.getElementById('image');
                    if (featureImgInput.files && featureImgInput.files[0]) {
                        form_data.append("image", featureImgInput.files[0]);
                    }

                    $.ajax({
                        url: url,
                        method: "POST",
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function(d) {
                            showSuccess(d.message);
                            $("#addThisFormContainer").slideUp(300);
                            setTimeout(() => {
                                $("#newBtn").show(200);
                            }, 300);
                            reloadTable('#sectorTable');
                            clearform();
                        },
                        error: function(xhr, status, error) {
                            if (xhr.status === 422) {
                                let firstError = Object.values(xhr.responseJSON.errors)[0][0];
                                showError(firstError);
                            } else {
                                showError(xhr.responseJSON?.message ?? "Something went wrong!");
                            }
                            console.error(xhr.responseText);
                        }
                    });
                }
                //create  end

                //Update
                if ($(this).val() == 'Update') {
                    var form_data = new FormData();
                    form_data.append("name", $("#name").val());
                    form_data.append("description", $("#description").val());
                    form_data.append("meta_title", $("#meta_title").val());
                    form_data.append("meta_description", $("#meta_description").val());
                    form_data.append("meta_keywords", $("#meta_keywords").val());

                    var featureImgInput = document.getElementById('image');
                    if (featureImgInput.files && featureImgInput.files[0]) {
                        form_data.append("image", featureImgInput.files[0]);
                    }

                    form_data.append("codeid", $("#codeid").val());

                    $.ajax({
                        url: upurl,
                        type: "POST",
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function(d) {
                            showSuccess(d.message);
                            $("#addThisFormContainer").hide();
                            $("#addThisFormContainer").slideUp(300);
                            setTimeout(() => {
                                $("#newBtn").show(200);
                            }, 300);
                            reloadTable('#sectorTable');
                            clearform();
                        },
                        error: function(xhr, status, error) {
                            if (xhr.status === 422) {
                                let firstError = Object.values(xhr.responseJSON.errors)[0][0];
                                showError(firstError);
                            } else {
                                showError(xhr.responseJSON?.message ?? "Something went wrong!");
                            }
                            console.error(xhr.responseText);
                        }
                    });
                }
                //Update  end
            });
            //Edit
            $("#contentContainer").on('click', '#EditBtn', function() {
                $("#cardTitle").text('Update this data');
                codeid = $(this).attr('rid');
                info_url = url + '/' + codeid + '/edit';
                $.get(info_url, {}, function(d) {
                    populateForm(d);
                    pagetop();
                });
            });
            //Edit  end 
            function populateForm(data) {
                $("#name").val(data.name);
                $("#description").val(data.description);
                $("#meta_title").val(data.meta_title);
                $("#meta_description").val(data.meta_description);
                $("#meta_keywords").val(data.meta_keywords);
                $("#codeid").val(data.id);
                $("#addBtn").val('Update');
                $("#addBtn").html('Update');
                $("#addThisFormContainer").show(300);
                $("#newBtn").hide(100);

                var featureImagePreview = document.getElementById('preview-image');
                if (data.image) {
                    featureImagePreview.src = '/images/sector/' + data.image;
                } else {
                    featureImagePreview.src = "#";
                }

            }

            function clearform() {
                $('#createThisForm')[0].reset();
                $("#addBtn").val('Create');
                $("#addBtn").html('Create');
                $('#preview-image').attr('src', '#');
                $("#cardTitle").text('Add new Sector');
            }
        });
    </script>

@endsection