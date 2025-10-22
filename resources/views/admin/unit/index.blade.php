@extends('admin.pages.master')
@section('title', 'Unit')
@section('content')
    <div class="container-fluid" id="newBtnSection">
        <div class="row mb-3">
            <div class="col-auto">
                <button class="btn btn-primary" id="newBtn">Add New Unit</button>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="addThisFormContainer" style="display:none;">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 id="cardTitle">Add New Unit</h4>
                    </div>
                    <div class="card-body">
                        <form id="createThisForm">
                            @csrf
                            <input type="hidden" id="codeid" name="id">
                            <div class="mb-3">
                                <label class="form-label">Unit Name <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" class="form-control">
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
                <h4 class="card-title mb-0">Units</h4>
            </div>
            <div class="card-body">
                <table id="unitTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Name</th>
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

            var table = $('#unitTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('units.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
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
                $('#cardTitle').text('Add New Unit');
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
                var url = $(btn).val() === 'Create' ? "{{ route('units.store') }}" :
                    "{{ route('units.update') }}";
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
                $.get("{{ url('/admin/unit') }}/" + id + "/edit", {}, function(res) {
                    $('#codeid').val(res.id);
                    $('#name').val(res.name);
                    $('#cardTitle').text('Update Unit');
                    $('#addBtn').val('Update').text('Update');
                    $('#addThisFormContainer').show(300);
                    $('#newBtn').hide();
                });
            });

            $(document).on('change', '.toggle-status', function() {
                var id = $(this).data('id');
                var status = $(this).prop('checked') ? 1 : 0;
                $.post("{{ route('units.toggleStatus') }}", {
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
