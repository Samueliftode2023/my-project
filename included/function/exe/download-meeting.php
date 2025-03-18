<?php
    $root = '../../../'; 
    $type_session = 'important';
    include_once $root.'included/function/php/common.php';
    include_once $root.'included/function/php/download-meeting.php';
    check_session($type_session, $root, $conectareDB);
    if(isset($_POST['meeting-year']) && isset($_POST['meeting-month'])){
        $year = $_POST['meeting-year'];
        $month = $_POST['meeting-month'];
        download_meeting($year, $month);
    }
    else if(isset($_POST['meetings'])){
        $year = $_POST['year'];
        wirite_file_meetings($root, $year, $_POST['meetings']);
    }
    else{
        header('Location:'.$root.'');
        exit;
    }

?>    