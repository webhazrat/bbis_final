<div id="peopleTypeUpdateModal" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">People Type Update</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>

            <form id="peopleTypeUpdateFrom">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="uTypeName">Name</label>
                        <input type="text" id="uTypeName" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="uTypeStatus">Status</label>
                        <select id="uTypeStatus" class="form-control selectpicker" title=" ">
                            <option value="6">Published</option>
                            <option value="7">Unpublished</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="uTypeOrder">Order</label>
                        <input type="number" id="uTypeOrder" class="form-control">
                    </div>

                    <div class="d-flex justify-content-end align-items-center">
                        <div class="preloader spinner-border text-primary spinner-border-sm hide mr-2"> </div>
                        <button href="#" class="btn btn-primary" type="submit" id="peopleTypeUpdateBtn">Update</button>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
</div>