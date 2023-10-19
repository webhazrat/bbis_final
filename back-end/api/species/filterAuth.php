<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$filepath = realpath(dirname(__FILE__));
include ($filepath.'/../../class/species.class.php');
include_once ($filepath.'/Session.php');
$species = new SPECIES();

// species.php, my-contributions.php
$res = [];
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);
    $data['key'] = $data && array_key_exists('key', $data) ? $data['key'] : '';
    $data['value'] = $data && array_key_exists('value', $data) ? $data['value'] : '';

    if(Session::authentication()){
        if(in_array('1', explode(',', Session::get('user_role')))){
            $data['role'] = 'admin';
            if($data['key'] === 'author'){
                $data['value'] = Session::get('user_id');
            }
            if($data['key'] === ''){
                $data['key'] = 'all';
                $data['value'] = 'all';
            }
        }elseif(in_array('3', explode(',', Session::get('user_role')))){
            $data['role'] = 'groupmanger';
            if($data['key'] === 'author'){
                $data['value'] = Session::get('user_id');
            }else{
                $data['key'] = 'manageGroup';
                $data['value'] = implode(',',(explode(',', Session::get('manage_group'))));
            }
        }else{
            $data['role'] = 'general';
            $data['key'] = 'author';
            $data['value'] = Session::get('user_id');
        }
        $species->filterAuth($data);
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