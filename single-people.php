<?php 
  include 'inc/header.php';
  $page = $_GET['page'];
  $userName = explode('/', $page)[1];
?>

<section class="page pt-5 pb-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="singlePeople" userName="<?php echo $userName; ?>">
                    
                    <div class="form-row">
                        <div class="col-md-3">
                            <div class="profile-photo-area p-3 mb-3 shadow-sm">
                                <div class="photo" id="profilePhoto">
                                    
                                </div>

                                <div class="profile-body mb-2">
                                    <div class="section-title mt-3 text-center">
                                        <h3 id="profile_name"></h3>
                                    </div>
                                    <ul class="info-list profile_info_list">
                                        
                                    </ul>
                                </div>
                            </div>

                            <div class="content-section p-3 mb-3 shadow-sm">
                                <div class="section-title mb-2">
                                    <h3>Social Links</h3>
                                </div>
                                <div class="social-links">
                                    <ul id="social-links">

                                    </ul>
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-md-9">
                            <div class="personal-tab">
                                <div id="species-review">
                                    
                                </div>


                                <ul id="personal_info">

                                </ul>
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</section>
  
<?php include 'inc/footer.php'; ?>
<script src="<?php echo BASE_URL; ?>/js/single-people.js"></script>