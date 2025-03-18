<?php
    function change_privileges($privileges, $vestitori, $conectareDB){
        $privilegii_table = ['rugaciune', 'presedinte_viata_crestina', 'comori_cuvantare', 'nestemate', 'citirea_din_biblie', 'incepe_o_conversatie', 
        'fa_vizite_ulterioare', 'fa_discipoli', 'explica_ti_convingerile', 'cursant_cuvantare', 'discutie_eficienti_in_predicare', 
        'viata_de_crestin_teme', 'necesitati_locale', 'conducator_studiu_de_carte', 'cititor_la_studiu_de_carte', 'cuvantare_de_weekend', 
        'presedinte_la_final_de_saptamana', 'cititor_la_turn', 'om_de_ordine'];
        $vestitori = json_decode($vestitori);
        $privileges = json_decode($privileges);
        $numarVestitori = count($vestitori);
        $countPrivilege = count($privilegii_table);
        
        for ($i=0; $i < $numarVestitori; $i++) { 
            $sql = 'SELECT * FROM '.$_SESSION['username'].'_vestitori WHERE id = "'.$vestitori[$i].'"';
            $query = mysqli_query($conectareDB, $sql);
            $exist_user = mysqli_num_rows($query);
            if($exist_user > 0){
                for ($j=0; $j < $countPrivilege; $j++) { 
                    $sql = 'UPDATE '.$_SESSION['username'].'_vestitori SET '.$privilegii_table[$j].'="'.mysqli_real_escape_string($conectareDB,$privileges[$j]).'"  WHERE id="'.$vestitori[$i].'"';
                    mysqli_query($conectareDB, $sql);
                    echo $vestitori[$i];
                }
            }
        }
    }
?>