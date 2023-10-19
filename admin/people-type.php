<?php 
    include "inc/header.php"; 
    if(in_array('1', $session_roles)){ }else{
        header("location: 404.php");
    }
?>
            <div class="main-content-area">
                
                <div class="title-section">
                    <div class="section-title d-flex justify-content-between align-items-center">
                        <h3>People Type List</h3>
                        <div class="title-links">
                            <a href="#" class="btn btn-primary mr-2" data-toggle="modal" data-target="#peopleTypeCreateModal">Add People Type</a>
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
                        <table id="peopleTypeAll" class="table table-bordered" style="width:100%" data-export-title="People Type">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Name</th>
                                    <th>Count</th>
                                    <th>Order</th>
                                    <th>Author</th>
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
    include "modal/people-type-create.php";
    include "modal/people-type-update.php";
    include "inc/footer.php";
?>

<script src="../js/people-type.js"></script>