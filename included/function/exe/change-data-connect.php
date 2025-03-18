<?php
    $root = '../../../'; 
    $type_session = 'important';
    include_once $root.'included/function/php/common.php';
    include_once $root.'included/function/php/change-data-connect.php';
    check_session($type_session, $root, $conectareDB);
    if(isset($_POST['username'])){
        $new_user = strtolower($_POST['username']);
        $new_user = mysqli_real_escape_string($conectareDB, $new_user);
        
        if(checkUsername($new_user, $conectareDB)){
            changeUsername($new_user, $conectareDB);
        }
    }
    else if(isset($_POST['password'])){
        $_POST['password'] = mysqli_real_escape_string($conectareDB, $_POST['password']);
        if(checkPasswords($_POST['password'], $_POST['current-password'], $_POST['confirm-password'], $conectareDB)){
            changePassword($_POST['password'], $conectareDB);
        }
    }
    else if(isset($_POST['new-keys-name'])){
        $_POST['new-keys-name'] = mysqli_real_escape_string($conectareDB, $_POST['new-keys-name']);
        if(checkNameKey($_POST['new-keys-name'], $conectareDB)){
            changeNameKey($_POST['new-keys-name'], $conectareDB);
        }
    }
    else{
        header('Location:'.$root.'');
        exit;
    }
?>   