<?php include "inc/header.php"; ?>

    <div class="main-content-area">
        <div class="title-section">
            <div class="section-title d-flex justify-content-between align-items-center">
                <h3><?php echo "All ".ucfirst($_GET['post-type']); ?></h3>
                <div class="title-links">
                    <a class="btn btn-primary" href="post-new.php?post-type=<?php echo $_GET['post-type']; ?>">Add <?php echo ucfirst($_GET['post-type']); ?></a>
                </div>
            </div>
        </div>
        <input type="hidden" id="postType" value="<?php echo $_GET['post-type']; ?>">
        <div class="content-section">
            <table id="postAll" class="table table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Categories</th>
                        <th>Order</th>
                        <th>Status</th>
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

<?php include "inc/footer.php"; ?>
<script src="../js/posts.js"></script>
