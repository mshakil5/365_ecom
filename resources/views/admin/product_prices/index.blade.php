@extends('admin.pages.master')
@section('title', 'Product Prices')
@section('content')

<div class="container-fluid mb-3">
    @if($selectedProduct)
      <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
    @endif
    <button class="btn btn-primary" id="newBtn">Add New Price</button>
</div>


<div class="container-fluid" id="addThisFormContainer" style="display:none;">
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header"><h4 id="cardTitle">Add New Product Price</h4></div>
                <div class="card-body">
                    <form id="createThisForm">
                        @csrf
                        <input type="hidden" id="codeid" name="id">

                        <div class="mb-3">
                            <label>Product <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="product_id" id="product_id">
                                <option value="">Select Product</option>
                                @if($selectedProduct)
                                    <option value="{{ $selectedProduct->id }}" selected>{{ $selectedProduct->name }}</option>
                                @else
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Category <span class="text-danger">*</span></label>
                            <select class="form-control" name="category" id="category">
                                <option value="">Select Category</option>
                                <option value="Blank pricing">Blank pricing</option>
                                <option value="Print">Print</option>
                                <option value="Embroidery">Embroidery</option>
                                <option value="High stitch count">High stitch count</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Min-Max Qty</label>
                            <input type="text" name="min_max_qty" id="min_max_qty" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Discount Percent</label>
                            <input type="number" name="discount_percent" id="discount_percent" class="form-control" min="0" max="100">
                        </div>

                        <div class="mb-3 text-end">
                            <button type="button" id="addBtn" class="btn btn-primary">Create</button>
                            <button type="button" id="FormCloseBtn" class="btn btn-light">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Product Prices 
                @if(!is_null($selectedProduct))
                    - {{ $selectedProduct?->name }}
                @endif
            </h4>
        </div>
        <div class="card-body">
            <table id="priceTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Min-Max Qty</th>
                        <th>Discount %</th>
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
$(function(){
    $.ajaxSetup({ headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var table = $('#priceTable').DataTable({
        processing:true, serverSide:true, pageLength:25,
        ajax: "{{ route('product_prices.index') }}" + window.location.search,
        columns:[
            {data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false},
            {data:'product', name:'product'},
            {data:'category', name:'category'},
            {data:'min_max_qty', name:'min_max_qty'},
            {data:'discount_percent', name:'discount_percent'},
            {data:'status', name:'status', orderable:false, searchable:false},
            {data:'action', name:'action', orderable:false, searchable:false},
        ]
    });

    $('#newBtn').click(function(){
        $('#createThisForm')[0].reset();
        $('#codeid').val('');
        $('#cardTitle').text('Add New Product Price');
        $('#product_id').val(null).trigger('change');
        $('#addBtn').val('Create').text('Create');
        $('#addThisFormContainer').show(300);
        $('#newBtn').hide();
    });

    $('#FormCloseBtn').click(function(){
        $('#addThisFormContainer').hide(200);
        $('#newBtn').show();
        $('#createThisForm')[0].reset();
    });

    $('#addBtn').click(function(){
        var btn = this;
        var url = $(btn).val() === 'Create' ? "{{ route('product_prices.store') }}" :
                                         "{{ route('product_prices.update') }}";
        var form = document.getElementById('createThisForm');
        var fd = new FormData(form);
        if($(btn).val() !== 'Create') fd.append('id', $('#codeid').val());

        $.ajax({
            url:url, method:"POST", data:fd, contentType:false, processData:false,
            success:function(res){
                showSuccess(res.message);
                $('#addThisFormContainer').hide();
                $('#newBtn').show();
                table.ajax.reload(null,false);
                $('#createThisForm')[0].reset();
            },
            error:function(xhr){
                if(xhr.status===422 && xhr.responseJSON){
                    let first = Object.values(xhr.responseJSON.errors)[0][0];
                    showError(first);
                } else { showError(xhr.responseJSON?.message ?? 'Something went wrong'); }
            }
        });
    });

    $(document).on('click','.EditBtn', function(){
        var id = $(this).data('id');
        $.get("{{ url('/admin/product-price') }}/"+id+"/edit", function(res){
            $('#codeid').val(res.id);
            $('#product_id').val(res.product_id).trigger('change');
            $('#category').val(res.category);
            $('#min_max_qty').val(res.min_max_qty);
            $('#discount_percent').val(res.discount_percent);
            $('#cardTitle').text('Update Product Price');
            $('#addBtn').val('Update').text('Update');
            $('#addThisFormContainer').show(300);
            $('#newBtn').hide();
        });
    });

    $(document).on('change','.toggle-status', function(){
        var id = $(this).data('id');
        var status = $(this).prop('checked')?1:0;
        $.post("{{ route('product_prices.toggleStatus') }}",{id:id,status:status},function(res){
            showSuccess(res.message);
            table.ajax.reload(null,false);
        }).fail(function(xhr){ showError(xhr.responseJSON?.message ?? 'Failed'); table.ajax.reload(null,false); });
    });
});
</script>
@endsection