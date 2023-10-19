<div id="createCategoryModal" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Category</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>

            <form id="createCategoryForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="catName">Name</label>
                        <input type="text" id="catName" class="form-control">
                    </div>

                    <div class="d-flex justify-content-end align-items-center">
                        <div class="preloader spinner-border text-primary spinner-border-sm hide mr-2"> </div>
                        <button href="#" class="btn btn-primary" type="submit" id="createCategoryBtn">Create</button>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
</div>