<div id="userUpdateModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h6 class="modal-title d-flex align-items-center"><div class="ufo spinner-border text-primary spinner-border-sm hide mr-2"></div>Update User</h6>
                <button type="button" class="close" data-dismiss="modal">
                    <i data-feather="x"></i>
                </button>
            </div>

            <form id="userUpdateForm">
                <div class="modal-body">
                    <input type="hidden" class="form-control" id="uUserId">
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="userName">Name</label>
                                <input type="text" name="userName" id="userName" class="form-control">
                            </div>
                        </div> 
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="userGender">Gender</label>
                                <select name="userGender" id="userGender" class="form-control selectpicker" title=" ">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="userDOB">Date of Birth</label>
                                <input type="text" name="userDOB" id="userDOB" class="form-control datepicker">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="userProfession"> Profession </label>
                                <input type="text" name="userProfession" id="userProfession" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="userInstitute">Institute <span>(Optional)</span></label>
                                <input type="text" id="userInstitute" class="form-control">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="userDistrict">Current District </label>
                                <select name="userDistrict" id="userDistrict" class="form-control selectpicker" data-live-search="true" title=" ">

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="userArea">Upazilla/City Corporation </label>
                                <select name="userArea" id="userArea" class="form-control selectpicker" data-live-search="true" title=" ">

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="userZone"> Municipality/Thana/Union </label>
                                <select name="userZone" id="userZone" class="form-control selectpicker" data-live-search="true" title=" ">

                                </select>
                            </div>
                        </div>
                        
                    </div> 

                    <div class="d-flex justify-content-end align-items-center">
                        <div class="uep spinner-border text-primary spinner-border-sm mr-2 hide"></div>
                        <button href="#" class="btn btn-primary" type="submit" id="updateMember">Update</button>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
</div>