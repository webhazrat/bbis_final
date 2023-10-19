<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    $filepath = realpath(dirname(__FILE__));
    include ($filepath.'/../../class/login.class.php');
    $login = new LOGIN();

    // login.php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);
        $login -> login($data); 
    }else{
        $res['status'] = '405';
        $res['msg'] = 'Method Not Allowed';
        echo json_encode($res);
    }
