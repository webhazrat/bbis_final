<div id="speciesStatusModal" class="modal fade">
    <div class="modal-dialog" style="max-width:400px">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Update Category</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>

            <form id="speciesStatusForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control selectpicker">
                            <option value="4">Pending</option>
                            <option value="5">Canceled</option>
                            <option value="8">Approved</option>
                        </select>
                    </div>
                    <div class="form-group hide" id="commentArea">
                        <label for="comment">Comment</label>
                        <textarea name="comment" id="comment" rows="3" class="form-control"></textarea>
                    </div>

                    <div class="d-flex justify-content-end align-items-center">
                        <div class="preloader spinner-border text-primary spinner-border-sm hide mr-2"> </div>
                        <button href="#" class="btn btn-primary" type="submit" id="speciesStatusBtn">Update</button>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
</div>