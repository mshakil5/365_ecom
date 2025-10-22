@extends('admin.pages.master')
@section('title', 'API Products List')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title mb-0">API Products List</h4>
                <p class="text-muted mb-0">Data is automatically synced from the API source.</p>
            </div>
            <button id="syncBtn" class="btn btn-primary"><i class="fas fa-sync"></i> Sync from API</button>
        </div>

        <div class="card-body">
            <table id="apiProductTable" class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Category</th>
                        <th>EAN</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Country</th>
                        <th>Image</th>
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
            <h5 class="fs-16 mt-2">Importing Products</h5>
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
$(document).ready(function(){
    let table = $('#apiProductTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('allApiProducts') }}",
        columns: [
            {data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false},
            {data:'product_code_api', name:'product_code_api'},
            {data:'product_name_api', name:'product_name_api'},
            {data:'company', name:'company'},
            {data:'category_api', name:'category_api'},
            {data:'ean', name:'ean'},
            {data:'price_single', name:'price_single', orderable:false, searchable:false},
            {data:'quantity_api', name:'quantity_api', searchable:false},
            {data:'country_of_origin', name:'country_of_origin'},
            {data:'image', name:'image', orderable:false, searchable:false},
        ]
    });

    $('#syncBtn').on('click', function(){
        let syncButton = $(this);
        syncButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Syncing...');
        $('#syncModal').modal('show');

        let progressBar = $('#modalProgressBar');
        let progressText = $('#modalProgressText');

        progressBar.css('width', '0%').text('0%')
                  .removeClass('bg-success bg-danger')
                  .addClass('progress-bar-animated progress-bar-striped');
        progressText.text('Starting sync...');

        $.post("{{ route('sync.products') }}", {_token: "{{ csrf_token() }}"}, function(res){
            if(!res.success) return alert('Failed to fetch API data');

            let logId = res.log_id;

            function importNextChunk() {
                $.ajax({
                    url: "{{ url('admin/import-chunk') }}/" + logId,
                    method: 'GET',
                    success: function(data){
                        if(data.done){
                            progressBar.css('width', '100%').text('100%')
                                      .removeClass('progress-bar-animated progress-bar-striped')
                                      .addClass('bg-success');
                            progressText.text(`Sync Complete! ${data.synced || data.total || 0} products imported.`);

                            syncButton.prop('disabled', false).html('<i class="fas fa-sync"></i> Sync from API');
                            $('#apiProductTable').DataTable().ajax.reload(null,false);

                            setTimeout(() => $('#syncModal').modal('hide'), 1500);

                            if(typeof showSuccess === 'function') showSuccess(`Imported Successfully!`);
                        } else {
                            let percent = data.total > 0 ? Math.round((data.synced/data.total)*100) : 0;
                            progressBar.css('width', percent+'%').text(percent+'%');
                            progressText.text(`Imported ${data.synced} / ${data.total} products...`);

                            setTimeout(importNextChunk, 200);
                        }
                    },
                    error: function(xhr){
                        console.error('Import Chunk Error:', xhr.responseText);
                        progressText.text('An error occurred.');
                        syncButton.prop('disabled', false).html('<i class="fas fa-sync"></i> Sync from API');
                    }
                });
            }

            importNextChunk();
        });
    });

});
</script>
@endsection