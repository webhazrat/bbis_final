<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$filepath = realpath(dirname(__FILE__));
include ($filepath.'/../../class/species.class.php');
include_once ($filepath.'/Session.php');
$species = new SPECIES();

// species.php
$res = [];
if($_SERVER["REQUEST_METHOD"] == "PUT"){
    $id     = isset($_GET['id']) ? $_GET['id'] : '';
    $body   = file_get_contents('php://input');
    $data   = json_decode($body, true);
    if(Session::authentication() && (in_array('1', explode(',', Session::get('user_role'))) || (in_array('1', explode(',', Session::get('user_role'))) == false && in_array('3', explode(',', Session::get('user_role'))) && in_array($data['groupId'], explode(',', Session::get('manage_group')))))){
        $species->updateStatus($id, $data);
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