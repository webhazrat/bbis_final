<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$filepath = realpath(dirname(__FILE__));
include ($filepath.'/../../class/group.class.php');
include_once ($filepath.'/Session.php');
$group = new GROUP();

// species-group.php
$res = [];
if($_SERVER["REQUEST_METHOD"] == "GET"){
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $group -> findOne($id);
}else{
    $res['status'] = '405';
    $res['msg'] = 'Method Not Allowed';
    echo json_encode($res);
}