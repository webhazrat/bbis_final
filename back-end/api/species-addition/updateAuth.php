<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$filepath = realpath(dirname(__FILE__));
include ($filepath.'/../../class/speciesAddition.class.php');
include_once ($filepath.'/Session.php');
$speciesAddition = new SPECIESADDITION();

// my-contributions.php
$res = [];
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id   = isset($_GET['id']) ? $_GET['id'] : '';
    $data = array_merge($_POST, $_FILES);
    if(Session::authentication() && in_array('1', explode(',', Session::get('user_role')))){
        $data['role'] = 'admin';
        $data['author'] = Session::get('user_id');
        $speciesAddition->updateAuth($id, $data);
    }elseif(Session::authentication() && in_array('1', explode(',', Session::get('user_role'))) == false && in_array('3', explode(',', Session::get('user_role'))) && in_array($data['taxonGroup'], explode(',', Session::get('manage_group')))){
        $data['role'] = 'groupmanager';
        $data['author'] = Session::get('user_id');
        $speciesAddition->updateAuth($id, $data);
    }elseif(Session::authentication() && in_array('1', explode(',', Session::get('user_role'))) == false && in_array('3', explode(',', Session::get('user_role'))) == false){
        $data['role'] = 'general';
        $data['author'] = Session::get('user_id');
        $speciesAddition->updateAuth($id, $data);
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