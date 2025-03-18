<?php
    $root = '../../../'; 
    $type_session = 'important';
    include_once $root.'included/function/php/common.php';
    include_once $root.'included/function/php/privilege-herald.php';
    check_session($type_session, $root, $conectareDB);
    if(isset($_POST['filtru'])){
        echo checkbox_vestitor($_POST['filtru'], $conectareDB);
    }
    else if(isset($_POST['privilege']) && isset($_POST['vestitori'])){
        change_privileges($_POST['privilege'], $_POST['vestitori'], $conectareDB);
    }
    else{
        header('Location:'.$root.'');
        exit;
    }

?>    