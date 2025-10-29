@extends('admin.pages.master')
@section('title', 'API Product Sources')

@section('content')
<div class="container-fluid mb-3" id="newBtnSection">
    <div class="row">
        <div class="col-auto">
            <button class="btn btn-primary" id="newBtn">Add New API Source</button>
        </div>
    </div>
</div>

<div class="container-fluid" id="addThisFormContainer" style="display:none;">
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header"><h4 id="cardTitle">Add New API Source</h4></div>
                <div class="card-body">
                    <form id="createThisForm">@csrf
                        <input type="hidden" id="codeid" name="id">
                        <div class="mb-3">
                            <label class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" id="company" name="company" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">API URL <span class="text-danger">*</span></label>
                            <input type="text" id="url" name="url" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="3"></textarea>
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
        <div class="card-header"><h4 class="card-title mb-0">API Sources</h4></div>
        <div class="card-body">
            <table id="apiProductTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Company</th>
                        <th>URL</th>
                        <th>Status</th>
                        <th>Last Sync</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="syncModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4 d-flex flex-column align-items-center text-center">
            <lord-icon 
                src="https://cdn.lordicon.com/uetqnvvg.json" 
                trigger="loop"
                colors="primary:#405189,secondary:#0ab39c" 
                style="width:80px;height:80px">
            </lord-icon>
            <h5 class="fs-16 mt-2" id="modalTitle">Importing Products</h5>
            <p class="text-muted mb-1" id="modalProgressText">Starting...</p>
            <div class="progress w-100 mt-2">
                <div class="progress-bar progress-bar-striped progress-bar-animated" id="modalProgressBar" style="width:0%">0%</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var table = $('#apiProductTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('api_products.index') }}",
        columns: [
            {data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false},
            {data:'company', name:'company'},
            {data:'url', name:'url'},
            {data:'status', name:'status', orderable:false, searchable:false},
            {data:'last_sync', name:'last_sync', orderable:false, searchable:false},
            {data:'action', name:'action', orderable:false, searchable:false}
        ]
    });

    // New/Edit Form Handling (your existing code)
    $('#newBtn').click(function() {
        $('#createThisForm')[0].reset();
        $('#codeid').val('');
        $('#cardTitle').text('Add New API Source');
        $('#addBtn').val('Create').text('Create');
        $('#addThisFormContainer').show(300);
        $('#newBtn').hide();
    });

    $('#FormCloseBtn').click(function() {
        $('#addThisFormContainer').hide(200);
        $('#newBtn').show(100);
        $('#createThisForm')[0].reset();
    });

    $('#addBtn').click(function() {
        var url = ($(this).val() === 'Create') ? "{{ route('api_products.store') }}" : "{{ route('api_products.update') }}";
        var fd = new FormData(document.getElementById('createThisForm'));
        if ($(this).val() !== 'Create') fd.append('id', $('#codeid').val());

        $.ajax({
            url, method: "POST", data: fd, contentType: false, processData: false,
            success: function(res){
                showSuccess(res.message);
                $('#addThisFormContainer').hide();
                $('#newBtn').show();
                table.ajax.reload(null,false);
                $('#createThisForm')[0].reset();
            },
            error: function(xhr){
                showError(xhr.responseJSON?.message ?? 'Error');
            }
        });
    });

    $(document).on('click', '.EditBtn', function(){
        var id = $(this).data('id');
        $.get("{{ url('/admin/api-product') }}/" + id + "/edit", {}, function(res){
            $('#codeid').val(res.id);
            $('#company').val(res.company);
            $('#url').val(res.url);
            $('#description').val(res.description);
            $('#cardTitle').text('Update API Source');
            $('#addBtn').val('Update').text('Update');
            $('#addThisFormContainer').show(300);
            $('#newBtn').hide();
        });
    });

    $(document).on('change', '.toggle-status', function(){
        var id = $(this).data('id');
        var status = $(this).prop('checked') ? 1 : 0;
        $.post("{{ route('api_products.toggleStatus') }}", {id:id, status:status}, function(res){
            showSuccess(res.message);
            table.ajax.reload(null,false);
        }).fail(()=>showError('Failed'));
    });

    $(document).on('click', '.importBtn', function() {
        let apiId = $(this).data('id');
        let companyName = $(this).data('company');
        let importButton = $(this);

        $('#modalTitle').text(`Importing from ${companyName}`);
        $('#modalProgressText').text('Importing products...');
        $('#modalProgressBar').css('width', '0%').text('0%');
        $('#syncModal').modal({backdrop: 'static', keyboard: false});
        $('#syncModal').modal('show');

        importButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Importing...');

        $.get("/admin/sync-products?id=" + apiId, function(res) {
            console.log(res);

            $('#modalProgressBar').css('width', '100%').text('100%');
            $('#modalProgressText').text('Import completed successfully!');
            table.ajax.reload(null,false);
            importButton.prop('disabled', false).html('<i class="fas fa-download"></i> Import');

            setTimeout(() => { $('#syncModal').modal('hide'); }, 1500);

            showSuccess(`Products imported successfully from ${companyName}!`);
        }).fail(function(xhr) {
            $('#modalProgressText').text('Failed to import products.');
            importButton.prop('disabled', false).html('<i class="fas fa-download"></i> Import');
            console.log(xhr.responseText);
            showError('Failed to import products.');
        });
    });

});
</script>
@endsection