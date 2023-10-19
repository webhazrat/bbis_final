<div id="userStatusModal" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title d-flex align-items-center"><div class="preloader spinner-border text-primary spinner-border-sm hide mr-2"></div> Edit User </h6>
                <button type="button" class="close" data-dismiss="modal">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="userStatusForm">
                    <input type="hidden" class="form-control" id="userId">
                    <div class="form-group">
                        <label for="userRole">Role</label>
                        <select name="userRole" id="userRole" class="form-control selectpicker" title=" " multiple>
                        
                        </select>
                    </div>

                    <div id="managed_group_block" class="form-group hide">
                        <label for="managedGroup">Managed Group</label>
                        <select name="managedGroup" id="managedGroup" class="form-control selectpicker" title=" " multiple>
                            
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="userType">People Type</label>
                        <select name="userType" id="userType" class="form-control selectpicker" title>
                            
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="userStatus">Status</label>
                        <select name="userStatus" id="userStatus" class="form-control selectpicker" title=" ">
                            <option value="1">Verified</option>
                            <option value="2">Unverified</option>
                            <option value="3">Suspended</option>
                        </select>
                    </div>
                    
                    <div class="d-flex justify-content-end align-items-center">
                        <div class="preloader2 spinner-border text-primary spinner-border-sm hide mr-2"> </div>
                        <button href="#" class="btn btn-primary" type="submit" id="userStatusBtn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>