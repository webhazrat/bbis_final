<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$filepath = realpath(dirname(__FILE__));
include ($filepath.'/../../class/speciesAddition.class.php');
include_once ($filepath.'/Session.php');
$speciesAddition = new SPECIESADDITION();

// home.php, species-profile.php, contributors.php
$res = [];
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);
    $data['status'] = '8';
    $speciesAddition->filter($data);
}else{
    $res['status'] = '405';
    $res['msg'] = 'Method Not Allowed';
    echo json_encode($res);
}