@extends('admin.pages.master')
@section('title', 'Product Prices')
@section('content')

    <div class="container-fluid mb-3">
        @if ($selectedProduct)
            <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
        @endif
        <button class="btn btn-primary" id="newBtn">Add / Edit Product Prices</button>
    </div>

    <div class="container-fluid" id="addThisFormContainer" style="display:none;">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h4 id="cardTitle">Manage Product Prices</h4>
                    </div>
                    <div class="card-body">
                        <form id="createThisForm">
                            @csrf
                            <input type="hidden" id="codeid" name="product_id">

                            <div class="mb-3">
                                <label>Product <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="product_id_select" id="product_id_select">
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

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Category</th>
                                            <th>Min Qty</th>
                                            <th>Max Qty</th>
                                            <th>Discount %</th>
                                        </tr>
                                    </thead>
                                    <tbody id="categoryRows">
                                        @foreach (['Blank pricing', 'Print', 'Embroidery', 'High stitch count'] as $cat)
                                            <tr>
                                                <td>{{ $cat }}<input type="hidden" name="category[]"
                                                        value="{{ $cat }}"></td>
                                                <td><input type="number" name="min_qty[]" class="form-control min_qty"
                                                        min="0"></td>
                                                <td><input type="number" name="max_qty[]" class="form-control max_qty"
                                                        min="0"></td>
                                                <td><input type="number" name="discount_percent[]" class="form-control"
                                                        min="0" max="100"></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-end">
                                <button type="button" id="addBtn" class="btn btn-primary">Save</button>
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
                <h4>Product Prices</h4>
            </div>
            <div class="card-body">
                <table id="priceTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Min Qty</th>
                            <th>Max Qty</th>
                            <th>Discount %</th>
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

            const table = $('#priceTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('product_prices.index') }}" + window.location.search,

                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'product'
                    },
                    {
                        data: 'category'
                    },
                    {
                        data: 'min_qty'
                    },
                    {
                        data: 'max_qty'
                    },
                    {
                        data: 'discount_percent'
                    },
                ]
            });

            $('#newBtn').click(() => {
                $('#createThisForm')[0].reset();
                $('#codeid').val('');
                $('#product_id_select').val('').trigger('change');
                $('#addThisFormContainer').show(300);
                $('#newBtn').hide();
            });

            $('#FormCloseBtn').click(() => {
                $('#addThisFormContainer').hide(200);
                $('#newBtn').show();
            });

            $('#product_id_select').on('change', function() {
                const product_id = $(this).val();
                if (!product_id) return;

                $.get("{{ url('/admin/product-price/by-product') }}/" + product_id, function(res) {
                    $('#codeid').val(product_id);
                    $('tbody#categoryRows tr').each(function(i) {
                        let cat = $(this).find('input[name="category[]"]').val();
                        let found = res.find(r => r.category === cat);
                        if (found) {
                            $(this).find('.min_qty').val(found.min_qty);
                            $(this).find('.max_qty').val(found.max_qty);
                            $(this).find('input[name="discount_percent[]"]').val(found
                                .discount_percent);
                        } else {
                            $(this).find('input').not('[name="category[]"]').val('');
                        }
                    });
                });
            });

            $('#addBtn').click(function() {
                const fd = new FormData(document.getElementById('createThisForm'));
                const product_id = $('#product_id_select').val();
                if (!product_id) {
                    showError('Select a product first');
                    return;
                }

                let valid = true;
                $('tbody tr').each(function() {
                    const min = parseInt($(this).find('.min_qty').val()) || 0;
                    const max = parseInt($(this).find('.max_qty').val()) || 0;
                    if (min >= max && max > 0) {
                        showError('Min qty must be less than Max qty');
                        valid = false;
                        return false;
                    }
                });
                if (!valid) return;

                fd.append('product_id', product_id);

                $.ajax({
                    url: "{{ route('product_prices.store') }}",
                    method: "POST",
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        showSuccess(res.message);
                        $('#addThisFormContainer').hide();
                        $('#newBtn').show();
                        table.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.message ?? 'Error occurred';
                        if (xhr.status === 422 && xhr.responseJSON.errors) {
                            msg = Object.values(xhr.responseJSON.errors)[0][0];
                        }
                        showError(msg);
                    }
                });
            });
        });
    </script>
@endsection