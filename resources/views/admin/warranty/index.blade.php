@extends('admin.pages.master')
@section('title', 'Warranty')
@section('content')

    <div class="container-fluid" id="newBtnSection">
        <div class="row mb-3">
            <div class="col-auto">
                <button class="btn btn-primary" id="newBtn">Add New Warranty</button>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="addThisFormContainer" style="display:none;">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 id="cardTitle">Add New Warranty</h4>
                    </div>
                    <div class="card-body">
                        <form id="createThisForm">
                            @csrf
                            <input type="hidden" id="codeid" name="id">
                            <div class="mb-3">
                                <label class="form-label">Warranty Duration <span class="text-danger">*</span></label>
                                <input type="text" id="warranty_duration" name="warranty_duration" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Price Increase Percent (%) <span
                                        class="text-danger">*</span></label>
                                <input type="number" step="0.01" id="price_increase_percent"
                                    name="price_increase_percent" class="form-control">
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
                <h4 class="card-title mb-0">Warranties</h4>
            </div>
            <div class="card-body">
                <table id="warrantyTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Warranty Duration</th>
                            <th>Price Increase (%)</th>
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
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#warrantyTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('warranties.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'warranty_duration',
                        name: 'warranty_duration'
                    },
                    {
                        data: 'price_increase_percent',
                        name: 'price_increase_percent'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#newBtn').click(function() {
                $('#createThisForm')[0].reset();
                $('#codeid').val('');
                $('#cardTitle').text('Add New Warranty');
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
                var btn = this;
                var url = $(btn).val() === 'Create' ? "{{ route('warranties.store') }}" :
                    "{{ route('warranties.update') }}";
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
                        table.ajax.reload(null, false);
                        $('#createThisForm')[0].reset();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON.errors) {
                            let first = Object.values(xhr.responseJSON.errors)[0][0];
                            showError(first);
                        } else showError(xhr.responseJSON?.message ?? 'Error');
                    }
                });
            });

            $(document).on('click', '.EditBtn', function() {
                var id = $(this).data('id');
                $.get("{{ url('/admin/warranty') }}/" + id + "/edit", {}, function(res) {
                    $('#codeid').val(res.id);
                    $('#warranty_duration').val(res.warranty_duration);
                    $('#price_increase_percent').val(res.price_increase_percent);
                    $('#cardTitle').text('Update Warranty');
                    $('#addBtn').val('Update').text('Update');
                    $('#addThisFormContainer').show(300);
                    $('#newBtn').hide();
                });
            });

            $(document).on('change', '.toggle-status', function() {
                var id = $(this).data('id');
                var status = $(this).prop('checked') ? 1 : 0;
                $.post("{{ route('warranties.toggleStatus') }}", {
                    id: id,
                    status: status
                }, function(res) {
                    showSuccess(res.message);
                    table.ajax.reload(null, false);
                }).fail(function() {
                    showError('Failed');
                });
            });
        });
    </script>
@endsection
