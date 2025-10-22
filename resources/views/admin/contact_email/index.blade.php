@extends('admin.pages.master')
@section('title', 'Contact Mails')
@section('content')

    <div class="container-fluid mb-3" id="newBtnSection">
        <button class="btn btn-primary" id="newBtn">Add New Contact Mail</button>
    </div>

    <div class="container-fluid" id="addThisFormContainer" style="display:none;">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 id="cardTitle">Add New Contact Mail</h4>
                    </div>
                    <div class="card-body">
                        <form id="createThisForm">
                            @csrf
                            <input type="hidden" name="id" id="codeid">
                            <div class="mb-3">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Email Holder <span class="text-danger">*</span></label>
                                <input type="text" name="email_holder" id="email_holder" class="form-control">
                            </div>
                            <div class="text-end">
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
                <h4 class="card-title mb-0">All Contact Mails</h4>
            </div>
            <div class="card-body">
                <table id="contactTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Email</th>
                            <th>Email Holder</th>
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

            var table = $('#contactTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('contactemails.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'email_holder',
                        name: 'email_holder'
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
                $('#cardTitle').text('Add New Contact Mail');
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
                var url = $(btn).val() === 'Create' ? "{{ route('contactemails.store') }}" :
                    "{{ route('contactemails.update') }}";
                var fd = new FormData(document.getElementById('createThisForm'));
                if ($(btn).val() !== 'Create') fd.append('id', $('#codeid').val());

                $.ajax({
                    url: url,
                    method: 'POST',
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
                        showError(xhr.responseJSON?.message ?? 'Error');
                    }
                });
            });

            $(document).on('click', '.editBtn', function() {
                var id = $(this).data('id');
                $.get("{{ url('/admin/contact-emails') }}/" + id + "/edit", {}, function(res) {
                    $('#codeid').val(res.id);
                    $('#email').val(res.email);
                    $('#email_holder').val(res.email_holder);
                    $('#cardTitle').text('Update Contact Mail');
                    $('#addBtn').val('Update').text('Update');
                    $('#addThisFormContainer').show(300);
                    $('#newBtn').hide();
                });
            });
        });
    </script>
@endsection
