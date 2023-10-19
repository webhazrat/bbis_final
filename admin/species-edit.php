
<?php 
    include "inc/header.php";
?>
            <div class="main-content-area">

                <div class="title-section">
                    <div class="section-title d-flex justify-content-between align-items-center">
                        <h3>Edit Species</h3>
                        <div class="title-links d-flex align-items-center">
                            <div class="preloader spinner-border text-primary spinner-border-sm mr-2"></div>
                            <div class="saveBtn"> </div>
                            <a href="#" id="editSpecies" class="btn btn-primary"> <i data-feather="edit"></i> Edit</a>
                        </div>
                    </div>
                </div>

                <div id="speciesInfo">
                    <div class="form-row">
                        <div class="col-md-8">
                            <div class="content-section">
                                <input type="hidden" class="form-control" id="spId" value="<?php echo $_GET['id']; ?>">
                                <input type="hidden" class="form-control" id="groupId">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="spKingdom">Kingdom</label>
                                            <input type="text" id="spKingdom" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="spPhylum">Phylum</label>
                                            <input type="text" id="spPhylum" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="spClass">Class</label>
                                            <input type="text" id="spClass" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="spOrder">Order</label>
                                            <input type="text" id="spOrder" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="spFamily">Family</label>
                                            <input type="text" id="spFamily" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="spSubFamily">Sub Family</label>
                                            <input type="text" id="spSubFamily" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="spGenus">Genus</label>
                                            <input type="text" id="spGenus" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="spGenusAuth">Genus Auth</label>
                                            <input type="text" id="spGenusAuth" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="spSpecies">Species</label>
                                            <input type="text" id="spSpecies" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="spSpeciesAuth">Species Auth</label>
                                            <input type="text" id="spSpeciesAuth" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="spSubSpecies">Sub Species</label>
                                            <input type="text" id="spSubSpecies" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="spSubSpeciesAuth">Sub Species Auth</label>
                                            <input type="text" id="spSubSpeciesAuth" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="spScName">Scientific Name</label>
                                            <input type="text" id="spScName" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="spScNameAuth">Scientific Name Auth</label>
                                            <input type="text" id="spScNameAuth" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="spEngName">English Name</label>
                                            <input type="text" id="spEngName" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="spLocalName">Local Name</label>
                                            <input type="text" id="spLocalName" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="spHabitat">Habitat</label>
                                            <input type="text" class="form-control" id="spHabitat" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label for="">Sequences</label>
                                                <div id="addSeq">
                                                    
                                                </div>
                                            </div>
                                            <div id="sequences-area">  
                                                
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="spBdDist">Banladesh Distribution</label>
                                            <input type="text" id="spBdDist" class="form-control" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="spGbDist">Global Distribution</label>
                                            <input type="text" id="spGbDist" class="form-control" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label for="">Occurances</label>
                                                <div id="addOcu">
                                                    
                                                </div>
                                            </div>
                                            <div id="occurances-area">  
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group bd_iucn_block">
                                            <label for="spIucnBd">Bangladesh IUCN</label>
                                            <div class="summerNoteLink form-control" id="spIucnBd"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="spIucnBdYear">Bangladesh IUCN Year</label>
                                            <input type="text" id="spIucnBdYear" class="form-control" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="form-group gb_iucn_block">
                                            <label for="spIucnGb">Global IUCN</label>
                                            <div class="summerNoteLink form-control" id="spIucnGb"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="spIucnGbYear">Global IUCN Year</label>
                                            <input type="text" id="spIucnGbYear" class="form-control" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="spCitis">Citis</label>
                                            <input type="text" id="spCitis" class="form-control" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label for="">Accepted Names</label>
                                                <div id="addAc">
                                                    
                                                </div>
                                            </div>
                                            <div id="acceptedNames-area">  
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="spShortDes">Short Desciption</label>
                                            <div class="summerNoteLink form-control" id="spShortDes"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="spBiology">Biology</label>
                                            <div class="summerNoteLink form-control" id="spBiology"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="spCitePage">Cite Page</label>
                                            <div class="summerNoteLink form-control" id="spCitePage"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="form-group block mb-0">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label for="">References</label>
                                                <div id="addRef">
                                                    
                                                </div>
                                            </div>
                                            <div id="references-area">  
                                                
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="content-section">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="">Photos</label>
                                    <div id="addPhotos"> </div>
                                </div>
                                <div class="addedImage mt-2" id="featureImages">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<?php 
    include 'modal/media-modal.php'; 
    include "inc/footer.php" 
?>
<script src="../plugins/js/jquery.dm-uploader.min.js"></script>
<script src="../plugins/js/demo-ui.js"></script>
<script src="../plugins/js/demo-config.js"></script>
<script src="../js/media-modal.js"></script>
<script src="../js/species-edit.js"></script>

