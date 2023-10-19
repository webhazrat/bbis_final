<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$filepath = realpath(dirname(__FILE__));
include ($filepath.'/../../class/user.class.php');
include_once ($filepath.'/Session.php');
$user = new USER();

// reset-password.php
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);
    if(Session::authentication()){
        $data['id'] = Session::get('user_id');
        $data['verifyToken'] = '';
        $data['verifyEmail'] = '';
        $data['access'] = 'session';
    }else{
        $data['id'] = '';
        $data['oldPassword'] = '';
        $data['access'] = '';
    }
    $user->changePassword($data); 
}else{
    $res['status'] = '405';
    $res['msg'] = 'Method Not Allowed';
    echo json_encode($res);
}