<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$filepath = realpath(dirname(__FILE__));
include ($filepath.'/../../class/user.class.php');
include_once ($filepath.'/Session.php');
$user = new USER();

// user.php
$res = [];
if($_SERVER["REQUEST_METHOD"] == "DELETE"){
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    if(Session::authentication() && in_array('1', explode(',', Session::get('user_role')))){
        $user -> delete($id);
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
