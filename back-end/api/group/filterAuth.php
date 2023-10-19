<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$filepath = realpath(dirname(__FILE__));
include ($filepath.'/../../class/group.class.php');
include_once ($filepath.'/Session.php');
$group = new GROUP();

// species-group.php, users.php, species-add.php
$res = [];
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);
    if(Session::authentication()){
        if(in_array('1', explode(',', Session::get('user_role')))){
            $data['role'] = 'admin';
            $group->filterAuth($data);
        }elseif(in_array('3', explode(',', Session::get('user_role')))){
            $data['role'] = 'groupmanager';
            $data['key'] = 'groupId';
            $data['value'] = implode(',',(explode(',', Session::get('manage_group'))));
            $group->filterAuth($data);
        }else{
            $res['status'] = '401';
            $res['msg'] = 'Unauthorized';
            echo json_encode($res);
        }
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