<div id="peopleTypeCreateModal" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">People Type</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>

            <form id="peopleTypeCreateFrom">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="typeName">Name</label>
                        <input type="text" id="typeName" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="typeStatus">Status</label>
                        <select name="typeStatus" id="typeStatus" class="form-control selectpicker" title=" ">
                            <option value="6">Published</option>
                            <option value="7">Unpublished</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="typeOrder">Order</label>
                        <input type="number" id="typeOrder" class="form-control">
                    </div>

                    <div class="d-flex justify-content-end align-items-center">
                        <div class="preloader spinner-border text-primary spinner-border-sm hide mr-2"> </div>
                        <button href="#" class="btn btn-primary" type="submit" id="peopleTypeCreateBtn">Create</button>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
</div>