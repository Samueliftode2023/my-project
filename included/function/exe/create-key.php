<?php
    $root = '../../../'; 
    $type_session = 'important';
    include_once $root.'included/function/php/common.php';
    include_once $root.'included/function/php/create-key.php';
    check_session($type_session, $root, $conectareDB);
    if (isset($_POST['send']) && $_POST['send'] == 'ok') {
        unset($_SESSION['create-key']);
        echo 'ok';
    }
    else if(isset($_POST['read']) && $_POST['read'] == 'read'){
        if(isset($_SESSION['create-key'])){
            echo $_SESSION['create-key'];
        }
        else{
            echo 'Atentie - Adauga cel putin doua nume in tabel!';
        }
    }
    else if(isset($_POST['full-name']) && isset($_POST['privilege']) && isset($_POST['genul'])){
        $full_name = improve_name($_POST['full-name']);
        $privilege = $_POST['privilege'];
        $genul = $_POST['genul'];
        if(check_vestitor($full_name, $privilege, $genul)){
            vestitor_object($full_name, $privilege, $genul);
        }
    }
    else{
        header('Location:'.$root.'');
        exit;
    }
?>