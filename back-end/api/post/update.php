<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$filepath = realpath(dirname(__FILE__));
include ($filepath.'/../../class/post.class.php');
include_once ($filepath.'/Session.php');
$post = new POST();

// post-edit.php
$res = [];
if($_SERVER["REQUEST_METHOD"] == "PUT"){
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);
    if(Session::authentication() && in_array('1', explode(',', Session::get('user_role')))){
        $post -> update($id, $data);
    }else{
        $res['status'] = '401';
        $res['msg'] = 'Unauthorized';
        echo json_encode($res);
    }
}else{
    $res['status'] = '405';
    $res['msg'] = 'Method Not Allowed';
    echo json_encode($res);
}

