<div class="modal" id="editSubsidiary">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Subsidiary</h5>
            </div>
            <form method="post" id="updateSubsidiaryForm">
                @csrf 
                <input type="hidden" id="subsidiaryId" name="subsidiary_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Name :
                            <input type="text" name="name" id="editName" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            Address :
                            <textarea name="address" id="editAddress" class="form-control" cols="30" rows="10" required></textarea>
                        </div>
                        <div class="col-md-12">
                            Shipping Address :
                            <textarea name="shipping_address" class="form-control" id="editShippingAddress" cols="30" rows="10" required></textarea>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>