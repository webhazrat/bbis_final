<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$filepath = realpath(dirname(__FILE__));
include ($filepath.'/../../class/menu.class.php');
include_once ($filepath.'/Session.php');
$menu = new MENU();

// menus.php
$res = [];
if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(Session::authentication() && in_array('1', explode(',', Session::get('user_role')))){
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);
        if($data !== null){
            $menu->createMenuItem($data);
        }else{
            $res['status'] = '400';
            $res['msg'] = 'Data required';
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
