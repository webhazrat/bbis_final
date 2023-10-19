<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$filepath = realpath(dirname(__FILE__));
include ($filepath.'/../../class/species.class.php');
include_once ($filepath.'/Session.php');
$species = new SPECIES();

// species-add.php
$res = [];
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);
    if(Session::authentication() && (in_array('1', explode(',', Session::get('user_role'))) || (in_array('1', explode(',', Session::get('user_role'))) == false && in_array('3', explode(',', Session::get('user_role'))) && in_array($data['spGroup'], explode(',', Session::get('manage_group')))))){
        $data['author'] = Session::get('user_id');
        $species -> createAuth($data);
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

