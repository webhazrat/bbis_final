<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$filepath = realpath(dirname(__FILE__));
include ($filepath.'/../../class/media.class.php');
include ($filepath.'/Session.php');
$media = new MEDIA();

// verified
if(Session::authentication() && (in_array('1', explode(',', Session::get('user_role'))) || in_array('3', explode(',', Session::get('user_role'))))){
    try {
        if (
            !isset($_FILES['file']['error']) ||
            is_array($_FILES['file']['error'])
        ) {
            throw new RuntimeException('Invalid parameters.');
        }
    
        switch ($_FILES['file']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
            default:
                throw new RuntimeException('Unknown errors.');
        }
    
        $author = Session::get('user_id');
        $file_name = pathinfo($_FILES['file']['name'])['filename'];
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $i = 1;
        while(file_exists('../../../uploads/'.$file_name.".".$ext))
        {           
            $actual_name = $file_name.'-'.$i;
            $file_name = $actual_name;
        }
        $filename = $file_name.'.'.$ext;
        
        $mediapath = sprintf('../../../uploads/%s%s', $file_name, '.'.$ext);
        $media->create($filename, $author);
    
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $mediapath )) {
            throw new RuntimeException('Failed to move uploaded file.');
        }
    
        // All good, send the response
        echo json_encode([
            'status' => 'OK',
            'path' => $mediapath
        ]);
    
    } catch (RuntimeException $e) {
        // Something went wrong, send the err message as JSON
        http_response_code(400);
    
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
    
}else{
    $res['status'] = '401';
    $res['msg'] = 'Unauthorized';
    echo json_encode($res);
}