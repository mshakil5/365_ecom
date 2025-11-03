@extends('admin.pages.master')
@section('title', 'Orders')

@section('content')
<div class="container-fluid mb-3">
    <div class="btn-group">
        <a href="{{ route('orders.index') }}" class="btn btn-{{ request('status')==null ? 'success':'secondary' }}">All</a>
        <a href="{{ route('orders.index', ['status'=>'pending']) }}" class="btn btn-{{ request('status')=='pending' ? 'success':'secondary' }}">Pending</a>
        <a href="{{ route('orders.index', ['status'=>'processing']) }}" class="btn btn-{{ request('status')=='processing' ? 'success':'secondary' }}">Processing</a>
        <a href="{{ route('orders.index', ['status'=>'completed']) }}" class="btn btn-{{ request('status')=='completed' ? 'success':'secondary' }}">Completed</a>
        <a href="{{ route('orders.index', ['status'=>'cancelled']) }}" class="btn btn-{{ request('status')=='cancelled' ? 'success':'secondary' }}">Cancelled</a>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <table id="orders-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Order Number</th>
                        <th>Customer</th>
                        <th>Total Amount (£)</th>
                        <th>Status</th>
                        <th>Date</th>
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
$(function () {
    $('#orders-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('orders.index') }}"+window.location.search,
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, searchable:false},
            {data: 'invoice', name: 'invoice'},
            {data: 'full_name', name: 'full_name'},
            {data: 'total_amount', name: 'total_amount'},
            {data: 'status', name: 'status', orderable:false, searchable:false},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable:false, searchable:false}
        ]
    });

    $(document).on('change', '.change-status', function() {
        var orderId = $(this).data('id');
        var status = $(this).val();
        var url = '/admin/orders/' + orderId + '/change-status';

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                status: status,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                if(res.success) {
                    showSuccess(res.message);
                    $('#orders-table').DataTable().ajax.reload(null, false);
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    });

});
</script>
@endsection