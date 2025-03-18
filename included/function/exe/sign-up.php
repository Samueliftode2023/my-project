<?php
    $root = '../../../'; 
    $type_session = 'unimportant';
    include_once $root.'included/function/php/common.php';
    include_once $root.'included/function/php/sign-up.php';
    check_session($type_session, $root, $conectareDB);
    if(isset($_POST['username']) && isset($_POST['password'])){
        $secretKey  = '6LevXBAgAAAAAEuIYCKl8V_ED2hw6cu5u1KEmuCu';
        if(check_robot($secretKey)){
            $username = strtolower($_POST['username']);
            $password = $_POST['password'];
            $password_check = $_POST['confirm-password'];
    
            if(check_data_access($username, $password)){
                if(create_new_account('users', $username, $password, $password_check, $conectareDB, $root)){
                    create_connect_session($username, $password, $root);
                }
            }
        }
    }
    else{
        header('Location:'.$root.'');
        exit;
    }
?>