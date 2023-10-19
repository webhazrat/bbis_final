<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$filepath = realpath(dirname(__FILE__));
include ($filepath.'/../../class/media.class.php');
include_once ($filepath.'/Session.php');
$media = new MEDIA();

// media.php
$res = [];
if($_SERVER["REQUEST_METHOD"] === "GET"){
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $media -> findOne($id);
}else{
    $res['status'] = '405';
    $res['msg'] = 'Method Not Allowed';
    echo json_encode($res);
}