@extends('layouts.header')

@section('content')
<section class="content-header">
    <h1>
        Department
    </h1>
    <ol class="breadcrumb">
        <li>Settings</li>
        <li>Department</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-user"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Total Department</span>
                    <span class="info-box-number">0</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-user-plus"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Total Active</span>
                    <span class="info-box-number">0</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-user-times"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Total Inactive</span>
                    <span class="info-box-number">0</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-header">
                    {{-- <button class="btn btn-primary" type="button" id="addUserBtn">
                        <i class="fa fa-plus"></i>
                        Add Users
                    </button> --}}
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-hover" id="departmentTable">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('js')
<script>
    $(document).ready(function() {
        $(".select2").select2()

        var departmentTable = $('#departmentTable').DataTable({
            paging: true,
            lengthChange: true,
            ordering: false,
            info: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            stateSave:true,
            ajax: {
                type: "POST",
                url: "{{ url('get_department') }}",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            language: {
                processing: "⏳ Loading data, please wait..."
            },
            columns: [
                { data: 'action' },
                { data: 'code' },
                { data: 'name' },
                { data: 'status' }
            ],
            // rowCallback: function (row, data) {
            //     $(row).find("#editUserBtn").on('click', function() {
            //         $("#editUser").modal('show')
                    
            //         $("#editDepartment").val($(this).data('department')).trigger('change')
            //         $("#editName").val(data.name)
            //         $("#editEmail").val(data.email)
            //         $("#editPosition").val(data.position)
            //         $("#editRole").val($(this).data('role')).trigger('change')
            //         $("#editSubsidiary").val($(this).data('subsidiary')).trigger('change')
            //         $("#editUserId").val(data.id)
            //     }),
            //     $(row).find("#deactivateBtn").on('click', function() {
            //         var id = data.id

            //         $.ajax({
            //             type:"POST",
            //             url: "{{ url('deactivate_user') }}",
            //             data: {
            //                 user_id: id,
            //                 _token:"{{ csrf_token() }}"
            //             },
            //             success: function(res) {
                            
            //                 $.toast({
            //                     heading: 'Success',
            //                     text: res.message,
            //                     position: 'top-right',
            //                     stack: false,
            //                     icon: 'success'
            //                 })

            //                 userTable.ajax.reload()
            //             }
            //         })
            //     }),
            //     $(row).find("#activateBtn").on('click', function() {
            //         var id = data.id

            //         $.ajax({
            //             type:"POST",
            //             url: "{{ url('activate_user') }}",
            //             data: {
            //                 user_id: id,
            //                 _token:"{{ csrf_token() }}"
            //             },
            //             success: function(res) {
            //                 $.toast({
            //                     heading: 'Success',
            //                     text: res.message,
            //                     position: 'top-right',
            //                     stack: false,
            //                     icon: 'success'
            //                 })

            //                 userTable.ajax.reload()
            //             }
            //         })
            //     })
            // }
        })
        
        // $("#addUserBtn").on('click', function() {
        //     $('#newUser').modal('show')
        // })

        // $("#addUserForm").on('submit', function(e) {
        //     e.preventDefault()

        //     var formData = $(this).serializeArray()
            
        //     $.ajax({
        //         type: "POST",
        //         url: "{{ url('store_users') }}",
        //         data: formData,
        //         success: function(res) {
        //             if (res.error == 500) {
        //                 $.toast({
        //                     heading: 'Error',
        //                     text: res.error,
        //                     position: 'top-right',
        //                     stack: false,
        //                     icon: 'success'
        //                 })
        //             }
        //             else {
        //                 $.toast({
        //                     heading: 'Success',
        //                     text: res.message,
        //                     position: 'top-right',
        //                     stack: false,
        //                     icon: 'success'
        //                 })

        //                 $("#newUser").modal('hide')

        //                 userTable.ajax.reload()
        //             }
        //         }
        //     })
        // })

        // $("#updateUserForm").on('submit', function(e) {
        //     e.preventDefault()

        //     var formData = $(this).serializeArray()
            
        //     $.ajax({
        //         type: "POST",
        //         url: "{{ url('update_users') }}",
        //         data: formData,
        //         success: function(res) {
        //             if (res.error == 500) {
        //                 $.toast({
        //                     heading: 'Error',
        //                     text: res.error,
        //                     position: 'top-right',
        //                     stack: false,
        //                     icon: 'error'
        //                 })
        //             }
        //             else {
        //                 $.toast({
        //                     heading: 'Success',
        //                     text: res.message,
        //                     position: 'top-right',
        //                     stack: false,
        //                     icon: 'success'
        //                 })

        //                 $("#editUser").modal('hide')

        //                 userTable.ajax.reload()
        //             }
        //         }
        //     })
        // })
    })
</script>
@endsection