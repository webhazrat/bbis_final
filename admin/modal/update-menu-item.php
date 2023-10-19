<div id="menuItemEditModal" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Update Label</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form method="post" id="labelForm">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" id="data_id" class="form-control">
                        <input type="text" id="data_label" class="form-control">
                    </div>
                    <div class="text-right">
                        <button class="btn btn-primary" type="submit" id="labelUpdate">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>