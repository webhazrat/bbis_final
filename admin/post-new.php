<?php include 'inc/header.php'; ?>

            <div class="main-content-area">
                <div class="title-section">
                    <div class="section-title d-flex justify-content-between align-items-center">
                        <h3><?php echo 'New '.ucfirst($_GET["post-type"]); ?></h3>
                        <div class="title-links d-flex align-items-center">
                            <div class="preloader spinner-border text-primary spinner-border-sm hide mr-2"> </div>
                            <?php echo '<a class="btn btn-soft-primary mr-2" href="posts.php?post-type='.$_GET["post-type"].'">All '.ucfirst($_GET["post-type"]).'</a> '; ?>
                            <a href="#" class="btn btn-primary" type="<?php echo $_GET["post-type"]; ?>" id="postPublish">Publish</a>
                        </div>
                    </div>
                </div>

                <form id="postNewForm">
                    <div class="form-row">
                        <div class="col-lg-8">
                            <div class="content-section">
                                <div class="form-group">
                                    <label for="postTitle">Title</label>
                                    <input type="text" id="postTitle" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="postSlug">Slug</label>
                                    <input type="text" id="postSlug" class="form-control">
                                </div>
                                <?php if($_GET['post-type'] != 'partner') {?>
                                <div class="form-group mb-0">
                                    <label for="postContent">Description</label>
                                    <textarea name="postContent" id="postContent" class="summerNote" class="form-control"></textarea>
                                </div>
                                <?php } ?>
                            </div>
                            <?php if($_GET['post-type'] == 'partner') {?>
                            <div class="content-section">
                                <label for="postExcerpt">Link</label>
                                <input type="text" id="postExcerpt" class="form-control">
                            </div>
                            <?php } ?>
                            

                        </div>
                        <div class="col-lg-4">
                            <div class="content-section">
                                <div class="right-bar">
                                    <div class="card">
                                        <div class="card-header">
                                            <a class="btn-link d-flex justify-content-between" data-toggle="collapse" data-target="#collapseOne">
                                                <span>Featured Image</span> <span><i data-feather="chevron-down"></i></span>
                                            </a>
                                        </div>

                                        <div id="collapseOne" class="collapse">
                                            <div class="card-body">
                                                <?php if($_GET['post-type'] == 'post') {
                                                echo '<p class="mb-2">Recomended dimension 500*281</p>';
                                            }?>
                                                <div class="addedImage" id="featureImage">

                                                </div>
                                            <a href="#" class="btn btn-soft-primary" id="selectImage" wrapper="featureImage">Select Image</a>
                                            
                                            </div>
                                        </div>
                                    </div>

                                    <?php if($_GET["post-type"] == "post"){ ?>
                                        <div class="card">
                                            <div class="card-header">
                                                <a class="btn-link d-flex justify-content-between" data-toggle="collapse" data-target="#collapseTwo">
                                                    <span>Categories</span> <span><i data-feather="chevron-down"></i></span>
                                                </a>
                                            </div>
                                            <div id="collapseTwo" class="collapse">
                                                <div class="card-body">
                                                    <div class="form-element">
                                                        <select id="postCategory" class="form-control selectpicker" title=" ">

                                                        </select>
                                                    </div>
                                                    <a href="#" class="btn btn-soft-primary mt-2" data-toggle="modal" data-target="#createCategoryModal">Add Category</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if($_GET["post-type"] == "page"){ ?>

                                    <div class="card">
                                        <div class="card-header">
                                            <a class="btn-link d-flex justify-content-between" data-toggle="collapse" data-target="#collapseFour">
                                                <span>isTemplate </span> <span><i data-feather="chevron-down"></i></span>
                                            </a>
                                        </div>
                                        <div id="collapseFour" class="collapse">
                                            <div class="card-body">
                                                <div class="form-element">
                                                    <select name="template" id="template" class="form-control selectpicker">
                                                    <option value="">For which template</option>
                                                    <?php 
                                                        $scan = scandir('../template');
                                                        $files = array_diff($scan, array('.', '..'));
                                                        foreach($files as $file)
                                                        {
                                                            $file_name = explode('.', $file);
                                                            $name = strtolower($file_name[0]);
                                                            echo '<option value="'.$file.'">'.ucfirst($name).'</option>';
                                                        }  
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php } ?>

                                </div>
                            </div>

                            <div class="content-section">
                                <div class="d-flex align-items-center">
                                    <label for="status" class="mb-0 mr-3">Status</label>
                                    <select name="status" id="status" class="form-control selectpicker" title=" ">
                                        <option value="6">Published</option>
                                        <option value="7">Unpublished</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="content-section">
                                <div class="d-flex align-items-center">
                                    <label for="ordering" class="mb-0 mr-3">Order</label>
                                    <input type="number" min="0" class="form-control" id="ordering">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php 
    include 'modal/media-modal.php'; 
    include 'modal/category-create.php'; 
    include 'inc/footer.php'; 
?>
<script src="../plugins/js/jquery.dm-uploader.min.js"></script>
<script src="../plugins/js/demo-ui.js"></script>
<script src="../plugins/js/demo-config.js"></script>
<script src="../js/media-modal.js"></script>
<script src="../js/post-categories.js"></script>
<script src="../js/post-new.js"></script>