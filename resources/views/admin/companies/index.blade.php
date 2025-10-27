@extends('admin.pages.master')
@section('title', 'Company')
@section('content')

<div class="container-fluid" id="newBtnSection">
    <div class="row mb-3">
        <div class="col-auto">
            <button class="btn btn-primary" id="newBtn">Add New Company</button>
        </div>
    </div>
</div>

<div class="container-fluid" id="addThisFormContainer" style="display:none;">
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h4 id="cardTitle">Add New Company</h4>
                </div>
                <div class="card-body">
                    <form id="createThisForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="codeid" name="id">
                        <div class="mb-3">
                            <label class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" id="image" name="image" class="form-control" accept="image/*"
                                onchange="previewImage(event, '#preview-image')">
                            <img id="preview-image" src="#" alt="" class="img-thumbnail rounded mt-3"
                                style="max-width: 300px; display: none;">
                        </div>

                        <div class="mb-3 text-end">
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
            <h4 class="card-title mb-0">Companies</h4>
        </div>
        <div class="card-body">
            <table id="companyTable" class="table table-bordered table-striped">
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
@endsection

@section('script')
<script>
$(function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    var table = $('#companyTable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        ajax: "{{ route('companies.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'image', name: 'image', orderable: false, searchable: false},
            {data: 'status', name: 'status', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ]
    });

    function previewImage(event, selector){
        let output = $(selector);
        output.attr('src', URL.createObjectURL(event.target.files[0])).show();
    }

    $('#newBtn').click(function() {
        $('#createThisForm')[0].reset();
        $('#codeid').val('');
        $('#cardTitle').text('Add New Company');
        $('#preview-image').hide();
        $('#addBtn').val('Create').text('Create');
        $('#addThisFormContainer').show(300);
        $('#newBtn').hide();
    });

    $('#FormCloseBtn').click(function() {
        $('#addThisFormContainer').hide(200);
        $('#newBtn').show(100);
        $('#createThisForm')[0].reset();
        $('#preview-image').hide();
    });

    $('#addBtn').click(function() {
        var btn = this;
        var url = $(btn).val() === 'Create' ? "{{ route('companies.store') }}" : "{{ route('companies.update') }}";
        var form = document.getElementById('createThisForm');
        var fd = new FormData(form);
        if ($(btn).val() !== 'Create') fd.append('id', $('#codeid').val());

        $.ajax({
            url: url,
            method: "POST",
            data: fd,
            contentType: false,
            processData: false,
            success: function(res) {
                showSuccess(res.message ?? 'Saved');
                $('#addThisFormContainer').hide();
                $('#newBtn').show();
                table.ajax.reload(null, false);
                $('#createThisForm')[0].reset();
                $('#preview-image').hide();
            },
            error: function(xhr) {
                if (xhr.status === 422 && xhr.responseJSON) {
                    let first = Object.values(xhr.responseJSON.errors)[0][0];
                    showError(first);
                } else {
                    showError(xhr.responseJSON?.message ?? 'Something went wrong');
                }
                console.error(xhr.responseText);
            }
        });
    });

    $(document).on('click', '.EditBtn', function() {
        var id = $(this).data('id');
        $.get("{{ url('/admin/company') }}/" + id + "/edit", {}, function(res) {
            $('#codeid').val(res.id);
            $('#name').val(res.name);
            if (res.image) $('#preview-image').attr('src', '/images/companies/' + res.image).show();
            else $('#preview-image').hide();
            $('#cardTitle').text('Update Company');
            $('#addBtn').val('Update').text('Update');
            $('#addThisFormContainer').show(300);
            $('#newBtn').hide();
        });
    });

    $(document).on('change', '.toggle-status', function() {
        var id = $(this).data('id');
        var status = $(this).prop('checked') ? 1 : 0;
        $.post("{{ route('companies.toggleStatus') }}", {id: id, status: status}, function(res){
            showSuccess(res.message);
            table.ajax.reload(null, false);
        }).fail(function(xhr){
            showError(xhr.responseJSON?.message ?? 'Failed');
            table.ajax.reload(null, false);
        });
    });
});
</script>
@endsection