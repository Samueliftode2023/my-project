<?php

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

function creaza_obiect_teme_cursanti($anul, $luna, $conectareDB) {
    $cursanti = [];
    $nume_tabel = $_SESSION['username'] . '_meetings';
    $categorii_teme = [
        'citirea_din_biblie', 'incepe_o_conversatie', 
        'fa_vizite_ulterioare', 'asistent',
        'fa_discipoli', 'explica_ti_convingerile', 'cursant_cuvantare'
    ];
    
    $sql_teme = "'" . implode("','", $categorii_teme) . "'";
    $saptamani = get_week_month($anul, $luna);
    $numar_saptamani = count($saptamani);

    for ($i = 0; $i < $numar_saptamani; $i++) {
        $saptamana_teme = [];
        $saptamana = $saptamani[$i];
        
        $sql = 'SELECT * FROM ' . $nume_tabel . ' 
                WHERE week = "' . $saptamana . '" 
                AND year = "' . $anul . '"
                AND tema IN (' . $sql_teme . ')';
        $result = mysqli_query($conectareDB, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $row['week'] = get_day($anul, $row['week']);
                $row['vestitor_id'] = get_vestitor($row['vestitor_id']);
                $saptamana_teme[] = (object)$row;
            }
        }
        array_push($cursanti, $saptamana_teme);
    }
    print_r(json_encode($cursanti));
}

function get_day($anul, $saptamana){
    $folder_intruniri = '../../../main/meetings/download-meeting/'. $anul . '/' . $saptamana . '.json';
    if(file_exists($folder_intruniri)){
        $data_intrunirii = file_get_contents($folder_intruniri);
        $content = json_decode($data_intrunirii);
        return $content->introducere->data;
    }
}


?>