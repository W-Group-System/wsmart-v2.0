@extends('layouts.header')

@section('content')
<section class="content-header">
    <h1>
        User
    </h1>
    <ol class="breadcrumb">
        <li>Settings</li>
        <li>User</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <button class="btn btn-primary" type="button" id="addUserBtn">
                        <i class="fa fa-plus"></i>
                        Add Users
                    </button>
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-hover" id="userTable">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Role</th>
                                <th>Subsidiary</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@include('user.new_user')
@include('user.edit_user')
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $(".select2").select2()

        var userTable = $('#userTable').DataTable({
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
                url: "{{ url('get_users') }}",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            language: {
                processing: "⏳ Loading data, please wait..."
            },
            columns: [
                { data: 'action' },
                { data: 'name' },
                { data: 'email' },
                { data: 'department' },
                { data: 'position' },
                { data: 'role' },
                { data: 'subsidiary' },
                { data: 'status' }
            ],
            rowCallback: function (row, data) {
                $(row).find("#editUserBtn").on('click', function() {
                    $("#editUser").modal('show')
                    
                    $("#editDepartment").val($(this).data('department')).trigger('change')
                    $("#editName").val(data.name)
                    $("#editEmail").val(data.email)
                    $("#editPosition").val(data.position)
                    $("#editRole").val($(this).data('role')).trigger('change')
                    $("#editSubsidiary").val($(this).data('subsidiary')).trigger('change')
                    $("#editUserId").val(data.id)
                })
                
            }
        })
        
        $("#addUserBtn").on('click', function() {
            $('#newUser').modal('show')
        })

        $("#addUserForm").on('submit', function(e) {
            e.preventDefault()

            var formData = $(this).serializeArray()
            
            $.ajax({
                type: "POST",
                url: "{{ url('store_users') }}",
                data: formData,
                success: function(res) {
                    if (res.error == 500) {
                        $.toast({
                            heading: 'Error',
                            text: res.error,
                            position: 'top-right',
                            stack: false,
                            icon: 'success'
                        })
                    }
                    else {
                        $.toast({
                            heading: 'Success',
                            text: res.message,
                            position: 'top-right',
                            stack: false,
                            icon: 'success'
                        })

                        $("#newUser").modal('hide')

                        userTable.ajax.reload()
                    }
                }
            })
        })

        $("#updateUserForm").on('submit', function(e) {
            e.preventDefault()

            var formData = $(this).serializeArray()
            
            $.ajax({
                type: "POST",
                url: "{{ url('update_users') }}",
                data: formData,
                success: function(res) {
                    if (res.error == 500) {
                        $.toast({
                            heading: 'Error',
                            text: res.error,
                            position: 'top-right',
                            stack: false,
                            icon: 'success'
                        })
                    }
                    else {
                        $.toast({
                            heading: 'Success',
                            text: res.message,
                            position: 'top-right',
                            stack: false,
                            icon: 'success'
                        })

                        $("#editUser").modal('hide')

                        userTable.ajax.reload()
                    }
                }
            })
        })
    })
</script>
@endsection