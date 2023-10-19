<?php 
    $filepath = realpath(dirname(__FILE__));
    include ($filepath.'/../../back-end/lib/Session.php');
    include ($filepath.'/../../back-end/helpers/Helpers.php');
    Session::checkSession();
    ob_start();
    $session_roles = explode(',', Session::get('user_role'));

    if(in_array('1', $session_roles) || in_array('3', $session_roles)){ }else{
        header("location: ../profile");
    }
 ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="../plugins/css/bootstrap.min.css">
    <link rel="stylesheet" href="../plugins/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="../plugins/css/summernote-bs4.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/v/bs4/dt-1.11.5/datatables.min.css">
    <link rel="stylesheet" href="assets/css/share.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico" />
    
    <script src="../plugins/js/feather.min.js"></script>    
</head>

<body>

    
    <div class="main-wrapper">
        <div class="full-content">
            <div class="left-sidebar">
                <span class="menu-toggle"><span></span><span></span><span></span></span>
                <div class="logo-area text-center">
                    <a href="index.php"><img src="assets/images/logo.png" alt=""> ITWindow</a>
                </div>
                <div class="vertical-menu">
                    <ul>
                    
                    <?php if(in_array('1', $session_roles) || in_array('3', $session_roles)){ ?>

                        <li> <a href="index.php"><i data-feather="monitor"></i> Dashboard </a></li>
                        
                        <li class="sub-menu">
                            <a href="#"><i data-feather="folder"></i> Media <i data-feather="chevron-down" class="right"></i></a>
                            <ul>
                                <li><a href="media.php">Library</a></li>
                                <li><a href="media-new.php">Add New</a></li>
                            </ul>
                        </li>

                        <li class="sub-menu">
                            <a href="#"><i data-feather="image"></i> Sliders <i data-feather="chevron-down" class="right"></i></a>
                            <ul>
                                <li><a href="posts.php?post-type=slider">All Slider</a></li>
                                <li><a href="post-new.php?post-type=slider">Add New</a></li>
                            </ul>
                        </li>

                        <li class="sub-menu">
                            <a href="#"><i data-feather="file-plus"></i> Posts <i data-feather="chevron-down" class="right"></i></a>
                            <ul>
                                <li><a href="posts.php?post-type=post">All Posts</a></li>
                                <li><a href="post-new.php?post-type=post">Add New</a></li>
                                <li><a href="categories.php">Categories</a></li>
                            </ul>
                        </li>

                        <li class="sub-menu">
                            <a href="#"><i data-feather="box"></i> Missions <i data-feather="chevron-down" class="right"></i></a>
                            <ul>
                                <li><a href="posts.php?post-type=mission">All Missions</a></li>
                                <li><a href="post-new.php?post-type=mission">Add New</a></li>
                            </ul>
                        </li>

                        <li class="sub-menu">
                            <a href="#"><i data-feather="help-circle"></i> Faq's <i data-feather="chevron-down" class="right"></i></a>
                            <ul>
                                <li><a href="posts.php?post-type=faq">All Faq</a></li>
                                <li><a href="post-new.php?post-type=faq">Add New</a></li>
                            </ul>
                        </li>

                        <li class="sub-menu">
                            <a href="#"><i data-feather="users"></i> Partners <i data-feather="chevron-down" class="right"></i></a>
                            <ul>
                                <li><a href="posts.php?post-type=partner">All Partners</a></li>
                                <li><a href="post-new.php?post-type=partner">Add New</a></li>
                            </ul>
                        </li>

                        <li class="sub-menu">
                            <a href="#"><i data-feather="book-open"></i> Pages <i data-feather="chevron-down" class="right"></i></a>
                            <ul>
                                <li><a href="posts.php?post-type=page">All Pages</a></li>
                                <li><a href="post-new.php?post-type=page">Add New</a></li>
                            </ul>
                        </li>

                        <li class="sub-menu">
                            <a href="#"><i data-feather="layers"></i> Appearance <i data-feather="chevron-down" class="right"></i></a>
                            <ul>
                                <li><a href="cutomize.php">Customize</a></li>
                                <li><a href="menus.php">Menus</a></li>
                            </ul>
                        </li> 

                        <li class="sub-menu">
                            <a href="#"><i data-feather="file-text"></i> Species <i data-feather="chevron-down" class="right"></i></a>
                            <ul>
                                <?php if(in_array('1', $session_roles)){?> 
                                    <li><a href="species-group.php">Group</a></li>
                                <?php }?>
                                <li><a href="species-add.php">Add Species</a></li>
                                <li><a href="species.php">Species</a></li>
                            </ul>
                        </li> 
                        <?php if(in_array('1', $session_roles)){?>
                            <li class="sub-menu">
                                <a href="#"><i data-feather="users"></i> Users <i data-feather="chevron-down" class="right"></i></a>
                                <ul>
                                    <li><a href="people-type.php">People Type</a></li>
                                    <li><a href="users.php">All Users</a></li>
                                </ul>
                            </li> 
                        <?php }?>
                                            
                    <?php }?>
                                           
                    </ul>
                </div>
            </div>

            <?php if(isset($_GET['action']) && $_GET['action'] == 'logout'){
                Session::destroy();
            } ?>
            <div class="main-body">
                <div class="header-nav">
                    <ul>
                        <li> <a class="navbar-brand mr-0" href="<?php echo BASE_URL; ?>" target="_blank"><i data-feather="home"></i> </a></li>                        
                        <li>
                            <a href="#" data-toggle="dropdown"><i data-feather="user"></i> <span class="ml-2 mr-1"><?php echo Session::get('user_name'); ?></span> <i data-feather="chevron-down"></i></a>
                            <div class="dt-dropdown dropdown-menu dropdown-menu-right">
                                <a href="<?php echo BASE_URL; ?>/profile"><i data-feather="user"></i> Profile</a>
                                <a href="?action=logout"><i data-feather="log-out"></i> Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
                