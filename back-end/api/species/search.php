<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$filepath = realpath(dirname(__FILE__));
include ($filepath.'/../../class/species.class.php');
include_once ($filepath.'/Session.php');
$species = new SPECIES();

// home.php
$res = [];
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);
    $species->search($data);
}else{
    $res['status'] = '405';
    $res['msg'] = 'Method Not Allowed';
    echo json_encode($res);
}