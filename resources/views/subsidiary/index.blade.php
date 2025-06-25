@extends('layouts.header')

@section('content')
<section class="content-header">
    <h1>
        Subsidiary
    </h1>
    <ol class="breadcrumb">
        <li>Settings</li>
        <li>Subsidiary</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-building-o"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Total Subsidiary</span>
                    <span class="info-box-number">0</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-building-o"></i></span>

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
                <span class="info-box-icon bg-red"><i class="fa fa-building-o"></i></span>

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
                    <button class="btn btn-primary" type="button" id="addBtn">
                        <i class="fa fa-plus"></i>
                        Add Subsidiary
                    </button>
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-hover" id="subsidiaryTable">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Subsidiary Name</th>
                                <th>Address</th>
                                <th>Shipping Address</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@include('subsidiary.new_subsidiary')
@include('subsidiary.edit_subsidiary')
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $(".select2").select2()

        var subsidiaryTable = $('#subsidiaryTable').DataTable({
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
                url: "{{ url('get-subsidiary') }}",
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
                { data: 'address' },
                { data: 'shipping_address' },
                { data: 'status' }
            ],
            rowCallback: function (row, data) {
                $(row).find("#editBtn").on('click', function() {
                    $("#editSubsidiary").modal('show')
                    
                    $("#editName").val(data.name)
                    $("#editAddress").val(data.address)
                    $("#editShippingAddress").val(data.shipping_address)
                    $("#subsidiaryId").val(data.id)
                }),
                $(row).find("#deactivateBtn").on('click', function() {
                    var id = data.id

                    $.confirm({
                        title: 'Deactivate!',
                        content: 'Are you sure you want to deactivate this subsidiary?',
                        theme: 'material',
                        buttons: {
                            confirm: function () {
                                $.ajax({
                                    type:"POST",
                                    url: "{{ url('deactivate-subsidiary') }}",
                                    data: {
                                        subsidiary_id: id,
                                        _token:"{{ csrf_token() }}"
                                    },
                                    success: function(res) {
                                        
                                        $.toast({
                                            heading: 'Success',
                                            text: res.msg,
                                            position: 'top-right',
                                            stack: false,
                                            icon: 'success'
                                        })

                                        subsidiaryTable.ajax.reload()
                                    }
                                })
                            },
                            cancel: function () {
                                $.alert('Canceled!');
                            },
                        }
                    });
                }),
                $(row).find("#activateBtn").on('click', function() {
                    var id = data.id
                    
                    $.confirm({
                        title: 'Activate!',
                        content: 'Are you sure you want to activate this subsidiary?',
                        theme: 'material',
                        buttons: {
                            confirm: function () {
                                $.ajax({
                                    type:"POST",
                                    url: "{{ url('activate-subsidiary') }}",
                                    data: {
                                        subsidiary_id: id,
                                        _token:"{{ csrf_token() }}"
                                    },
                                    success: function(res) {
                                        $.toast({
                                            heading: 'Success',
                                            text: res.msg,
                                            position: 'top-right',
                                            stack: false,
                                            icon: 'success'
                                        })

                                        subsidiaryTable.ajax.reload()
                                    }
                                })
                            },
                            cancel: function () {
                                $.alert('Canceled!');
                            },
                        }
                    });
                })
            }
        })
        
        $("#addBtn").on('click', function() {
            $('#newSubsidiary').modal('show')
        })

        $("#subsidiaryForm").on('submit', function(e) {
            e.preventDefault()

            var formData = $(this).serializeArray()
            
            $.ajax({
                type: "POST",
                url: "{{ url('store-subsidiary') }}",
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
                            text: res.msg,
                            position: 'top-right',
                            stack: false,
                            icon: 'success'
                        })

                        $("#newSubsidiary").modal('hide')

                        subsidiaryTable.ajax.reload()
                    }
                }
            })
        })

        $("#updateSubsidiaryForm").on('submit', function(e) {
            e.preventDefault()

            var formData = $(this).serializeArray()
            
            $.ajax({
                type: "POST",
                url: "{{ url('update-subsidiary') }}",
                data: formData,
                success: function(res) {
                    if (res.error == 500) {
                        $.toast({
                            heading: 'Error',
                            text: res.error,
                            position: 'top-right',
                            stack: false,
                            icon: 'error'
                        })
                    }
                    else {
                        $.toast({
                            heading: 'Success',
                            text: res.msg,
                            position: 'top-right',
                            stack: false,
                            icon: 'success'
                        })

                        $("#editSubsidiary").modal('hide')

                        subsidiaryTable.ajax.reload()
                    }
                }
            })
        })
    })
</script>
@endsection