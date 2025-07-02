<div class="modal" id="newUser">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Users</h5>
            </div>
            <form method="post" id="addUserForm">
                @csrf 
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Department
                            <select data-placeholder="Select department" name="department" id="department" class="select-2 form-control" style="width: 100%;" required>
                                <option value=""></option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->code .' - '. $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            Name
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            Email
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            Position
                            <input type="text" name="position" id="position" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            Role <i>(Optional)</i>
                            <select data-placeholder="Select role" name="role" id="role" class="select2 form-control" style="width: 100%;" >
                                <option value=""></option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            Subsidiary
                            <select data-placeholder="Select subsidiary" name="subsidiary" id="subsidiary" class="select2 form-control" style="width: 100%;" required>
                                <option value=""></option>
                                @foreach ($subsidiaries as $subsidiary)
                                    <option value="{{ $subsidiary->id }}">{{ $subsidiary->subsidiary_name }}</option>
                                @endforeach
                            </select>
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