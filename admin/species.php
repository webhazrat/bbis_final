<?php include "inc/header.php"; ?>

            <div class="main-content-area">
                
                <div class="title-section">
                    <div class="section-title d-flex justify-content-between align-items-center">
                        <h3>Species</h3>
                        <div class="title-links">
                            <a href="#" class="btn btn-soft-primary mr-2" data-toggle="dropdown"> Export <i data-feather="chevron-down"></i></a>
                            <div class="export-dropdown dropdown-menu dropdown-menu-right" style="min-width:100px">
                                <a href="#" id="excel"><i data-feather="file"></i> Excel</a>
                                <a href="#" id="pdf"><i data-feather="file"></i> PDF</a>
                                <a href="#" id="print"><i data-feather="file"></i> Print</a>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="content-section">
                    <div class="table-responsive">
                        <table id="speciesList" class="table table-bordered" style="width:100%" data-export-title="Species List">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Auhtor</th>
                                    <th>Group</th>
                                    <th>Code</th>
                                    <th>Scientific Name</th>
                                    <th>English Name</th>
                                    <th>Kingdom</th>
                                    <th>Phylum</th>
                                    <th>Class</th>
                                    <th>Family</th>
                                    <th>Review</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php 
    include "modal/species-status.php";
    include "inc/footer.php"; 
?>
<script src="../js/species.js"></script>