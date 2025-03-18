<?php
    $root = '../../../'; 
    $type_session = 'important';
    include_once $root.'included/function/php/common.php';
    include_once $root.'included/function/php/print-meeting.php';
    check_session($type_session, $root, $conectareDB);
    if(isset($_POST['year']) && isset($_POST['month'])){
        $year = $_POST['year'];
        $month = $_POST['month'];
        print_meeting('no', $year, $month, $_SESSION['key'], $conectareDB, $root);
    }
    else if(isset($_POST['year-fil']) && isset($_POST['month-fil'])){
        $year = $_POST['year-fil'];
        $month = $_POST['month-fil'];
        print_r(json_encode(get_week_month($year, $month)));
    }
    else if(isset($_POST['filter'])){
        $obiectFilter = $_POST['filter'];
        print_meeting($obiectFilter, '', '', $_SESSION['key'], $conectareDB, $root);
    }
    else{
        header('Location:'.$root.'');
        exit;
    }

?>    

