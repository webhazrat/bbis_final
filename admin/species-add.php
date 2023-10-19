
<?php 
    include "inc/header.php";
?>
            <div class="main-content-area">

                <div class="title-section">
                    <div class="section-title d-flex justify-content-between align-items-center">
                        <h3>Add Species </h3>
                        <a href="../uploads/species-format.xlsx" class="btn btn-primary">Download .xls, .xlsx Format</a>
                    </div>
                </div>
                
                <div class="content-section">
                    <div class="form-row justify-content-center">
                        <div class="col-md-4">
                            <div id="progress" class="hide">
                                <div class="progress mb-3">
                                    <div class="progress-bar" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>

                            <form id="addSpeciesForm">
                                <div class="form-group">
                                    <label for="spGroup">Species Group</label>
                                    <select name="spGroup" id="spGroup" class="form-control selectpicker" title=" ">
                                        
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="speciesFile">Species File <span>(.xls/.xlsx)</span></label>
                                    <input type="file" name="speciesFile" id="speciesFile" class="form-control" accept=".xls,.xlsx">
                                </div>
                                <div class="text-right">
                                    <button id="speciesSubmitBtn" type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                            
                        </div>
                    </div>

                    <div>
                        <pre class="output"></pre>
                    </div>
                </div>
                    

            </div>
        </div>
    </div>
</div>


<?php include "inc/footer.php" ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="../js/species-add.js"></script>

