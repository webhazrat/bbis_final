
<?php 
    // After login
    include "inc/header.php";
    if(Session::authentication() == false){
        header("location:".BASE_URL);
    }
?>

<div class="page-header-area">
    <div class="container">
        <div class="header-body text-center">
            <h2> My Contributions </h2>
        </div>
    </div>
</div>


<div class="container pt-5 pb-5">
    <div class="col-md-12">

        <div class="main-content-area">
            <div class="content-section">
                <!-- <div class="d-flex align-items-center justify-content-between">
                    <div class="btn-group mb-3">
                        <a href="#" id="tableView" class="btn btn-outline-default"><i data-feather="menu"></i></a>
                        <a href="#" id="gridView" class="btn btn-outline-default active"><i data-feather="grid"></i></a>
                    </div>
                    <div class="searchBox">
                        <div class="d-flex align-items-center" style="gap:6px">
                            Search: <input type="text" class="form-control" id="customSearch">
                        </div>
                    </div>
                </div> -->
                <div class="table-responsive">
                    <table id="contributionsList" class="table table-bordered" style="width:100%" data-export-title="Contributions List">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Group</th>
                                <th>Code</th>
                                <th>Scientific Name</th>
                                <th>English Name</th>
                                <th>Family</th>
                                <th>Locality</th>
                                <th>District</th>
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


<?php 
    include "modal/species-addition-update.php";
    include "inc/footer.php";
 ?>
 
<script src="js/my-contributions.js"></script>
