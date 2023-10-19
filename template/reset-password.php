<?php 
    include 'inc/header.php';
    if(Session::get('user_login') == true){ 
        header("location: home");
    }
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    $token = isset($_GET['token']) ? $_GET['token'] : '';
    $email = isset($_GET['email']) ? $_GET['email'] : '';
    if(empty($token) || empty($email)){
        header("location: forgot");
    }
?>

    <div class="page-header-area">
        <div class="container">
            <div class="header-body text-center">
                <h2> Reset Password </h2>
            </div>
        </div>
    </div>
    
    <section class="pt-5 pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="form-area shadow-sm p-5" style="background-color:#F8F9FA">
                        
                        <form id="changePassForm">
                            <div class="form-group">
                                <label for="password">Password <span>(At least 8 digit)</span></label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control field">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><span id="viewPassword"><i data-feather="eye"></i></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cPassword">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" name="cPassword" id="cPassword" class="form-control field">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><span id="viewPassword"><i data-feather="eye"></i></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end align-items-center">
                                <div class="preloader spinner-border text-success spinner-border-sm mr-2 hide">
                                </div>
                                <button type="submit" class="btn btn-success" id="changePassFormBtn">Confirm</button>
                            </div>
                        </form>

                        <form id="resetForm">
                            <input type="hidden" name="action" id="action" class="form-control" value="<?php echo $action; ?>">
                            <input type="hidden" name="verifyToken" id="verifyToken" class="form-control" value="<?php echo $token; ?>">
                            <input type="hidden" name="verifyEmail" id="verifyEmail" class="form-control" value="<?php echo $email; ?>">
                        </form>
                                
                    </div>
                </div>

            </div>
        </div>
    </section>
<?php include 'inc/footer.php'; ?>
<script src='js/reset-password.js'></script>
