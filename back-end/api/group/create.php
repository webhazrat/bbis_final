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
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);
    if(Session::authentication() && in_array('1', explode(',', Session::get('user_role')))){
        $data['author'] = Session::get('user_id');
        $group -> create($data);
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

