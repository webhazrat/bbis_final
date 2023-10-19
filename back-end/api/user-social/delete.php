<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$filepath = realpath(dirname(__FILE__));
include ($filepath.'/../../class/social.class.php');
include_once ($filepath.'/Session.php');
$social = new SOCIAL();

// profile.php
$res = [];
if($_SERVER["REQUEST_METHOD"] == "DELETE"){
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    if(Session::authentication()){
        $userId = Session::get('user_id');
        $social -> delete($id, $userId);
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
