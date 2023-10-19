<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$filepath = realpath(dirname(__FILE__));
include ($filepath.'/../../class/user.class.php');
include_once ($filepath.'/Session.php');
$user = new USER();

// profile.php
$res = [];
if($_SERVER["REQUEST_METHOD"] == "GET"){
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    if(empty($id) && Session::authentication()){
        $user -> findOne(Session::get('user_id'));  
    }elseif(!empty($id) && Session::authentication() && Session::get('user_id') == $id){
        $user -> findOne($id);
    }elseif(!empty($id) && Session::authentication() && (in_array('1', explode(',', Session::get('user_role'))))){
        $user -> findOne($id); 
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