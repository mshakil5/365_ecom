@extends('admin.pages.master')
@section('title', 'Colors')
@section('content')

<div class="container-fluid mb-3" id="newBtnSection">
    <button class="btn btn-primary" id="newBtn">Add New Color</button>
</div>

<div class="container-fluid" id="addThisFormContainer" style="display:none;">
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h4 id="cardTitle">Add New Color</h4>
                </div>
                <div class="card-body">
                    <form id="createThisForm">
                        @csrf
                        <input type="hidden" id="codeid" name="id">
                        <div class="mb-3">
                            <label class="form-label">Color Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Color Code</label>
                            <input type="text" class="form-control" id="code" name="code">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pantone</label>
                            <input type="text" class="form-control" id="pantone" name="pantone">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hex</label>
                            <input type="text" class="form-control" id="hex" name="hex">
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
            <h4 class="card-title mb-0">Colors</h4>
        </div>
        <div class="card-body">
            <table id="colorTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Pantone</th>
                        <th>Hex</th>
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
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var table = $('#colorTable').DataTable({
        processing: true,
        serverSide: true,
            ajax: {
        url: "{{ route('api_products.index') }}",
        type: 'GET',
        error: function(xhr, status, error) {
            console.error('DataTables Ajax Error:', status, error);
            console.log(xhr.responseText); // shows full server response
        }
    },
        columns: [
            {data: 'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false},
            {data: 'name', name:'name'},
            {data: 'code', name:'code'},
            {data: 'pantone', name:'pantone'},
            {data: 'hex', name:'hex'},
            {data: 'status', name:'status', orderable:false, searchable:false},
            {data: 'action', name:'action', orderable:false, searchable:false},
        ]
    });

    // Show form
    $('#newBtn').click(function() {
        $('#createThisForm')[0].reset();
        $('#codeid').val('');
        $('#cardTitle').text('Add New Color');
        $('#addBtn').val('Create').text('Create');
        $('#addThisFormContainer').show(300);
        $('#newBtn').hide();
    });

    // Hide form
    $('#FormCloseBtn').click(function() {
        $('#addThisFormContainer').hide(200);
        $('#newBtn').show(100);
        $('#createThisForm')[0].reset();
    });

    // Create / Update
    $('#addBtn').click(function() {
        var btn = this;
        var url = $(btn).val() === 'Create' ? "{{ route('colors.store') }}" :
                  "{{ route('colors.update') }}";
        var fd = new FormData(document.getElementById('createThisForm'));
        if ($(btn).val() !== 'Create') fd.append('id', $('#codeid').val());

        $.ajax({
            url: url,
            method: "POST",
            data: fd,
            contentType: false,
            processData: false,
            success: function(res) {
                showSuccess(res.message);
                $('#addThisFormContainer').hide();
                $('#newBtn').show();
                table.ajax.reload(null,false);
                $('#createThisForm')[0].reset();
            },
            error: function(xhr){ showError(xhr.responseJSON?.message ?? 'Error'); }
        });
    });

    // Edit
    $(document).on('click','.EditBtn',function(){
        var id = $(this).data('id');
        $.get("{{ url('/admin/color') }}/"+id+"/edit", function(res){
            $('#codeid').val(res.id);
            $('#name').val(res.name);
            $('#code').val(res.code);
            $('#pantone').val(res.pantone);
            $('#hex').val(res.hex);
            $('#cardTitle').text('Update Color');
            $('#addBtn').val('Update').text('Update');
            $('#addThisFormContainer').show(300);
            $('#newBtn').hide();
        });
    });

    // Toggle Status
    $(document).on('change','.toggle-status',function(){
        var id = $(this).data('id');
        var status = $(this).prop('checked') ? 1 : 0;
        $.post("{{ route('colors.toggleStatus') }}",{id:id,status:status},function(res){
            showSuccess(res.message);
            table.ajax.reload(null,false);
        }).fail(function(){ showError('Failed'); });
    });
});
</script>
@endsection