<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$filepath = realpath(dirname(__FILE__));
include ($filepath.'/../../class/location.class.php');
$location = new LOCATION();

// blood-requests.php
$res = [];
if($_SERVER["REQUEST_METHOD"] == "GET"){
    $districtId = isset($_GET['districtId']) ? $_GET['districtId'] : '';
    if(!empty($districtId)){
        $location -> filterArea($districtId);
    }else{
        $res['status'] = '400';
        $res['msg'] = 'District ID required';
        echo json_encode($res);
    }
}else{
    $res['status'] = '405';
    $res['msg'] = 'Method Not Allowed';
    echo json_encode($res);
}