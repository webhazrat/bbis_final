<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$filepath = realpath(dirname(__FILE__));
include ($filepath.'/../../class/social.class.php');
include_once ($filepath.'/Session.php');
$social = new SOCIAL();

// profile.php, users.php
$res = [];
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);
    if(Session::authentication() && in_array('1', explode(',', Session::get('user_role'))) && $data['value'] != '-1'){
        $social->filterAuth($data);
    }elseif(Session::authentication() && $data['value'] == '-1'){
        $data['key'] = 'memberId';
        $data['value'] = Session::get('user_id');
        $social->filterAuth($data);
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