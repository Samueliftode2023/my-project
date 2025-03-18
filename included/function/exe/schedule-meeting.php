<?php
    $root = '../../../'; 
    $type_session = 'important';
    include_once $root.'included/function/php/common.php';
    include_once $root.'included/function/php/schedule-meeting.php';
    check_session($type_session, $root, $conectareDB);
    if(isset($_POST['suggestion']) && isset($_POST['curent-week']) && isset($_POST['curent-year']) && isset($_POST['column'])){
        $year = $_POST['curent-year'];
        $week = $_POST['curent-week'];
        $coloana = $_POST['column'];
        get_suggestion($year, $week, $coloana, $conectareDB);
    }
    else if(isset($_POST['curent-week']) && isset($_POST['curent-year']) && isset($_POST['column'])){
        $year = $_POST['curent-year'];
        $week = $_POST['curent-week'];
        $coloana = $_POST['column'];
        get_history($year, $week, $coloana, $conectareDB);
    }
    else if(isset($_POST['year-meeting']) && isset($_POST['week']) && isset($_POST['range']) && isset($_POST['id']) && isset($_POST['tema'])){
       $year = $_POST['year-meeting'];
       $week = $_POST['week'];
       $range = $_POST['range'];
       $id = $_POST['id'];
       $tema = $_POST['tema'];
       write_meeting_schedule($year, $week, $range, $id, $tema, $conectareDB);
    }
    else if(isset($_POST['year']) && isset($_POST['week'])){
        $year = $_POST['year'];
        $week = $_POST['week'];
        create_book_meeting($root, $year, $week, $conectareDB);
    }
    else{
        header('Location:'.$root.'');
        exit;
    }

?>    