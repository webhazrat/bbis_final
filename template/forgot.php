<?php 
    include 'inc/header.php';
    if(Session::get('user_login') == true){ 
        header("location: home");
    }
?>

    <div class="page-header-area">
        <div class="container">
            <div class="header-body text-center">
                <h2> Forgot Password </h2>
            </div>
        </div>
    </div>
    
    <section class="pt-5 pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="form-area shadow-sm p-5" style="background-color:#F8F9FA">
                        <form id="forgotForm">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                            
                            <div class="d-flex justify-content-end align-items-center">
                                <div class="preloader spinner-border text-success spinner-border-sm mr-2 hide">
                                </div>
                                <button type="submit" class="btn btn-success" id="forgotFormBtn"> Submit </button>
                            </div>
                        </form>
                                
                    </div>
                </div>

            </div>
        </div>
    </section>
<?php include 'inc/footer.php'; ?>
<script src='js/forgot.js'></script>
