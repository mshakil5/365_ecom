@extends('admin.pages.master')
@section('title', 'API Products List')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title mb-0">API Products List</h4>
            </div>
        </div>

        <div class="card-body">
            <table id="apiProductTable" class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Price(£)</th>
                        <th>Category</th>
                        <th>Company</th>
                        <th>Image</th>
                        <th>Prices</th>
                        <th>More</th>
                    </tr>
                </thead>
            </table>
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
            {data:'product_code', name:'product_code'},
            {data:'name', name:'name'},
            {data:'price', name:'price'},
            {data:'category', name:'category'},
            {data:'company', name:'company'},
            {data:'image', name:'image'},
            {data: 'prices', name: 'prices'},
            {data:'action', name:'action', orderable:false, searchable:false},
        ]
    });
});
</script>
@endsection