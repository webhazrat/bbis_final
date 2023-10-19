<?php include 'inc/header.php'; ?>

    <div class="page-header-area">
        <div class="container">
            <div class="header-body text-center">
                <h2> Join </h2>
            </div>
        </div>
    </div>
    
    <section class="pt-5 pb-5">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-md-6">
                    <div class="form-area p-5" style="background-color:#F8F9FA">
                        <form id="joinForm" method="post" class="mb-0">
                            
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">+88</span>
                                    </div>
                                    <input type="text" name="phone" id="phone" class="form-control">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 form-group">
                                    <label for="password">Password <span>(At least 8 digit)</span></label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="password" class="form-control field">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><span id="viewPassword"><i data-feather="eye"></i></span></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="cPassword">Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password" name="cPassword" id="cPassword" class="form-control field">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><span id="viewPassword"><i data-feather="eye"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            

                            <div class="form-group">
                                <label for="profession">Profession</label>
                                <input type="text" name="profession" id="profession" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="institution">Institution</label>
                                <input type="text" name="institution" id="institution" class="form-control">
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="terms" name="terms">
                                    <label class="custom-control-label" for="terms">I agree to BBIS terms and conditions</label>
                                </div>

                                <div class="d-flex justify-content-end align-items-center">
                                    <div class="preloader spinner-border text-success spinner-border-sm mr-2 hide">
                                    </div>
                                    <button type="submit" class="btn btn-success" id="joinFormBtn">Register</button>
                                </div>
                            </div>
                            <hr class="mt-4">
                            <div class="login-block text-center">
                                Already a member | <a href="login"> Login</a>
                            </div>

                        </form> 
                        
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php include 'inc/footer.php'; ?>

<script src="js/join.js"></script>
