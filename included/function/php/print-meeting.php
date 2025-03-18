<?php
    function get_meetings_year(){
        $year = date("Y");  
        for ($i = 2024; $i <= 2026; $i++) {
            $select_status = '';
            if($i == $year){
                $select_status = 'selected';
            } 
            echo "<option ".$select_status." value='".$i."'>".$i."</option>";
        }    
    }         
    function get_meetings_months(){
        $lunile_anului_ro = array('Ianuarie','Februarie','Martie','Aprilie','Mai','Iunie','Iulie','August','Septembrie','Octombrie','Noiembrie','Decembrie');
        $lunile_anului_en = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
        $data = date('d/m/Y');
        $datetime = DateTime::createFromFormat('d/m/Y', $data);
        $data_name = $datetime->format('l');
        $luna = $datetime->format('M');
        for ($i=0; $i <= 11; $i++) { 
            $select_status = '';
            if($luna == $lunile_anului_en[$i]){
                $select_status = 'selected';
            }
            echo "<option ".$select_status." value='".$lunile_anului_en[$i]."'>".$lunile_anului_ro[$i]."</option>";
        }
    }
    function get_week_month($year, $month){
        $num_week = '';
        $k = 1;
        for ($i=1; $i <= 12; $i++) { 
            $zile = cal_days_in_month(CAL_GREGORIAN,$i,$year);
            for ($j=1; $j <= $zile; $j++) { 
                $data = $j.'/'.$i.'/'.$year;
                $datetime = DateTime::createFromFormat('d/m/Y', $data);
                $data_name = $datetime->format('l');
                $luna = $datetime->format('M');
                if($data_name == 'Thursday'){
                    if ($luna == $month) {
                        $num_week .= $k.',';
                    }
                    $k++;
                }
            }
        }
        $num_week = rtrim($num_week,',');
        $num_week = explode(',',$num_week);
        return $num_week;
    }

    function get_name_id($idul, $vestitori){
        $contents = $vestitori;
        $container = $contents->vestitori;
        $count_array = count($container);
        for ($i=0; $i < $count_array; $i++) { 
            $id = $container[$i]->id;
            $name = $container[$i]->nume;
            if($id == $idul){
                return $name;
            }
        }
    }
    function get_meeting_redy($year, $week, $range, $conectareDB){
        $id = '';
        $name_table = $_SESSION['username'].'_meetings';
        $sql = 'SELECT * FROM '.$name_table.' WHERE year = "'.$year.'" AND week = "'.$week.'" AND range_pagina = "'.$range.'"';
        $execute_search = mysqli_query($conectareDB, $sql);
        $verify_user = mysqli_num_rows($execute_search);
        if($verify_user > 0){
            $row_user =  mysqli_fetch_assoc($execute_search);
            $id = $row_user['vestitor_id'];
        }
        return $id;
    }
    function regroupAndSortByYear($weeks) {
        $groupedByYear = [];
    
        // Gruparea săptămânilor după an
        foreach ($weeks as $item) {
            foreach ($item as $year => $week) {
                if (!isset($groupedByYear[$year])) {
                    $groupedByYear[$year] = [];
                }
                $groupedByYear[$year][] = $week;
            }
        }
    
        // Sortare chei (ani) în ordine crescătoare
        ksort($groupedByYear);
    
        // Sortare valorilor (săptămânilor) în fiecare an
        foreach ($groupedByYear as $year => $weeks) {
            sort($groupedByYear[$year]);
        }
    
        return $groupedByYear;
    }

    function print_meeting($obiectFilter, $year, $month, $vestitori, $conectareDB, $root){
        if(is_array($obiectFilter)){
            $weeks = regroupAndSortByYear($obiectFilter);
            $year = array_keys($weeks);

            for ($i=0; $i < count($year); $i++) { 
                $saptW = $weeks[$year[$i]];
                meetings_execute($year[$i], $saptW, $vestitori, $conectareDB, $root);
            }
        }
        else{
            $weeks = get_week_month($year, $month);
            meetings_execute($year, $weeks, $vestitori, $conectareDB, $root);
        }
    }

    function meetings_execute($year, $weeks, $vestitori, $conectareDB, $root){
        $count_weeks = count($weeks);
        $intruniri = '';
        for ($i=0; $i < $count_weeks; $i++) { 
            $ran = 0;
            $intrunire = '';
            $sectiuni = ['COMORI DIN CUVÂNTUL LUI DUMNEZEU', 'SĂ FIM MAI EFICIENȚI ÎN PREDICARE', 'VIAȚA DE CREȘTIN'];
            $parti = ['introducere', 'comori', 'predicare','crestin'];

            if(file_exists($root.'main/meetings/download-meeting/'.$year.'/'.$weeks[$i].'.json')){
                $file_meetings = file_get_contents($root.'main/meetings/download-meeting/'.$year.'/'.$weeks[$i].'.json');
                $file_meetings = json_decode($file_meetings);

                $data = $file_meetings->introducere->data;
                $capitole = $file_meetings->introducere->capitole;
                $get_name = get_meeting_redy($year, $weeks[$i], 'start', $conectareDB);
                $get_name = get_name_id($get_name, $vestitori);
                $get_presedite = 'Presedinte: '.$get_name;
                $antet = '<h1 class="impartire"><div>'.$data.' | <span class="capitole">'.$capitole.'</span></div><div class="teme-info">'.$get_presedite.'</div></h1>';
                
                $get_name = get_meeting_redy($year, $weeks[$i], $ran, $conectareDB);
                $get_name = get_name_id($get_name, $vestitori);
                $get_rugaciune = 'Rugaciune: '.$get_name;
                $start = explode('|',$file_meetings->introducere->cantarea);
                $cantare = '<div class="neutral impartire"><div>'.$start[0].'</div><div class="teme-info">'.$get_rugaciune.'</div></div>';
                $cantare .= '<div class="neutral">'.$start[1].'</div>';
                $intrunire .= $antet.$cantare;

                $intrunire .= '<div class="section gray">'.$sectiuni[0].'</div>';
                $teme = $file_meetings->comori;
                $count_teme = count($teme);
                for ($j=0; $j < $count_teme; $j++) {
                    $ran++;
                    $get_name = get_meeting_redy($year, $weeks[$i], $ran, $conectareDB);
                    $get_name = get_name_id($get_name, $vestitori);

                    $time = $teme[$j]->paragraf;
                    $time = explode('(',$time);
                    $time = explode(')',$time[1]);
                    $time = '<span> ('.$time[0].')</span>';
                    $intrunire .= '<div class="gray-section neutral impartire"><div>'.$teme[$j]->title.$time.'</div><div class="teme-info">'.$get_name.'</div></div>';
                }

                $intrunire .= '<div class="section orange">'.$sectiuni[1].'</div>';
                $teme = $file_meetings->predicare;
                $count_teme = count($teme);
                for ($j=0; $j < $count_teme; $j++) { 
                    $ran++;
                    $get_name = get_meeting_redy($year, $weeks[$i], $ran, $conectareDB);
                    $get_name = get_name_id($get_name, $vestitori);

                    if(get_meeting_redy($year, $weeks[$i], 'asistent-'.$ran, $conectareDB)){
                        $get_assitent = get_meeting_redy($year, $weeks[$i], 'asistent-'.$ran, $conectareDB);
                        $get_assitent = get_name_id($get_assitent, $vestitori);
                        $get_name .= ' / '.$get_assitent;
                    }

                    $time = $teme[$j]->paragraf;
                    $time = explode('(',$time);
                    $time = explode(')',$time[1]);
                    $time = '<span> ('.$time[0].')</span>';
                    $intrunire .= '<div class="orange-section neutral impartire"><div>'.$teme[$j]->title.$time.'</div><div class="teme-info">'.$get_name.'</div></div>';
                }

                $intrunire .= '<div class="section red">'.$sectiuni[2].'</div>';
                $teme = $file_meetings->crestin;
                $count_teme = count($teme);
                $intrunire .= '<div class="neutral">'.$teme[0]->title.'</div>';
                $ran++;
                for ($j=1; $j < $count_teme-2; $j++) { 
                    $get_name = get_meeting_redy($year, $weeks[$i], $ran, $conectareDB);
                    $get_name = get_name_id($get_name, $vestitori);
                    $ran++;
                    
                    $time = $teme[$j]->paragraf;
                    $time = explode('(',$time);
                    $time = explode(')',$time[1]);
                    $time = '<span> ('.$time[0].')</span>';
                    $intrunire .= '<div class="red-section neutral impartire"><div>'.$teme[$j]->title.$time.'</div><div class="teme-info">'.$get_name.'</div></div>';
                }
                // CODUCATOR STUDIU DE CARTE SI CITITOR
                    $get_name = get_meeting_redy($year, $weeks[$i], $ran, $conectareDB);
                    $get_name = get_name_id($get_name, $vestitori);
                    $ran++;

                    $get_cititor = get_meeting_redy($year, $weeks[$i], $ran, $conectareDB);
                    $get_cititor = get_name_id($get_cititor, $vestitori);

                    $time = $teme[$count_teme - 2]->paragraf;
                    $time = explode('(',$time);
                    $time = explode(')',$time[1]);
                    $time = '<span> ('.$time[0].')</span>';
                    $intrunire .= '<div class="red-section neutral impartire"><div>'.$teme[$count_teme - 2]->title.$time.'</div><div class="teme-info">'.$get_name.' / '.$get_cititor.'</div></div>';
                //
                // RUGACIUNEA DE LA FINAL
                    $ran++;
                    $get_name = get_meeting_redy($year, $weeks[$i], $ran, $conectareDB);
                    $get_name = get_name_id($get_name, $vestitori);
                    $get_rugaciune_final = 'Rugaciune: '.$get_name;

                    $final = explode('|',$teme[$count_teme-1]->title);
                    $intrunire .= '<div class="neutral">'.$final[0].'</div>';
                    $intrunire .= '<div class="neutral impartire"><div>'.$final[1].'</div><div class="teme-info">'.$get_rugaciune_final.'</div></div>';
                    $intruniri .= '<div class="meeting">'.$intrunire.'</div>';
                //
            }
        }
        echo $intruniri;
    }

    function getNumberOfWeeksInYear($year) {
        // Prima zi a anului
        $startDate = new DateTime("$year-01-01");
    
        // Ultima zi a anului
        $endDate = new DateTime("$year-12-31");
    
        // Se setează săptămâna începând de luni
        $startDate->modify('monday this week');
        $endDate->modify('sunday this week');
    
        // Calculăm numărul săptămânilor
        $interval = $startDate->diff($endDate);
        $weeks = floor($interval->days / 7) + 1; // Adăugăm 1 pentru a include ultima săptămână
    
        return $weeks;
    }
    
    function redaToateSaptamanile($year){
        $nr = getNumberOfWeeksInYear($year);
        for ($i=1; $i < $nr; $i++) { 
            echo '<div id="week-' . $year . '-' . $i . '">' . $i . '</div>';
        }
    }
?>