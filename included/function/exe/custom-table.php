<?php
    $root = '../../../'; 
    $type_session = 'important';
    include_once $root.'included/function/php/common.php';
    include_once $root.'included/function/php/custom-table.php';
    check_session($type_session, $root, $conectareDB);
    if(isset($_POST['name-table'])){
        $name_table = strtolower($_POST['name-table']);
        $name_table = mysqli_real_escape_string($conectareDB, $name_table);
        if(check_name_table($name_table, $conectareDB)){
            echo 'ok';
        }
    }
    else if(isset($_POST['structura'])){
        $name_str = strtolower($_POST['structura']);
        $name_str = mysqli_real_escape_string($conectareDB, $name_str);
        readStructuraTabel($name_str, $conectareDB);
    }
    else{
        header('Location:'.$root.'');
        exit;
    }
?>