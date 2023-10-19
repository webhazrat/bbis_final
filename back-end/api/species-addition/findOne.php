<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$filepath = realpath(dirname(__FILE__));
include ($filepath.'/../../class/speciesAddition.class.php');
include_once ($filepath.'/Session.php');
$speciesAddition = new SPECIESADDITION();

// species-addition.php
$res = [];
if($_SERVER["REQUEST_METHOD"] == "GET"){
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    if(Session::authentication()){
        $speciesAddition -> findOne($id);
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