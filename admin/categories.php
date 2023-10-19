<?php include "inc/header.php"; ?>

<div class="main-content-area">

    <div class="title-section">
        <div class="section-title d-flex justify-content-between align-items-center">
            <h3>Categories</h3>
            <div class="title-links">
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#createCategoryModal">Add Category</a>
            </div>
        </div>
    </div>
    <div class="content-section">
        <table id="categoryAll" class="table table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>Name</th>
                    <th>Author</th>
                    <th>Count</th>
                    <th>Created At</th>
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
    include "modal/category-create.php";
    include "modal/category-update.php";
    include "inc/footer.php"; 
?>
<script src="../js/categories.js"></script>
