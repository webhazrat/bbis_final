<div id="profileSocialModal" class="modal fade roboto">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title">Add Social</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i data-feather="x"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="profileSocialForm">
                <div class="form-group">
                    <label for="socialTitle">Title</label>
                    <select id="socialTitle" class="form-control selectpicker" title=" ">
                        <option value="Google Scholar">Google Scholar</option>
                        <option value="ResearchGate">ResearchGate</option>
                        <option value="Facebook">Facebook</option>
                        <option value="Twitter">Twitter</option>
                        <option value="Instagram">Instagram</option>
                        <option value="Linkedin">Linkedin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="socialURL">URL</label>
                    <input type="text" id="socialURL" class="form-control">
                </div>

                <div class="d-flex justify-content-end align-items-center">
                    <div class="preloader4 spinner-border text-primary spinner-border-sm mr-2 hide"> </div>
                    <button type="submit" id="profileSocialBtn" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
  </div>
</div>