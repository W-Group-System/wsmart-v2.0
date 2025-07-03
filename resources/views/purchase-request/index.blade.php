@extends('layouts.header')

@section('content')
<section class="content-header">
    <h1>
        Purchase Request
    </h1>
    <ol class="breadcrumb">
        <li>Procurement</li>
        <li>Purchase Request</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-dollar"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Total Purchase Request</span>
                    <span class="info-box-number">0</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        {{-- <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-building-o"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Total Pending</span>
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
        </div> --}}
    </div>
    <div class="row">
        <div class="col-lg-3">
            <div class="box box-primary">
                <div class="box-header">
                    <a href="{{ url('create-purchase-request') }}" class="btn btn-primary" id="addBtn">
                        <i class="fa fa-plus"></i>
                        Add Purchase Request
                    </a>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="purchaseRequestTable">
                            <thead>
                                <tr>
                                    {{-- <th>Action</th>
                                    <th>Request Date </th>
                                    <th>PR Number </th>
                                    <th>Item Description </th>
                                    <th>Due Date </th>
                                    <th>Requestor Name </th>
                                    <th>Department </th>
                                    <th>Subsidiary </th> --}}
                                    <th>Purchase Request</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="box box-primary" >
                <div class="box-header">
                    Purchase Request Details
                </div>
                <div class="box-body">
                    <div id="detailContainer" hidden>
                        <h4>Primary Information</h4>
                        <hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <b>Purchase Number :</b>
                                        <p id="displayPurchaseNumber"></p>
                                    </div>
                                    <div class="col-lg-6">
                                        <b>Request Date :</b>
                                        <p id="displayRequestDate"></p>
                                    </div>
                                    <div class="col-lg-6">
                                        <b>Request Due Date :</b>
                                        <p id="displayDueDate"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="box box-primary" style="width: 40%;">
                                <div class="box-head bg-primary">
                                    <b>Total</b>
                                </div>
                                <div class="box-body">
                                    <p style="margin:0;">TOTAL AMOUNT: 
                                        <div class="text-right">
                                            <span class="h3 text-right" id="totalAmount">0.00</span>
                                        </div>
                                    </p>
                                </div>
                            </div>
                            </div>
                            
                        </div>
                        <h4>Classification</h4>
                        <hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <b>Subsidiary :</b>
                                <p id="displaySubsidiary"></p>
                            </div>
                            <div class="col-lg-6">
                                <b>Department :</b>
                                <p id="displayDepartment"></p>
                            </div>
                            <div class="col-lg-6">
                                <b>Class :</b>
                                <p id="displayClass"></p>
                            </div>
                            <div class="col-lg-6">
                                <b>Remarks :</b>
                                <p id="displayRemarks"></p>
                            </div>
                        </div>
                        <h4>Attachments</h4>
                        <hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <b>Attachments :</b>
                                <div id="fileContainer">

                                </div>
                                
                            </div>
                        </div>
                        <div class="row">
                            <input type="hidden" id="purchaseRequestId">
                            <div class="col-lg-12" style="margin-top: 20px;">
                                <table class="table-bordered table" id="purchaseRequestItemTable">
                                    <thead>
                                        <tr>
                                            <th>Item Code</th>
                                            <th>Item Description</th>
                                            <th>Item Category</th>
                                            <th>Item Sub Category</th>
                                            <th>Item Quantity</th>
                                            <th>Item Amount</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- @include('subsidiary.new_subsidiary') --}}
{{-- @include('subsidiary.edit_subsidiary') --}}
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $(".select2").select2()

        var purchaseRequestTable = $('#purchaseRequestTable').DataTable({
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
                url: "{{ url('get-purchase-request') }}",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            language: {
                processing: "⏳ Loading data, please wait..."
            },
            columns: [
                // { data: 'action' },
                // { data: 'request_date' },
                { 
                    // data: 'pr_number',
                    render: function(data, type, row) {
                        var label = ""
                        if(row.status == "Pending") {
                            label = `<span class="label label-warning">${row.status}</span>`
                        }
                        else if(row.status == "For RFQ") {
                            label = `<span class="label label-primary">${row.status}</span>`
                        }
                        else if(row.status == "Done") {
                            label = `<span class="label label-success">${row.status}</span>`
                        }

                        return `
                            <h3 style="margin:0;">${row.pr_number} - <small>${label}</small> </h3> <br>
                            <h5 style="margin:0;">Requestor: ${row.requestor}</h5><br>
                            <h6 style="margin:0;">Request Date: ${row.request_date}</h6><br>
                        `
                    }
                },
                // {data: 'item_description'},
                // { data: 'due_date' },
                // { data: 'requestor' },
                // { data: 'department' },
                // { data: 'subsidiary' },
                // { data: 'status' }
            ],
            rowCallback: function (row, data) {
                $(row).eq(0).find('td').on('click', function() {
                    $("#detailContainer").removeAttr('hidden')
                    
                    $("#displayPurchaseNumber").text(data.pr_number)
                    $("#displayRequestDate").text(data.request_date)
                    $("#displayDueDate").text(data.due_date)
                    $("#displaySubsidiary").text(data.subsidiary)
                    $("#displayDepartment").text(data.department)
                    $("#displayClass").text(data.class)
                    $("#displayRemarks").text(data.remarks)
                    $("#purchaseRequestId").val(data.id)

                    $("#fileContainer").children().remove()
                    $.each(data.attachments, function(key, attachment) {
                        let link = `
                            <a href="{{ url('') }}${attachment.file}" target="_blank">
                                <i class="fa fa-file-o"></i>
                            </a><br>
                        `;
                        $("#fileContainer").append(link);
                    })

                    $("#totalAmount").text(data.total_cost)
                    purchaseRequestItemTable.ajax.reload()

                })
            }
        })
        
        var purchaseRequestItemTable = $('#purchaseRequestItemTable').DataTable({
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
                url: "{{ url('get-purchase-item') }}",
                data: function(d){
                    d.purchase_request_id = $("#purchaseRequestId").val();
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            language: {
                processing: "⏳ Loading data, please wait..."
            },
            columns: [
                {data: "item_code"},
                {data: "item_description"},
                {data: "category"},
                {data: "subcategory"},
                {data: "qty"},
                {data: "amount"}
            ]
        })
    })
</script>
@endsection