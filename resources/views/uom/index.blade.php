@extends('layouts.header')

@section('content')
<section class="content-header">
    <h1>
        Unit of Measurement
    </h1>
    <ol class="breadcrumb">
        <li>Settings</li>
        <li>Unit of Measurement</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-balance-scale"></i></span>

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
                <span class="info-box-icon bg-green"><i class="fa fa-balance-scale"></i></span>

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
                <span class="info-box-icon bg-red"><i class="fa fa-balance-scale"></i></span>

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
                        Add UOMs
                    </button>
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-hover" id="uomTable">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>UOM</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@include('uom.new_uom')
@include('uom.edit_uom')
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $(".select2").select2()

        var uomTable = $('#uomTable').DataTable({
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
                url: "{{ url('get-uom') }}",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            language: {
                processing: "⏳ Loading data, please wait..."
            },
            columns: [
                { data: 'action' },
                { data: 'measurement' },
                { data: 'status' }
            ],
            rowCallback: function (row, data) {
                $(row).find("#editBtn").on('click', function() {
                    $("#editUomModal").modal('show')
                    
                    $("#editUom").val(data.measurement)
                    $("#uomId").val(data.id)
                }),
                $(row).find("#deactivateBtn").on('click', function() {
                    var id = data.id

                    $.confirm({
                        title: 'Deactivate!',
                        content: 'Are you sure you want to deactivate this uom?',
                        theme: 'material',
                        buttons: {
                            confirm: function () {
                                $.ajax({
                                    type:"POST",
                                    url: "{{ url('deactivate-uom') }}",
                                    data: {
                                        uom_id: id,
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

                                        uomTable.ajax.reload()
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
                        content: 'Are you sure you want to activate this uom?',
                        theme: 'material',
                        buttons: {
                            confirm: function () {
                                $.ajax({
                                    type:"POST",
                                    url: "{{ url('activate-uom') }}",
                                    data: {
                                        uom_id: id,
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

                                        uomTable.ajax.reload()
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
            $('#newUomModal').modal('show')

            $("#uomForm").trigger('reset')
        })

        $("#uomForm").on('submit', function(e) {
            e.preventDefault()

            var formData = $(this).serializeArray()
            
            $.ajax({
                type: "POST",
                url: "{{ url('store-uom') }}",
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

                        $("#newUomModal").modal('hide')

                        uomTable.ajax.reload()
                    }
                }
            })
        })

        $("#editUomForm").on('submit', function(e) {
            e.preventDefault()

            var formData = $(this).serializeArray()
            
            $.ajax({
                type: "POST",
                url: "{{ url('update-uom') }}",
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

                        $("#editUomModal").modal('hide')

                        uomTable.ajax.reload()
                    }
                }
            })
        })
    })
</script>
@endsection