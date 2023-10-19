<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$filepath = realpath(dirname(__FILE__));
include ($filepath.'/../../class/user.class.php');
include_once ($filepath.'/Session.php');
$user = new USER();

// profile.php
$res = [];
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $body   = file_get_contents('php://input');
    $data   = json_decode($body, true);
    if(Session::authentication()){
        $data['author'] = Session::get('user_id');
        $user->profilePhoto($data);
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


