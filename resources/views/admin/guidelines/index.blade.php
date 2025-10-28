@extends('admin.pages.master')
@section('title', 'Guidelines')

@section('content')
    <div class="container-fluid" id="newBtnSection">
        <div class="row mb-3">
            <div class="col-auto">
                <button type="button" class="btn btn-primary" id="newBtn">Add New Guideline</button>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="addThisFormContainer">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1" id="cardTitle">Add New Guideline</h4>
                    </div>
                    <div class="card-body">
                        <form id="createThisForm">@csrf
                            <input type="hidden" id="codeid" name="codeid">
                            <div class="mb-3">
                                <label class="form-label">Position <span class="text-danger">*</span></label>
                                <select name="position" id="position" class="form-control">
                                    <option value="">Select Position</option>
                                    @foreach ($availablePositions as $pos)
                                        <option value="{{ $pos }}">{{ $pos }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Image <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="image" accept="image/*"
                                    onchange="previewImage(event, '#preview-image')">
                                <img id="preview-image" src="#" alt="" class="img-thumbnail mt-3"
                                    style="max-width: 200px;">
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" id="addBtn" class="btn btn-primary" value="Create">Create</button>
                        <button type="button" id="FormCloseBtn" class="btn btn-light">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="contentContainer">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Guidelines List</h4>
            </div>
            <div class="card-body">
                <table id="guidelineTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Position</th>
                            <th>Image</th>
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
        $(document).ready(function() {
            $("#addThisFormContainer").hide();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // DataTable
            var table = $('#guidelineTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('guidelines.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'position',
                        name: 'position'
                    },
                    {
                        data: 'image',
                        name: 'image',
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

            // Show form
            $("#newBtn").click(function() {
                clearForm();

                $.get('/admin/guidelines/available-positions', function(positions) {
                    let options = '<option value="">Select Position</option>';
                    positions.forEach(p => options += `<option value="${p}">${p}</option>`);
                    $("#position").html(options);
                });

                $("#addThisFormContainer").slideDown(300);
                $("#newBtn").hide();
            });

            // Hide form
            $("#FormCloseBtn").click(function() {
                $("#addThisFormContainer").slideUp(300);
                setTimeout(() => $("#newBtn").show(), 300);
            });

            // Create/Update
            $("#addBtn").click(function() {
                var formData = new FormData();
                formData.append('id', $("#codeid").val());

                // Append position for create
                if ($(this).val() === 'Create') {
                    formData.append('position', $("#position").val());
                }

                if ($("#image")[0].files[0]) {
                    formData.append('image', $("#image")[0].files[0]);
                }

                var url = $(this).val() === 'Update' ? '{{ route('guidelines.update') }}' :
                    '{{ route('guidelines.store') }}';

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        showSuccess(res.message);
                        $("#addThisFormContainer").slideUp(300);
                        setTimeout(() => $("#newBtn").show(), 300);
                        clearForm();
                        table.ajax.reload(null, false);
                    },
                    error: function(err) {
                        showError(err.responseJSON?.message || 'Something went wrong!');
                    }
                });
            });

            // Edit
            $(document).on('click', '.EditBtn', function() {
                var id = $(this).data('id');
                $.get('/admin/guidelines/' + id + '/edit', function(data) {
                    $("#codeid").val(data.id);
                    $("#position").html(`<option>${data.position}</option>`).prop('disabled', true);
                    $("#preview-image").attr('src', '/images/guidelines/' + data.image);
                    $("#addBtn").val('Update').html('Update');
                    $("#cardTitle").text('Update Guideline');
                    $("#addThisFormContainer").slideDown(300);
                    $("#newBtn").hide();
                }).fail(() => showError('Failed to load data.'));
            });


            function clearForm() {
                $("#createThisForm")[0].reset();
                $("#preview-image").attr('src', '#');
                $("#codeid").val('');
                $("#position").prop('disabled', false);
                $("#addBtn").val('Create').html('Create');
                $("#cardTitle").text('Add New Guideline');
            }
        });
    </script>
@endsection
