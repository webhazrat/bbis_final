<?php 
    include "inc/header.php"; 
    $id = isset($_GET['id']) ? $_GET['id'] : '';
?>

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
                        <table id="speciesAdditionList" class="table table-bordered" style="width:100%" data-export-title="Species Addition List" data-id="<?php echo $id; ?>">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Auhtor</th>
                                    <th>Locality</th>
                                    <th>District</th>
                                    <th>Coordination</th>
                                    <th>Collection</th>
                                    <th>Comment</th>
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
    include "modal/species-addition-status.php";
    include "inc/footer.php"; 
?>
<script src="../js/species-addition.js"></script>