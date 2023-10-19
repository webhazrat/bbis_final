<?php 
    include 'inc/header.php';
?>

    <div class="page-header-area">
        <div class="container">
            <div class="header-body text-center">
                <h2> Contribute </h2>
            </div>
        </div>
    </div>
        
    <section class="pt-5 pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <?php if(Session::authentication()) { ?>
                    <div class="col-md-12">
                        <div class="form-area p-5" style="background-color:#F8F9FA">
                            <form id="contributeForm" method="post" class="mb-0" enctype="multipart/form-data">
                            
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
                                                    <div class="text-right">
                                                        <a href="#" id="typeToggleBtn" type="exist">Search then If not exist, Add as a new</a>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="commonName">Common Name </label>
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
                                                    <label for="district">District</label>
                                                    <select name="district" id="district" class="form-control selectpicker" data-live-search="true" title=" " data-size="5">

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
                                                    <label for="dateTime">Collection Date <span>(mm/dd/yyyy hh:mm)</span></label>
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
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="terms" name="terms">
                                        <label class="custom-control-label" for="terms">I agree to BBIS terms and conditions</label>
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center">
                                        <div class="preloader spinner-border text-success spinner-border-sm mr-2 hide">
                                        </div>
                                        <button type="submit" class="btn btn-success" id="contributeBtn">Submit</button>
                                    </div>
                                </div>
                                

                            </form>   
                        </div>
                    </div>
                    
                    
                <?php }else{ ?>

                    <div class="col-md-6">
                        <div class="row contribute-false">
                            <div class="col-md-6">
                                <a href="join">
                                    <div class="p-4 text-center shadow-sm">
                                        Already not a member | <strong>Join</strong> 
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="login">
                                    <div class="p-4 text-center shadow-sm">
                                        Already a member | <strong>Login</strong>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                <?php } ?>
            </div>
        </div>
    </section>
<?php include 'inc/footer.php'; ?>

<script src="js/contribute.js"></script>
