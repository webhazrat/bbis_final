<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$filepath = realpath(dirname(__FILE__));
include ($filepath.'/../../class/user.class.php');
include_once ($filepath.'/Session.php');
$user = new USER();

// join.php
$res = [];
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $body   = file_get_contents('php://input');
    $data   = json_decode($body, true);
    $user->create($data);
}else{
    $res['status'] = '405';
    $res['msg'] = 'Method Not Allowed';
    echo json_encode($res);
}

