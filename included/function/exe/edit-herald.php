<?php
    $root = '../../../'; 
    $type_session = 'important';
    include_once $root.'included/function/php/common.php';
    include_once $root.'included/function/php/edit-herald.php';
    check_session($type_session, $root, $conectareDB);
    if(isset($_POST['verificare-session-key'])){
        if(isset($_SESSION['new_key'])){
            echo 'activ';
        }
        else{
            echo 'inactiv';
        }
    }
    else if(isset($_POST['remove-new-key'])){
        if(isset($_SESSION['new_key'])){
            unset($_SESSION['new_key']);
            unset($_SESSION['compilate-key']);
        }
    }
    else if(isset($_POST["get-date-vestitori"])){
        $vestitor = get_data_vestitori($_POST["get-date-vestitori"]);
        echo json_encode($vestitor);
    }
    else if(isset($_POST["obtine-lista"])){
        object_list($_POST["obtine-lista"]);
    }
    else if(isset($_POST["type-input"])){
        if($_POST["type-input"] == 'select'){
            echo select_vestitor('not-all', 'id-vestitor', '', $conectareDB);
        }
        else if($_POST["type-input"] == 'checkbox'){
            echo checkbox_vestitor('new_key', $conectareDB);
        }
    }
    else if(isset($_POST["anulare"]) && isset($_POST["nume-anulare"])){
        anuleaza($_POST);
    }
    else if(isset($_POST['lista'])){
        create_new_key($_POST);
    }
    else if(isset($_POST["generate-key"])){
        generate_key_key($conectareDB);
    }
    else if(isset($_FILES['file']['name'])){
        if(exe_new_key($_FILES, $conectareDB)){
            echo 'conga';
        }
    }
    else{
        header('Location:'.$root.'');
        exit;
    }
?>