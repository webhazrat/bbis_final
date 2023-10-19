<?php include "inc/header.php"; ?>

    <div class="main-content-area">
        <div class="title-section">
            <div class="section-title d-flex justify-content-between align-items-center">
                <h3>Media Library</h3>
                <div class="title-links">
                    <a href="media-new.php" class="btn btn-primary">Add Media</a>
                </div>
            </div>
        </div>
        <div class="content-section">
            <table id="mediaAll" class="table table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>File</th>
                        <th>Size</th>
                        <th>Uploaded</th>
                        <th>Author</th>
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
    include "modal/media-view.php";
    include "inc/footer.php"; 
?>
<script src="../js/media.js"></script>
