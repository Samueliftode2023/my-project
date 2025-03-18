<?php
    $root = '../../../'; 
    $type_session = 'important';
    include_once $root.'included/function/php/common.php';
    include_once $root.'included/function/php/print-teme.php';
    check_session($type_session, $root, $conectareDB);
    if(isset($_POST['year']) && isset($_POST['month'])){
        $anul = $_POST['year'];
        $luna = $_POST['month'];
        creaza_obiect_teme_cursanti($anul, $luna, $conectareDB);
    }
    else{
        header('Location:'.$root.'');
        exit;
    }

?>    
