@extends('layouts.header')

@section('css')
  <link rel="stylesheet" href="{{ asset('plugins/iCheck/all.css') }}">
@endsection

@section('content')
<section class="content-header">
    <h1>
        User Access Module
    </h1>
    <ol class="breadcrumb">
        <li>Settings</li>
        <li>User</li>
        <li>User Access Module</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-6">
            <div class="box box-primary">
                <div class="box-header">
                    <p class="box-title">Access Module</p>
                </div>
                <div class="box-body">
                    <form method="post" action="{{ url('store_user_module_access') }}" id="userAccessModuleForm">
                        @csrf 
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Module</th>
                                    <th>Create
                                        <label>
                                            <input type="checkbox" class="minimal" id="createCheckAll">
                                        </label>
                                    </th>
                                    <th>Read/View
                                        <label>
                                            <input type="checkbox" class="minimal" id="readCheckAll">
                                        </label>
                                    </th>
                                    <th>Update
                                        <label>
                                            <input type="checkbox" class="minimal" id="updateCheckAll">
                                        </label>
                                    </th>
                                    <th>Delete
                                        <label>
                                            <input type="checkbox" class="minimal" id="deleteCheckAll">
                                        </label>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($modules as $module)
                                    <tr>
                                        <td colspan="5"><b>{{ $module->module_name }}</b></td>
                                    </tr>
                                    @foreach ($module->submodule as $submodule)
                                        <tr>
                                            <td><p style="margin-left: 15px;">{{ $submodule->submodule_name }}</p></td>
                                            <td>
                                                <label>
                                                    <input type="checkbox" class="minimal createCheck" name="module_access[{{ $submodule->id }}][create]" >
                                                </label>
                                            </td>
                                            <td>
                                                <label>
                                                    <input type="checkbox" class="minimal readCheck" name="module_access[{{ $submodule->id }}][read]">
                                                </label>
                                            </td>
                                            <td>
                                                <label>
                                                    <input type="checkbox" class="minimal updateCheck" name="module_access[{{ $submodule->id }}][update]">
                                                </label>
                                            </td>
                                            <td>
                                                <label>
                                                    <input type="checkbox" class="minimal deleteCheck" name="module_access[{{ $submodule->id }}][delete]">
                                                </label>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary btn-block">Save</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="box box-primary">
                <div class="box-header">
                    <p class="box-title">Audit Logs</p>
                </div>
                <div class="box-body"></div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('js')
<script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('input[type="checkbox"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
        })
        
        $('#createCheckAll').on('ifChecked', function(){
            $(".createCheck").iCheck('check')
        });
        $('#createCheckAll').on('ifUnchecked', function(){
            $(".createCheck").iCheck('uncheck')
        });

        $('#readCheckAll').on('ifChecked', function(){
            $(".readCheck").iCheck('check')
        });
        $('#readCheckAll').on('ifUnchecked', function(){
            $(".readCheck").iCheck('uncheck')
        });

        $('#updateCheckAll').on('ifChecked', function(){
            $(".updateCheck").iCheck('check')
        });
        $('#updateCheckAll').on('ifUnchecked', function(){
            $(".updateCheck").iCheck('uncheck')
        });

        $('#deleteCheckAll').on('ifChecked', function(){
            $(".deleteCheck").iCheck('check')
        });
        $('#deleteCheckAll').on('ifUnchecked', function(){
            $(".deleteCheck").iCheck('uncheck')
        });

        $("#userAccessModuleForm").on('submit', function(e) {
            e.preventDefault()

            var formData = $(this).serializeArray()
            
            $.ajax({
                type:"POST",
                url:"{{ url('store_user_module_access') }}",
                data: formData,
                success:function(res) {
                    if (res.status == 201) {
                        $.toast({
                            heading: 'Success',
                            text: res.msg,
                            position: 'top-right',
                            stack: false,
                            icon: 'success'
                        })
                    }
                }
            })
        })
    })
</script>
@endsection