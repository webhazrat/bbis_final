<div id="speciesAdditionUpdateModal" class="modal fade">
    <div class="modal-dialog" style="max-width:1000px">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Update</h6>
                <button type="button" class="close" data-dismiss="modal">
                    <i data-feather="x"></i>
                </button>
            </div>

            <form id="speciesAdditionUpdateForm" method="post" enctype="multipart/form-data">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="taxonGroup">Taxon Group</label>
                                        <select id="taxonGroup" name="taxonGroup" class="form-control selectpicker" title=" ">

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="scientificName">Scientific Name</label>
                                        <div class="scientificNameArea">
                                            <select name="scientificName" id="scientificName" class="form-control selectpicker" data-live-search="true" data-size="5" title=" ">
                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="commonName">Common Name</label>
                                        <input type="text" name="commonName" id="commonName" class="form-control" readonly>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="locality">Locality</label>
                                        <input type="text" name="locality" id="locality" class="form-control">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="district">District </label>
                                        <select id="district" name="district" class="form-control selectpicker" data-live-search="true" title=" ">

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="placeSearch">GPS Coordination</label>
                                        <div class="withIcon mb-1">
                                            <input type="text" name="placeSearch" id="placeSearch" class="form-control" placeholder="Search a location">
                                            <span id="currentLocation" data-toggle="tooltip" data-content="Current Position"><i data-feather="map-pin"></i></span>
                                        </div>

                                        <input type="text" name="gpsCoordination" id="gpsCoordination" class="form-control" placeholder="Coordination" readonly>

                                        <div id="map" class="mt-2"></div>
                                    </div>
                                </div>
                                                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="dateTime">Collection Date</label>
                                        <input type="datetime-local" name="dateTime" id="dateTime" class="form-control">
                                    </div>
                                </div>
                                

                            </div>
                        </div>
                        <div class="col-md-6">
                            
                            <div class="form-row">
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="photos">Images <span>(Each image must be less than 2 megabytes)</span></label>
                                        <input type="file" name="photos[]" id="photos" class="form-control" multiple accept=".jpg, .png, .jpeg">
                                        <input type="hidden" class="form-control" id="removePhotos" name="removePhotos">
                                        <div id="prevImagePreview">

                                        </div>
                                        
                                        <div id="imagePreview">

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="localNameArea form-group">
                                        <label for="localName">Local Name <span>(Optional)</span></label>
                                        <input type="text" class="form-control" name="localName" id="localName">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notes">Notes <span>(Optional)</span></label>
                                        <textarea name="notes" id="notes" rows="3" class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="descriptionArea form-group">
                                        <label for="description">Short Description / Identification <span>(Optional)</span></label>
                                        <textarea name="description" id="description" class="form-control" rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="habitatArea form-group">
                                        <label for="habitat">Habitat <span>(Optional)</span></label>
                                        <textarea name="habitat" id="habitat" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="biologyArea form-group">
                                        <label for="biology">Biology <span>(Optional)</span></label>
                                        <textarea name="biology" id="biology" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="referenceArea form-group">
                                        <label for="reference">Reference <span>(Optional)</span></label>
                                        <textarea name="reference" id="reference" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>

                                </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end align-items-center">
                        <div class="preloader spinner-border text-primary spinner-border-sm mr-2 hide"> </div>
                        <button href="#" class="btn btn-primary" type="submit" id="speciesAdditionUpdateBtn">Update</button>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
</div>