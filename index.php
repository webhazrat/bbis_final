<?php
$filepath = realpath(dirname(__FILE__));
include ($filepath.'/back-end/class/router.class.php');
$router = new ROUTER();

if(isset($_GET['page']) && !empty($_GET['page'])){
    $slug = $_GET['page']; 
    $slug_part = explode('/', $slug);
    $path = $slug;
    if((count($slug_part) > 2)){
        $router->not_found();
    }   
}else{
    $slug_part = array();
    $path = '';
}

$router->get('', 'template/home.php');

$page = array_key_exists('1', $slug_part) === false ? $router->checkUrl($path) : false;
$page2 = array_key_exists('1', $slug_part) && ($slug_part[0] == 'species' || $slug_part[0] == 'gallery' || $slug_part[0] == 'references') ? $router->checkSpeciesUrl($path) : false;
$page3 = array_key_exists('1', $slug_part) && ($slug_part[0] == 'species' || $slug_part[0] == 'group') ? $router->checkGroupUrl($path) : false;
$page4 = array_key_exists('1', $slug_part) && ($slug_part[0] == 'user') ? $router->checkUserUrl($path) : false;

if($page){
    while($value = $page->fetch_assoc()){
        if($value['isTemplate'] !== '' && $value['postType'] == 'page'){
            $router->get($value['slug'], 'template/'.$value['isTemplate']);
        }elseif($value['postType'] == 'post'){
            $router->get($value['slug'], 'single.php');
        }else{
            $router->get($value['slug'], 'page.php');
        }
    }
}elseif($page2){
    while($value = $page2->fetch_assoc()){
        $router->get('species/'.$value['spCode'], 'species-profile.php');
        $router->get('gallery/'.$value['spCode'], 'species-gallery.php');
        $router->get('references/'.$value['spCode'], 'species-references.php');
    }
}elseif($page3){
    while($value = $page3->fetch_assoc()){
        $router->get('species/'.$value['slug'], 'species-list.php');
        $router->get('group/'.$value['slug'], 'species-group.php');
    }
}elseif($page4){
    while($value = $page4->fetch_assoc()){
        $router->get('user/'.$value['userName'], 'single-people.php');
    }
}elseif(!empty($path)){
    $router->not_found();
}
require $router->run($path);