@extends('layouts.header')

@section('content')
<section class="content-header">
    <h1>
        Purchase Request
    </h1>
    <ol class="breadcrumb">
        <li>Procurement</li>
        <li>Purchase Request</li>
        <li>Create Purchase Request</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-body">
                    <h4>Primary Information</h4>
                    <hr>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label>PR No.</label>
                                    <input type="text" class="form-control input-sm" value="To be generated" disabled>
                                </div>
                                    {{-- <div class="col-lg-6">
                                        <label></label>
                                        <input type="text" class="form-control input-sm" value="To be generated" disabled>
                                    </div> --}}
                                <div class="col-lg-6">
                                    <label>Date Requested</label>
                                    <input type="date" class="form-control input-sm" name="date_requested" value="{{ date('Y-m-d') }}" disabled>
                                </div>
                                <div class="col-lg-6">
                                    <label>Requested By</label>
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="text" class="form-control input-sm" value="{{ auth()->user()->name }}" disabled>
                                </div>
                                <div class="col-lg-6">
                                    <label>Due Date</label>
                                    <input type="date" class="form-control input-sm" name="due_date">
                                </div>
                                <div class="col-lg-6">
                                    <label>Remarks</label>
                                    <textarea name="remarks" id="remarks" class="form-control input-sm" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-lg-6" style="display: flex; justify-content:center; align-items:center;" >
                            <div class="box box-primary" style="width: 40%;">
                                <div class="box-head bg-primary">
                                    <b>Summary</b>
                                </div>
                                <div class="box-body">
                                    <p id="estimateAmount" style="margin:0;">ESTIMATE AMOUNT:
                                        <div class="text-right">
                                            <span class="h3 text-right">0.00</span>
                                        </div>
                                    </p>
                                    <hr>
                                    <p id="totalAmount" style="margin:0;">TOTAL AMOUNT: 
                                        <div class="text-right">
                                            <span class="h3 text-right">0.00</span>
                                        </div>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4>Classification</h4>
                    <hr>
                    <div class="row">
                        <div class="col-lg-4">
                            <label>Subsidiary</label>
                            <input type="hidden" name="subsidiary" value="{{ auth()->user()->subsidiary->id }}">
                            <input type="text" class="form-control input-sm" value="{{ auth()->user()->subsidiary->subsidiary_name }}" disabled>
                        </div>
                        <div class="col-lg-4">
                            <label>Subsidiary</label>
                            <input type="hidden" name="subsidiary" value="{{ auth()->user()->subsidiary->id }}">
                            <input type="text" class="form-control input-sm" value="{{ auth()->user()->subsidiary->subsidiary_name }}" disabled>
                        </div>
                        <div class="col-lg-4">
                            <label for="">Department</label>
                            <input type="hidden" name="department" value="{{ auth()->user()->department->id }}">
                            <input type="text" class="form-control input-sm" value="{{ auth()->user()->department->name }}" disabled>
                        </div>
                        <div class="col-lg-4">
                            <label for="">Classification</label>
                            <select data-placeholder="Select classification" class="form-control select2" name="classification" id="classification" required>
                                <option value=""></option>
                                @foreach ($classifications as $classification)
                                    <option value="{{ $classification->id }}">{{ $classification->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label for="">Attachments</label>
                            <input type="file" name="attachments" id="attachments" class="form-control input-sm" multiple required>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12">
                            <button class="btn btn-xs btn-success" type="button" id="addItemBtn">
                                <i class="fa fa-plus"></i>
                            </button>
                            <button class="btn btn-xs btn-danger" type="button" id="addRemoveBtn">
                                <i class="fa fa-minus"></i>
                            </button>
                            <div class="table-responsive">
                                <table class="table table-border table-hover" id="tableItem">
                                    <thead>
                                        <tr>
                                            <th>Item Code</th>
                                            <th>Item Description</th>
                                            <th>Category</th>
                                            <th>Sub Category</th>
                                            <th>Item Quantity</th>
                                            <th>Amount</th>
                                            <th>Estimated Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyItemTable">
                                        <tr>
                                            <td>
                                                <select data-placeholder="Select item code" name="inventoryItem[]" class="form-control select2" style="width:100%;">
                                                    <option value=""></option>
                                                    @foreach ($inventories as $inventory)
                                                        <option value="{{ $inventory->id }}">{{ $inventory->item_code }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <p id="itemDescription"></p>
                                            </td>
                                            <td>
                                                <p id="category"></p>
                                            </td>
                                            <td>
                                                <p id="subCategory"></p>
                                            </td>
                                            <td>
                                                <p id="itemQuantity"></p>
                                            </td>
                                            <td>
                                                <p id="amount"></p>
                                            </td>
                                            <td>
                                                <input type="number" name="estimated_amount" class="form-control" step=".01">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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

        $("#addItemBtn").on('click', function() {
            
            var newRow = `
                <tr>
                    <td>
                        <select data-placeholder="Select item code" name="inventoryItem[]" class="form-control select2" style="width:100%;">
                            <option value=""></option>
                            @foreach ($inventories as $inventory)
                                <option value="{{ $inventory->id }}">{{ $inventory->item_code }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <p id="itemDescription"></p>
                    </td>
                    <td>
                        <p id="category"></p>
                    </td>
                    <td>
                        <p id="subCategory"></p>
                    </td>
                    <td>
                        <p id="itemQuantity"></p>
                    </td>
                    <td>
                        <p id="amount"></p>
                    </td>
                    <td>
                        <input type="number" name="estimated_amount" class="form-control" step=".01">
                    </td>
                </tr>
            `

            $("#tbodyItemTable").append(newRow)
            $("[name='inventoryItem[]']").select2()
        })

        $("#addRemoveBtn").on('click', function() {
            var childrenLength = $('#tbodyItemTable').children().length

            if (childrenLength > 1) {
                $('#tbodyItemTable').children().last().remove()
            }
        })
        
        $("[name='inventoryItem[]']").on('change', function() {
            var itemDescription = $(this).closest('tr').find('#itemDescription')
            var itemCategory = $(this).closest('tr').find('#category')
            var itemQuantity = $(this).closest('tr').find('#itemQuantity')
            var amount = $(this).closest('tr').find('#amount')
            var id =  $(this).val()

            $.ajax({
                type:"POST",
                url:"{{ url('refreshInvetory') }}",
                data: {
                    inventory_id: id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    itemDescription.text(res.inventory.item_description)
                    itemCategory.text("")
                    itemQuantity.text(res.inventory.qty)
                    amount.text(res.inventory.cost)
                }
            })
        })

        // $("#subsidiaryForm").on('submit', function(e) {
        //     e.preventDefault()

        //     var formData = $(this).serializeArray()
            
        //     $.ajax({
        //         type: "POST",
        //         url: "{{ url('store-subsidiary') }}",
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
        //                     text: res.msg,
        //                     position: 'top-right',
        //                     stack: false,
        //                     icon: 'success'
        //                 })

        //                 $("#newSubsidiary").modal('hide')

        //                 subsidiaryTable.ajax.reload()
        //             }
        //         }
        //     })
        // })

        // $("#updateSubsidiaryForm").on('submit', function(e) {
        //     e.preventDefault()

        //     var formData = $(this).serializeArray()
            
        //     $.ajax({
        //         type: "POST",
        //         url: "{{ url('update-subsidiary') }}",
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
        //                     text: res.msg,
        //                     position: 'top-right',
        //                     stack: false,
        //                     icon: 'success'
        //                 })

        //                 $("#editSubsidiary").modal('hide')

        //                 subsidiaryTable.ajax.reload()
        //             }
        //         }
        //     })
        // })
    })
</script>
@endsection