<?php 
    include "inc/header.php"; 
    if(in_array('1', $session_roles)){ }else{
        header("location: 404.php");
    }
?>
            <div class="main-content-area">
                
                <div class="title-section">
                    <div class="section-title d-flex justify-content-between align-items-center">
                        <h3>Users List</h3>
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
                        <table id="userAll" class="table table-bordered" style="width:100%" data-export-title="Users List">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Role</th>
                                    <th>Manage Group</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Join</th>
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
    include 'modal/user-view.php';
    include 'modal/user-status.php';
    include "inc/footer.php";
?>
<script src="../js/datatable-export.js"></script>
<script src="../js/users.js"></script>