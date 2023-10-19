<?php 
  $filepath = realpath(dirname(__FILE__));
  include_once ($filepath.'/../back-end/helpers/Helpers.php');
  include ($filepath.'/../back-end/lib/Session.php');
  include ($filepath.'/../back-end/class/og.class.php');
  $og = new OG();
  Session::authentication();
  ob_start();
  $role = explode(',', Session::get('user_role'));
  

  if(isset($_GET['page'])){
    $og_slug = $_GET['page'];
    $path = str_replace("/"," | ", str_replace("-"," ", $_GET['page']));
    $og_content = $og->getOG($og_slug);
  }else{
    $og_slug = '';
    $path = 'Home';
  }
?>


<!DOCTYPE html>
<html lang="bn">

<head>
  <title> <?php echo ucfirst($path); ?> | Bangladesh Biodiversity Information System </title>
  <meta charset="utf-8">
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/plugins/css/bootstrap.min.css" />
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/plugins/css/bootstrap-select.min.css" />
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/plugins/css/owl.carousel.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/plugins/css/croppie.css">
  <script src="<?php echo BASE_URL; ?>/plugins/js/feather.min.js"></script>

  <link rel="stylesheet" href="https://cdn.datatables.net/v/bs4/dt-1.11.5/datatables.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/plugins/css/summernote-bs4.min.css">

  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/admin/assets/css/share.css">  
  <link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE_URL; ?>/style.css" />
  <link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE_URL; ?>/assets/css/responsive.css" />
  
</head>

<body>

<!-- For all form input response -->
<div id="alert"></div>

  <div class="main-wrapper">
    <header id="header" style="border-bottom:1px solid #f5f5f5">
        <div class="mobile-header shadow-sm" style="display:none">
          <a href="#" id="toggleMenu"><i data-feather="menu"></i> </a>
          <div class="logo text-center">
            <a class="navbar-brand m-0" href="<?php echo BASE_URL; ?>">

            </a>
          </div>
        </div>
        <div class="main-menu-area">
        <div class="container">
            <nav id="main-menu" class="navbar navbar-expand-lg">
              <div class="logo">
                <a class="navbar-brand m-0" href="<?php echo BASE_URL; ?>">
                
                </a>
              </div>
              <ul id="navMenus" class="navbar-nav ml-auto">
                
              </ul>
              <ul class="navbar-nav">
              
              <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/contribute">  <span><i data-feather="folder-plus"></i> Contribute</span></a></li> 
              <?php 
                if(Session::get('user_login') == true) {  
                  if(isset($_GET['action']) && $_GET['action'] == 'logout'){
                    Session::destroy(); 
                  } 
              ?>
                <li class="nav-item dropdown">
                <a class="nav-link" href="#" data-toggle="dropdown"> <span><i data-feather="user"></i>My Account</span> <i data-feather="chevron-down"></i></a>
                  <ul class="dropdown-menu dropdown-menu-right shadow-lg">
                  <?php if(in_array('1', $role) || in_array('3', $role)){ ?>
                    <li class="nav-item"><a href="<?php echo BASE_URL; ?>/admin/index.php" class="nav-link"><i data-feather="monitor"></i> Dashboard</a> </li>
                  <?php } ?>
                    <li class="nav-item"><a href="<?php echo BASE_URL; ?>/profile" class="nav-link"><i data-feather="user"></i> My Profile</a> </li>
                    <li class="nav-item"><a href="<?php echo BASE_URL; ?>/my-contributions" class="nav-link"><i data-feather="share"></i> My Contributions</a> </li>
                    <li class="nav-item"><a href="<?php echo BASE_URL; ?>?action=logout" class="nav-link"><span><i data-feather="log-out"></i>Logout</span></a> </li>
                  </ul>
                </li>
                
              <?php }else{ ?>             
                <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/login"> <i data-feather="log-in"></i> Login</a></li>           
              <?php } ?>
                 
              </ul>
            </nav>
          </div>
        </div>
        
    </header>
