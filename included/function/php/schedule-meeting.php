<?php
function get_all_week($anul,$value){
    $saptamana = '';
    $luna_ant = '';
    $lunile_anului_en = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
    $lunile_anului_ro = array('Ianuarie','Februarie','Martie','Aprilie','Mai','Iunie','Iulie','August','Septembrie','Octombrie','Noiembrie','Decembrie');
    $k = 1;
    for($i = 1; $i <= 12; $i++){
        $zile = cal_days_in_month(CAL_GREGORIAN,$i,$anul);
        $saptamana = '';
        for ($j=1; $j <= $zile; $j++) { 
            $data = $j.'/'.$i.'/'.$anul;
            $datetime = DateTime::createFromFormat('d/m/Y', $data);
            $data_name = $datetime->format('l');
            $luna = $datetime->format('M');
            $num_index = array_search($luna,$lunile_anului_en);
            $luna_ant = '';
            if($data_name == 'Thursday'){
                $monday = $j - 3;
                $sunday= $j + 3;
                if($sunday > $zile){
                    $sunday = $sunday - $zile;
                    $luna_ant = ' '.$lunile_anului_ro[$num_index];
                    $num_index += 1;
                    if($num_index == 12){
                        $num_index = 0;
                    }   
                }
                $lunile_last = $lunile_anului_ro[$num_index];
                if($monday <= 0){
                    $an_anterior = $anul;
                    $luna_anterioara = $i;
                    $num_index -= 1;
                    if($luna == 'Jan'){
                        $an_anterior = $anul - 1;
                        $luna_anterioara = 13;
                    }
                    if($num_index == -1){
                        $num_index = 0;
                    }
                    $luna_ant = ' '.$lunile_anului_ro[$num_index];
                    $calucate_day_mon = cal_days_in_month(CAL_GREGORIAN,$luna_anterioara - 1,$an_anterior);
                    $monday = $calucate_day_mon + $monday;
                }
                $select_status = '';
                if($value == $k){
                    $select_status = 'selected';
                }
                $saptamana .= '<option value="'.$k.'" '.$select_status.'>'.$monday.$luna_ant.' - '.$sunday.' '.$lunile_last.'</option>';    
                $k++;
            }
        }
        echo $saptamana;
    }
    }
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
    function create_book_meeting($root, $year, $week, $data_base){
        if(!file_exists($root.'main/meetings/download-meeting/'.$year.'/'.$week.'.json')){
            echo '<div class="mess-center">Aceasta intrunire nu a fost descarcata!</div>';
            return false;
        }
        $caiet = file_get_contents($root.'main/meetings/download-meeting/'.$year.'/'.$week.'.json');
        $caiet = json_decode($caiet);
        $intrunire = '';
        $introducere = '';
        $comori = '';
        $predicare = '';
        $crestin = '';

        // INTRODUCERE
            $data = $caiet->introducere->data;
            $capitole = $caiet->introducere->capitole;
            $cantarea_inceput = $caiet->introducere->cantarea;
            $introducere .= '<h2><span class="color-data">'.$data.' | </span><span class="color-citire">'.$capitole.'</span></h2>';
            $introducere .= '<div class="weight-sing">'.$cantarea_inceput.'</div>';
            $intrunire .= $introducere;
        //
        
        // COMORI DIN CUVÂNTUL LUI DUMNEZEU
            $comori .= '<h3 class="blue-tag"> COMORI DIN CUVÂNTUL LUI DUMNEZEU </h3>';
            $comori_extras = $caiet->comori;
            $numara_temele = count($comori_extras);
            for ($i=0; $i < $numara_temele; $i++) { 
                $comori .= '<div class="blue-tag weight-tag">'.$comori_extras[$i]->title.'</div>';
                if($comori_extras[$i]->paragraf !== ''){
                    $comori .= '<details><summary><span class="material-symbols-outlined">
                    two_pager
                    </span></summary><p>'.$comori_extras[$i]->paragraf.'</p></details>';
                }
            }
            $intrunire .= $comori;
        //
        // SA FIM MAI EFICIENTI IN PREDICARE
            $predicare .= '<h3 class="orange-tag"> SĂ FIM MAI EFICIENȚI ÎN PREDICARE </h3>';
            $predicare_extras = $caiet->predicare;
            $numara_temele = count($predicare_extras);
            for ($i=0; $i < $numara_temele; $i++) { 
                $predicare .= '<div class="orange-tag weight-tag">'.$predicare_extras[$i]->title.'</div>';
                if($predicare_extras[$i]->paragraf !== ''){
                    $predicare .= '<details><summary><span class="material-symbols-outlined">
                    two_pager
                    </span></summary><p>'.$predicare_extras[$i]->paragraf.'</p></details>';
                }
            }
            $intrunire .= $predicare;
        //
        // VIATA DE CRESTIN
            $crestin .= '<h3 class="red-tag"> VIAȚA DE CREȘTIN </h3>';
            $crestin_extras = $caiet->crestin;
            $numara_temele = count($crestin_extras);
            for ($i=0; $i < $numara_temele; $i++) { 
                $class = '';
                if($i !== 0 && $i !== $numara_temele - 1){
                    $class = 'class="red-tag weight-tag"';
                }
                else if($i == 0){
                    $class = 'class="margin-sing weight-sing"';
                }
                else if($i == $numara_temele - 1){
                    $class = 'class="weight-sing"';
                }
                $crestin .= '<div '.$class.'>'.$crestin_extras[$i]->title.'</div>';
                
                if($crestin_extras[$i]->paragraf !== ''){
                    $crestin .= '<details><summary><span class="material-symbols-outlined">
                    two_pager
                    </span></summary><p>'.$crestin_extras[$i]->paragraf.'</p></details>';
                }
            }
            $intrunire .= $crestin;
        //
        $planificare = create_schedule_meeting($year, $week, $root, $caiet, $data_base);
        echo $intrunire.'|sec|'.$planificare;
    }
    function create_schedule_meeting($year, $week, $root, $caiet, $conectareDB){
        $range = 'presedinte_viata_crestina-start';
        $selection = fill_page_meeting($year, $week, 'start', $conectareDB);
        $vestitor = select_vestitor('presedinte_viata_crestina', $range, $selection, $conectareDB);
        $presedinte = '<label>Presedinte'.$vestitor.'</label>';

        $nr_teme = 0;
        $selection = fill_page_meeting($year, $week, $nr_teme, $conectareDB);
        $range = 'rugaciune-'.$nr_teme;
        $vestitor = select_vestitor('rugaciune', $range, $selection, $conectareDB);
        $rugaciune_deschidere = '<label>Rugaciune de deschidere'.$vestitor.'</label>';

        $nr_teme = 1;
        $selection = fill_page_meeting($year, $week, $nr_teme, $conectareDB);
        $range = 'comori_cuvantare-'.$nr_teme;
        $vestitor = select_vestitor('comori_cuvantare', $range, $selection, $conectareDB);
        $comori = '<label class="blue-tag">1. Comori'.$vestitor.'</label>';

        $nr_teme = 2;
        $selection = fill_page_meeting($year, $week, $nr_teme, $conectareDB);
        $range = 'nestemate-'.$nr_teme;
        $vestitor = select_vestitor('nestemate', $range, $selection, $conectareDB);
        $nestemate = '<label class="blue-tag">2. Nestemate'.$vestitor.'</label>';

        $nr_teme = 3;
        $selection = fill_page_meeting($year, $week, $nr_teme, $conectareDB);
        $range = 'citirea_din_biblie-'.$nr_teme;
        $vestitor = select_vestitor('citirea_din_biblie', $range, $selection, $conectareDB);
        $citireBiblie = '<label class="blue-tag">3. Citirea Bibliei'.$vestitor.'</label>';

        $titluri_teme = ['Începe o conversație', 'Fă vizite ulterioare', 'Fă discipoli', 'Explică-ți convingerile', 'Cuvântare'];
        $titluri_tabel = ['incepe_o_conversatie', 'fa_vizite_ulterioare', 'fa_discipoli', 'explica_ti_convingerile', 'cursant_cuvantare'];
        $primele_trei_teme = array_slice($titluri_teme, 0, 3);
        $eficienti_in_predicare = '';
        $predicare = $caiet->predicare;
        $numara = count($predicare);
        for ($i=0; $i < $numara; $i++) { 
            $predicare_titlu = substr($predicare[$i]->title, 3);
            if(in_array($predicare_titlu, $primele_trei_teme)){
                $nr_teme++;
                $selection = fill_page_meeting($year, $week, $nr_teme, $conectareDB);
                $range_assistent = 'asistent-'.$nr_teme;
                $selection_asistent = fill_page_meeting($year, $week, $range_assistent, $conectareDB);
                $pozitie = array_search($predicare_titlu, $titluri_teme);
                $range = $titluri_tabel[$pozitie].'-'.$nr_teme;
                $vestitor = select_vestitor($titluri_tabel[$pozitie], $range, $selection, $conectareDB);
                $assistent = select_vestitor($titluri_tabel[$pozitie], $range_assistent, $selection_asistent, $conectareDB);
                $eficienti_in_predicare .= '<label class="orange-tag">'.$nr_teme.'. '.$titluri_teme[$pozitie].$vestitor.'</label>';
                $eficienti_in_predicare .= '<label class="orange-tag">Asistent'.$assistent.'</label>';
            }
            else if($predicare_titlu == 'Explică-ți convingerile'){
                $nr_teme++;
                $selection = fill_page_meeting($year, $week, $nr_teme, $conectareDB);
                $range =  $titluri_tabel[3].'-'.$nr_teme;
                $range_assistent = 'asistent-'.$nr_teme;
                $selection_asistent = fill_page_meeting($year, $week, $range_assistent, $conectareDB);
                $paragraf_type = explode('.)',$predicare[$i]->paragraf);
                $paragraf_type = explode('.',$paragraf_type[1]);
                if($paragraf_type[0] == ' Cuvântare'){
                    $pozitie = array_search('Cuvântare', $titluri_teme);
                    $vestitor = select_vestitor($titluri_tabel[$pozitie], $range, $selection, $conectareDB);
                    $eficienti_in_predicare .= '<label class="orange-tag">'.$nr_teme.'. '.$titluri_teme[$pozitie].$vestitor.'</label>';
                }
                else{
                    $pozitie = array_search('Explică-ți convingerile', $titluri_teme);
                    $vestitor = select_vestitor($titluri_tabel[$pozitie], $range, $selection, $conectareDB);
                    $assistent = select_vestitor($titluri_tabel[$pozitie], $range_assistent, $selection_asistent, $conectareDB);
                    $eficienti_in_predicare .= '<label class="orange-tag">'.$nr_teme.'. '.$titluri_teme[$pozitie].$vestitor.'</label>';
                    $eficienti_in_predicare .= '<label class="orange-tag">Asistent'.$assistent.'</label>';
                }
            }
            else if($predicare_titlu == 'Cuvântare'){
                $nr_teme++;
                $selection = fill_page_meeting($year, $week, $nr_teme, $conectareDB);
                $range =  'Cuvântare-'.$nr_teme;
                $pozitie = array_search('Cuvântare', $titluri_teme);
                $vestitor = select_vestitor($titluri_tabel[$pozitie], $range, $selection, $conectareDB);
                $eficienti_in_predicare .= '<label class="orange-tag">'.$nr_teme.'. '.$titluri_teme[$pozitie].$vestitor.'</label>';
            }
            else{
                $nr_teme++;
                $selection = fill_page_meeting($year, $week, $nr_teme, $conectareDB);
                $range =  'discutie_eficienti_in_predicare-'.$nr_teme;
                $vestitor = select_vestitor('discutie_eficienti_in_predicare', $range, $selection, $conectareDB);
                $eficienti_in_predicare .= '<label class="orange-tag">'.$nr_teme.'. '.$predicare_titlu.$vestitor.'</label>';
            }
        }

        $viata_de_crestin = '';
        $crestin = $caiet->crestin;
        $numar_teme_cresint = count($crestin) - 2;
        for ($i=1; $i < $numar_teme_cresint; $i++) { 
            $numar_teme_cresint_titlu = substr($crestin[$i]->title, 3);
            $tema = $crestin[$i]->title;
            if($numar_teme_cresint_titlu == 'Necesități locale'){
                $nr_teme++;
                $selection = fill_page_meeting($year, $week, $nr_teme, $conectareDB);
                $range =  'necesitati_locale-'.$nr_teme;
                $vestitor = select_vestitor('necesitati_locale', $range, $selection, $conectareDB);
                $viata_de_crestin .= '<label class="red-tag">'.$tema.$vestitor.'</label>';
            }
            else{
                $nr_teme++;
                $selection = fill_page_meeting($year, $week, $nr_teme, $conectareDB);
                $range =  'viata_de_crestin_teme-'.$nr_teme;
                $vestitor = select_vestitor('viata_de_crestin_teme', $range, $selection, $conectareDB);
                $viata_de_crestin .= '<label class="red-tag">'.$tema.$vestitor.'</label>';
            }
        }
        $nr_teme++;
        $selection = fill_page_meeting($year, $week, $nr_teme, $conectareDB);
        $range =  'conducator_studiu_de_carte-'.$nr_teme;
        $vestitor = select_vestitor('conducator_studiu_de_carte', $range, $selection, $conectareDB);
        $tema = $caiet->crestin[count($crestin) - 2]->title;
        $viata_de_crestin .= '<label class="red-tag">'.$tema.$vestitor.'</label>';

        $nr_teme++;
        $selection = fill_page_meeting($year, $week, $nr_teme, $conectareDB);
        $range =  'cititor_la_studiu_de_carte-'.$nr_teme;
        $vestitor = select_vestitor('cititor_la_studiu_de_carte', $range, $selection, $conectareDB);
        $viata_de_crestin .= '<label class="red-tag">Cititor studiu de carte'.$vestitor.'</label>';
        
        $nr_teme++;
        $selection = fill_page_meeting($year, $week, $nr_teme, $conectareDB);
        $range =  'rugaciune-'.$nr_teme;
        $vestitor = select_vestitor('rugaciune', $range, $selection, $conectareDB);
        $rugaciune_inchidere = '<label>Rugaciune de incheiere'.$vestitor.'</label>';
        $rugaciune_inchidere = '<div class="last-div">'.$rugaciune_inchidere.'</div>';

        $sectiune_1 = '<div>'.$presedinte.$rugaciune_deschidere.'</div>';
        $sectiune_2 = '<div>'.$comori.$nestemate.$citireBiblie.'</div>';
        $sectiune_3 = '<div>'.$eficienti_in_predicare.'</div>';
        $sectiune_4 = '<div>'.$viata_de_crestin.'</div>';
        $intrunire = $sectiune_1.$sectiune_2.$sectiune_3.$sectiune_4.$rugaciune_inchidere;
        return $intrunire;
    }

    function write_meeting_schedule($year, $week, $range, $id, $tema, $conectareDB){
        $columns = "year, week, tema, range_pagina, vestitor_id";		
        $year = mysqli_real_escape_string($conectareDB, $year);  
        $week = mysqli_real_escape_string($conectareDB, $week);  
        $id = mysqli_real_escape_string($conectareDB, $id);  
        $tema = mysqli_real_escape_string($conectareDB, $tema);  
        $range = mysqli_real_escape_string($conectareDB, $range);  
        $name_table = $_SESSION['username'].'_meetings';

        $value = "'".$year."','".$week."','".$tema."','".
        $range."','".$id."'";

        $sql = 'SELECT * FROM '.$name_table.' WHERE year = "'.$year.'" AND week = "'.$week.'" AND range_pagina = "'.$range.'"';
        $execute_search = mysqli_query($conectareDB, $sql);
        $verify_user = mysqli_num_rows($execute_search);
        if($verify_user == 0){
            insert_data($conectareDB, $name_table, $columns, $value);
        }
        else{
            $sql = 'UPDATE '.$name_table.' SET vestitor_id = "'.$id.'" WHERE year = "'.$year.'" AND week = "'.$week.'" AND range_pagina = "'.$range.'"';
            mysqli_query($conectareDB, $sql);
        }
    }

    function fill_page_meeting($year, $week, $range, $conectareDB){
        $id = 'no';
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

    function sortare_descrescatoare_saptamana($array) {
        usort($array, function($a, $b) {
            return $b['saptamana'] - $a['saptamana'];
        });
        return $array;
    }

    function get_history($year, $week, $coloana, $conectareDB){
        $istoric = [];
        $name_table = $_SESSION['username'].'_meetings';

        $sql = 'SELECT * FROM '.$name_table.' WHERE week < '.$week.' AND year = "'.$year.'" AND tema = "'.$coloana.'" AND vestitor_id != "no" ';
        $execute_search = mysqli_query($conectareDB, $sql);
        $verify_user = mysqli_num_rows($execute_search);

        if($verify_user > 0){
            while($row = mysqli_fetch_assoc($execute_search)) {
                $array_componente = ['saptamana'=>$row['week'],'nume'=>get_vestitor($row['vestitor_id'])];
                array_push($istoric, $array_componente);
            }
        }
        $istoric = sortare_descrescatoare_saptamana($istoric);
        $count_istoric = count($istoric);
        for ($i=0; $i < $count_istoric; $i++) { 
            if($i % 2 != 0){
                $par_color = 'class = "color-row"';
            }
            else{
                $par_color = '';
            }
            echo '<div '.$par_color.'>'.$istoric[$i]['saptamana'].'</div><div '.$par_color.'>'.$istoric[$i]['nume'].'</div>';
        }
    }

    function sortare_crescatoare_number($array) {
        usort($array, function($a, $b) {
            return $a['number'] - $b['number'];
        });
        return $array;
    }

    function get_suggestion($year, $week, $coloana, $conectareDB){
        $suggestion = [];
        $name_table = $_SESSION['username'].'_vestitori';
        if($coloana == 'asistent'){
            $activare = true;
            $coloana = 'incepe_o_conversatie';
        }
        $sql = 'SELECT * FROM '.$name_table.' WHERE '.$coloana.'= "da"';
        $execute_search = mysqli_query($conectareDB, $sql);
        $verify_user = mysqli_num_rows($execute_search);
        if($verify_user > 0){
            while($row = mysqli_fetch_assoc($execute_search)) {
                $array_componente = ['id'=>$row['id'],'number'=>0,'last-time'=>0];
                array_push($suggestion, $array_componente);
            }
        }
        if(isset($activare)){
            $coloana == 'asistent';
        }
        $count_suggestion = count($suggestion);
        $name_table = $_SESSION['username'].'_meetings';
        for ($i=0; $i < $count_suggestion; $i++) { 
            $id = $suggestion[$i]['id'];
            $sql = 'SELECT * FROM '.$name_table.' WHERE week <= '.$week.' AND year = "'.$year.'" AND tema = "'.$coloana.'" AND vestitor_id = "'.$id.'" ';
            $execute_search = mysqli_query($conectareDB, $sql);
            $verify_user = mysqli_num_rows($execute_search);
            if($verify_user > 0){
                while($row = mysqli_fetch_assoc($execute_search)) {
                    $suggestion[$i]['number'] = intval($suggestion[$i]['number']) + 1;
                    if(intval($suggestion[$i]['last-time']) < intval($row['week'])){
                        $suggestion[$i]['last-time'] = $row['week'];
                    }
                }
            }
            $suggestion[$i]['id'] = get_vestitor($suggestion[$i]['id']);
        }
        $suggestion = sortare_crescatoare_number($suggestion );
        for ($i=0; $i < $count_suggestion; $i++) { 
            if($i % 2 != 0){
                $par_color = 'class = "color-row"';
            }
            else{
                $par_color = '';
            }
            echo '<div '.$par_color.'>'.$suggestion[$i]['id'].'</div><div '.$par_color.'>'.$suggestion[$i]['number'].'</div><div '.$par_color.'>'.$suggestion[$i]['last-time'].'</div>';
        }
    }
?>