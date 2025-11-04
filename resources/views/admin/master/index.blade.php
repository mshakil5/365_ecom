@extends('admin.pages.master')
@section('title', 'Master')

@section('content')
    <div class="container-fluid mb-3" id="newBtnSection">
        <button class="btn btn-primary" id="newBtn">Add New</button>
    </div>

    <div class="container-fluid" id="addThisFormContainer" style="display:none;">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="card">
                    <div class="card-header">
                        <h4 id="cardTitle">Add New</h4>
                    </div>
                    <div class="card-body">
                        <form id="createThisForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="codeid" name="id">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Short Title</label>
                                    <input type="text" class="form-control" id="short_title" name="short_title">
                                </div>
                                <div class="col-12 mb-3">
                                    <label>Long Title</label>
                                    <input type="text" class="form-control" id="long_title" name="long_title">
                                </div>
                                <div class="col-12 mb-3">
                                    <label>Short Description</label>
                                    <textarea class="form-control summernote" id="short_description" name="short_description"></textarea>
                                </div>
                                <div class="col-12 mb-3">
                                    <label>Long Description</label>
                                    <textarea class="form-control summernote" id="long_description" name="long_description"></textarea>
                                </div>
                                <div class="col-12 mb-3">
                                    <label>Meta Title</label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title">
                                </div>
                                <div class="col-12 mb-3">
                                    <label>Meta Description</label>
                                    <textarea class="form-control summernote" id="meta_description" name="meta_description"></textarea>
                                </div>
                                <div class="col-12 mb-3">
                                    <label>Meta Keywords</label>
                                    <input type="text" class="form-control" id="meta_keywords" name="meta_keywords">
                                </div>
                                <div class="col-12 mb-3">
                                    <label>Meta Image</label>
                                    <input type="file" class="form-control" id="meta_image" name="meta_image"
                                        onchange="previewImage(event, '#meta_image_preview')">
                                    <img id="meta_image_preview" src="#" class="img-thumbnail mt-3">
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="button" id="addBtn" class="btn btn-primary" value="Create">Create</button>
                                <button type="button" id="FormCloseBtn" class="btn btn-light">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="contentContainer">
        <div class="card">
            <div class="card-header">
                <h4>All Masters</h4>
            </div>
            <div class="card-body">
                <table id="masterTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Name</th>
                            <th>Short Title</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const table = $('#masterTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('master.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'short_title',
                        name: 'short_title'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            let editors = new Map();

            $('#newBtn').click(function() {
                $('#createThisForm')[0].reset();
                $('#codeid').val('');
                $('#cardTitle').text('Add New');
                $('#addBtn').val('Create').text('Create');
                $('#addThisFormContainer').show(300);
                $('#newBtn').hide();
            });

            $('#FormCloseBtn').click(function() {
                $('#addThisFormContainer').hide(200);
                $('#newBtn').show(100);
                $('#createThisForm')[0].reset();
                $('#meta_image_preview').hide();
                $('#name').prop('readonly', false);
            });

            $('#addBtn').click(function() {
                editors.forEach((editor, id) => {
                    document.getElementById(id).value = editor.getData();
                });

                var btn = this;
                var url = $(btn).val() === 'Create' ? "{{ route('master.store') }}" :
                    "{{ route('master.update') }}";
                var form = document.getElementById('createThisForm');
                var fd = new FormData(form);
                if ($(btn).val() !== 'Create') fd.append('id', $('#codeid').val());

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        showSuccess(res.message);
                        $('#addThisFormContainer').hide();
                        $('#newBtn').show();
                        table.ajax.reload(null, false);
                        $('#createThisForm')[0].reset();
                        $('#meta_image_preview').hide();
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

            $(document).on('click', '.EditBtn', function() {
                var id = $(this).data('id');
                $.get("{{ url('/admin/master') }}/" + id + "/edit", {}, function(res) {
                    $('#codeid').val(res.id);
                    $('#name').val(res.name).prop('readonly', true);
                    $('#short_title').val(res.short_title);
                    $('#long_title').val(res.long_title);
                    $('#meta_title').val(res.meta_title);
                    $('#meta_keywords').val(res.meta_keywords);

                    if (res.meta_image) {
                        $('#meta_image_preview').attr('src', '/images/meta_image/' + res.meta_image)
                            .show();
                    } else {
                        $('#meta_image_preview').hide();
                    }

                    if (editors.has('short_description')) editors.get('short_description').setData(
                        res.short_description);
                    if (editors.has('long_description')) editors.get('long_description').setData(res
                        .long_description);
                    if (editors.has('meta_description')) editors.get('meta_description').setData(res
                        .meta_description);

                    $('#cardTitle').text('Update');
                    $('#addBtn').val('Update').text('Update');
                    $('#addThisFormContainer').show(300);
                    $('#newBtn').hide();
                });
            });
        });
    </script>
@endsection
