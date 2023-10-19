<?php include "inc/header.php"; 
    if(!isset($_GET["id"]) || $_GET["id"] == ''){
        header("location:404.php");
    }
?>

            <div class="main-content-area">
                <div class="title-section">
                    <div class="section-title d-flex justify-content-between align-items-center">
                        <h3 id="pageTitle"></h3>
                        <div class="title-links d-flex align-items-center">
                            <div class="preloader spinner-border text-primary spinner-border-sm hide mr-2"> </div>
                            <a class="btn btn-soft-primary mr-2" href="species-group.php">All Species Group</a>
                            <a href="#" class="btn btn-primary" dataId="<?php echo $_GET['id']; ?>" id="groupUpdateBtn">Update</a>
                        </div>
                    </div>
                </div>

                <form id="groupUpdateForm">
                    <div class="form-row">
                        <div class="col-lg-8">
                            <div class="content-section">
                                <div class="form-group">
                                    <label for="gName">Title</label>
                                    <input type="text" id="gName" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="gSlug">Slug</label>
                                    <input type="text" id="gSlug" class="form-control">
                                </div>
                                <div class="form-group mb-0" id="description">
                                    <label for="gDescription">Description</label>
                                    <textarea name="gDescription" id="gDescription" class="summerNote" class="form-control"></textarea>
                                </div>
                            </div>                          

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

                                        <div id="collapseOne" class="collapse show">
                                            <div class="card-body">
                                                <div class="addedImage" id="featureImage">

                                                </div>
                                                <a href="#" class="btn btn-soft-primary" id="selectImage" wrapper="featureImage">Select Image</a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="content-section">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <label for="gParent">Parent</label>
                                        <select name="gParent" id="gParent" class="form-control selectpicker" title=" " data-live-search="true">
                                            
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="custom-control custom-switch mt-4">
                                            <input type="checkbox" class="custom-control-input" id="endLevel">
                                            <label class="custom-control-label" for="endLevel">End Level</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="content-section">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control selectpicker" title=" ">
                                            <option value="6">Published</option>
                                            <option value="7">Unpublished</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="ordering">Order</label>
                                        <input type="number" min="0" class="form-control" id="ordering">
                                    </div>
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
    include 'inc/footer.php'; 
?>
<script src="../plugins/js/jquery.dm-uploader.min.js"></script>
<script src="../plugins/js/demo-ui.js"></script>
<script src="../plugins/js/demo-config.js"></script>
<script src="../js/media-modal.js"></script>
<script src="../js/group-edit.js"></script>