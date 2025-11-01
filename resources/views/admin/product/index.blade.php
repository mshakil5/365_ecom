@extends('admin.pages.master')
@section('title', 'All Products')

@section('content')

<div class="container-fluid" id="newBtnSection">
  <div class="row mb-3">
      <div class="col-auto">
          <a class="btn btn-primary" href="{{ route('create.product') }}">Create New Product</a>
      </div>
  </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">In House Products</h5>
        </div>
        <div class="card-body">
            <table id="products-table" class="table table-bordered table-striped align-middle">
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
$(function () {
    $('#products-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('products.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'product_code', name: 'product_code'},
            {data: 'name', name: 'name'},
            {data: 'price', name: 'price'},
            {data: 'category_name', name: 'category_name'},
            {data: 'company_name', name: 'company_name'},
            {data: 'feature_image', name: 'feature_image', orderable: false, searchable: false},
            {data: 'prices', name: 'prices'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ]
    });
});
</script>
@endsection