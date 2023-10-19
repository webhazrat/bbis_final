<?php include "inc/header.php"; 
    if(in_array('1', $session_roles)){ }else{
        header("location: 404.php");
    }
?>
            <div class="main-content-area">
                
                <div class="title-section">
                    <div class="section-title d-flex justify-content-between align-items-center">
                        <h3>Species Group</h3>
                        <div class="title-links">
                            <a href="group-new.php" class="btn btn-primary">Add Group</a>
                        </div>
                    </div>
                </div>

                <div class="content-section">
                    <div class="table-responsive">
                        <table id="groupAll" class="table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Name</th>
                                    <th>Hierarchy</th>
                                    <th>End Level</th>
                                    <th>Total</th>
                                    <th>Approved</th>
                                    <th>Author</th>
                                    <th>Order</th>
                                    <th>Status</th>
                                    <th>Date</th>
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
    include "inc/footer.php"; 
?>
<script src="../js/species-group.js"></script>