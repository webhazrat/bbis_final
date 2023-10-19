<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$filepath = realpath(dirname(__FILE__));
include ($filepath.'/../../class/speciesAddition.class.php');
include_once ($filepath.'/Session.php');
$speciesAddition = new SPECIESADDITION();

// contribute.php
$res = [];
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(Session::authentication()){
        $data = array_merge($_POST, $_FILES);
        $data['author'] = Session::get('user_id');
        $speciesAddition->create($data);
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

