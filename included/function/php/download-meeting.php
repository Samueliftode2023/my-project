<?php
    function get_meetings_year(){
        $year = date("Y");  
        for ($i = 2024; $i <= 2025; $i++) {
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
    function download_meeting($year, $month){
        $lunile_anului_en = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
        $week = get_week_month($year, $month);
        $count_w = count($week);
        $intruniri = '';
        if(!in_array($month, $lunile_anului_en) || !is_numeric($year)){
            echo 'bad';
            return false;
        }
        for ($i=0; $i < $count_w; $i++) { 
            $url = 'https://wol.jw.org/ro/wol/meetings/r34/lp-m/'.$year.'/'.$week[$i];
            $url_check = get_headers($url);
            if($url_check[0] === 'HTTP/1.1 200 OK'){
                $caiet_intruniri = file_get_contents($url);
                $check_caiet = stripos($caiet_intruniri, '<header>');
                if($check_caiet > 1){
                    $caiet_intruniri = explode('itemCaption',$caiet_intruniri);
                    $caiet_intruniri = $caiet_intruniri[1];
                    $intruniri .= $caiet_intruniri.'|sep|';
                }
            }
        }
        if($intruniri !== ''){
            $week = json_encode($week);
            echo  $intruniri.$week;
        }
        else{
            echo 'doesnt-exist';
        }

    }    
    function wirite_file_meetings($root, $year, $meetings){
        $root = $root.'main/meetings/download-meeting/';
        if(!is_dir($root.$year)){
            mkdir($root.$year);
        }
        $date_meetings = explode('||',$meetings);
        $stocare_intruniri = '';
        $file = fopen($root.$year.'/'.$date_meetings[1].'.json',"w");
        echo fwrite($file,$date_meetings[0]);
        fclose($file);
    }                     
    function readDirector(){
        $content_display = '';
        $foldere = scandir('.', SCANDIR_SORT_DESCENDING);
        $count_foldere = count($foldere) - 2;
    
        for ($i = 0; $i < $count_foldere; $i++) { 
            if (is_dir($foldere[$i])) {
                $content = [];
                $get_file = scandir($foldere[$i], SCANDIR_SORT_DESCENDING);
                $count_file = count($get_file) - 2;
    
                for ($j = 0; $j < $count_file; $j++) { 
                    $name_file = pathinfo($get_file[$j], PATHINFO_FILENAME); 
                    array_push($content, $name_file);
                }
    
                sort($content); 
                $folder_display = ''; 
    
                for ($j = 0; $j < count($content); $j++) { 
                    $folder_display .= 
                    '<a href="' . $foldere[$i] . '/' . $content[$j] . '.json" download>
                        '.$content[$j].'
                    </a>';
                }
    
                $summary = '<summary>Intruniri descarcate pe anul ' . $foldere[$i] . '</summary>';
                $details = '<details>' . $summary . $folder_display  . '</details>';
    
                $content_display .= $details;
            }
        }
    
        echo $content_display;
    }
    
?>