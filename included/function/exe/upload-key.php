<?php
    $root = '../../../'; 
    $type_session = 'important';
    include_once $root.'included/function/php/common.php';
    include_once $root.'included/function/php/upload-key.php';
    check_session($type_session, $root, $conectareDB);
    if(isset($_FILES['file']['name'])){
        if(check_file($_FILES['file']['name'])){
            if(check_inside_file($_FILES['file']['tmp_name'])){
                if(check_activity($_SESSION['username'], 'users', $conectareDB) == 0){
                    set_key($_FILES['file']['tmp_name'], $_FILES['file']['name'], $_SESSION['username'], 'users', $conectareDB);
                    create_session_key($_FILES['file']['tmp_name']);
                    echo 'ok';
                    exit;
                }
                else if(check_key($_FILES['file']['name'], $_FILES['file']['tmp_name'], $_SESSION['username'], 'users', $conectareDB)){
                    create_session_key($_FILES['file']['tmp_name']);
                    echo 'ok';
                    exit;
                }
            }
        }
    }
    else{
        header('Location:'.$root.'');
        exit;
    }
?>