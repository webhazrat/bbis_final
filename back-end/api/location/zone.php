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
    $areaId = isset($_GET['areaId']) ? $_GET['areaId'] : '';
    if(!empty($areaId)){
        $location -> filterZone($areaId);
    }else{
        $res['status'] = '400';
        $res['msg'] = 'Area ID required';
        echo json_encode($res);
    }
}else{
    $res['status'] = '405';
    $res['msg'] = 'Method Not Allowed';
    echo json_encode($res);
}
