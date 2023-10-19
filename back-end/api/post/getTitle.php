<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$filepath = realpath(dirname(__FILE__));
include ($filepath.'/../../class/post.class.php');
include_once ($filepath.'/Session.php');
$post = new POST();

// verified
$res = [];
if($_SERVER["REQUEST_METHOD"] == "GET"){
    $slug = isset($_GET['slug']) ? $_GET['slug'] : '';
    if(!empty($slug)){
        $post -> getTitle($slug);
    }else{
        $res['status'] = '400';
        $res['msg'] = 'Slug required';
        echo json_encode($res);
    }
}else{
    $res['status'] = '405';
    $res['msg'] = 'Method Not Allowed';
    echo json_encode($res);
}