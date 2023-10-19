<?php 
    include 'inc/header.php';
    if(Session::get('user_login') == true){ 
        header("location: home");
    }
?>

    <div class="page-header-area">
        <div class="container">
            <div class="header-body text-center">
                <h2> Login </h2>
            </div>
        </div>
    </div>
    
    <section class="pt-5 pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    
                    <div class="form-area shadow-sm p-5" style="background-color:#F8F9FA">
                        <form id="loginForm">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control field">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><span id="viewPassword"><i data-feather="eye"></i></span></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex mb-3 justify-content-between align-items-center">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="remember_me">
                                    <label class="custom-control-label" for="remember_me"> Remember me</label>
                                </div>
                                <a href="forgot">Forgot password?</a>
                            </div>
                            
                            <div class="d-flex justify-content-end align-items-center">
                                <div class="preloader spinner-border text-success spinner-border-sm mr-2 hide">
                                </div>
                                <button type="submit" class="btn btn-success" id="loginFormBtn">Login</button>
                            </div>

                            <hr class="mt-4">
                            <div class="login-block text-center">
                                Already not a member | <a href="join"> Join</a>
                            </div>

                        </form>

                        <form id="verifyForm">
                            <input type="hidden" name="action" id="action" class="form-control" value="<?php if(isset($_GET['action'])) echo $_GET['action']; ?>">
                            <input type="hidden" name="verifyToken" id="verifyToken" class="form-control" value="<?php if(isset($_GET['token'])) echo $_GET['token']; ?>">
                            <input type="hidden" name="verifyEmail" id="verifyEmail" class="form-control" value="<?php if(isset($_GET['email'])) echo $_GET['email']; ?>">
                        </form>
                                
                    </div>
                </div>

            </div>
        </div>
    </section>
<?php include 'inc/footer.php'; ?>
<script src="js/login.js"></script>
