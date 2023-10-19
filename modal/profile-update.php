<div id="profileUpdateModal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Update Profile</h6>
                <button type="button" class="close" data-dismiss="modal">
                    <i data-feather="x"></i>
                </button>
            </div>

            <form id="profileUpdateForm">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" id="name" class="form-control">
                            </div>
                        </div> 
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select id="gender" class="form-control selectpicker" title=" ">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dob">Date of Birth <small>(mm/dd/yyyy)</small></label>
                                <input type="date" id="dob" class="form-control">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">+88</span>
                                    </div>
                                    <input type="text" name="phone" id="phone" class="form-control">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="district">District </label>
                                <select id="district" class="form-control selectpicker" data-live-search="true" title=" ">

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="area">Area</label>
                                <select id="area" class="form-control selectpicker" data-live-search="true" title=" ">

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="zone">Zone </label>
                                <select id="zone" class="form-control selectpicker" data-live-search="true" title=" ">

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="profession">Profession </label>
                                <input type="text" id="profession" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="institution">Institution</label>
                                <input type="text" id="institution" class="form-control">
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="about">About</label>
                                <textarea name="about" id="about" rows="6 " class="summernote form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end align-items-center">
                        <div class="preloader spinner-border text-primary spinner-border-sm mr-2"> </div>
                        <button href="#" class="btn btn-primary" type="submit" id="updateProfileBtn">Update</button>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
</div>