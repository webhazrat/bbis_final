<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$filepath = realpath(dirname(__FILE__));
include ($filepath.'/../../class/menu.class.php');
$menu = new MENU();

// header.php
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);
    $menu->navMenus($data);
}else{
    $res = [];
    $res['status'] = '405';
    $res['msg'] = 'Method Not Allowed';
    echo json_encode($res);
}
