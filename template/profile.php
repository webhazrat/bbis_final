
<?php
    // After login
    include "inc/header.php";
    if(Session::authentication() == false){
        header("location: ".BASE_URL);
    }
?>

<div class="page-header-area">
    <div class="container">
        <div class="header-body text-center">
            <h2> Profile </h2>
        </div>
    </div>
</div>


<div class="container pt-5 pb-5">
    <div class="col-md-12">
        <div class="main-content-area">
            
            <div class="content-section">
                <div class="profile">
                    
                    <div class="title-links d-flex align-items-center">
                        <div class="preloader spinner-border text-primary spinner-border-sm mr-2"></div>
                        
                        <a data-toggle="dropdown" class="btn btn-primary" href="#"> <i data-feather="settings"></i> </a>
                        <div class="dropdown-menu dropdown-menu-right shadow-lg">
                            <a href="#" id="editProfile"> <i data-feather="edit"></i> Edit Profile</a>
                            <a href="javascript:void(0)" id="cem"> <i data-feather="mail"></i>Change Email</a>
                        </div>
                    </div>
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
                                <div class="section-title mb-2 d-flex justify-content-between align-items-center">
                                    <h3>Social Links</h3>
                                    <a href="#" data-toggle="modal" data-target="#profileSocialModal"><i data-feather="plus"></i></a>
                                </div>
                                <div class="social-links">
                                    <ul id="social-links">
                                    </ul>
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-md-9">
                            <div class="personal-tab mt-5">
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
</div>


<?php 
    include "modal/profile-update.php";
    include "modal/profile-photo.php";
    include "modal/profile-social.php"; 
    include "modal/profile-email.php"; 
    include "inc/footer.php";
?>
<script src="<?php echo BASE_URL; ?>/plugins/js/croppie.min.js"></script>
<script src="<?php echo BASE_URL; ?>/plugins/js/summernote-bs4.min.js"></script>
<script src="<?php echo BASE_URL; ?>/js/location.js"></script>
<script src="<?php echo BASE_URL; ?>/js/profile.js"></script>

