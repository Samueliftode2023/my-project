<?php
function check_file($name_file){
    $explode_file_name = explode('.',$name_file);
    $termination = strtolower(end($explode_file_name)) ;
    if ($termination !== 'json') {
        echo 'Acest tip de fisier nu este accept!';
        return false;
    }
    if($_FILES['file']['error'] !== 0){
        echo 'Ceva nu a functionat, te rugam sa reincarci!';
        return false;
    }
    if($_FILES['file']['size'] > 10000){
        echo 'Marimea fisierului nu este acceptata!';
        return false;
    }
    return true;
}
function check_twin_id($obiect, $id){
    $count_array = count($obiect);
    $double = 0;
    for ($i=0; $i < $count_array; $i++) { 
        if($id == $obiect[$i]->id){
            $double++;
        }
    }
    if($double > 1){
        return true;
    }
    else{
        return false;
    }
}
function check_id_vestiori($obiect, $vestitor){
    if(!property_exists($vestitor, 'id')){
        return false;
    }
    else if(!is_numeric($vestitor->id)){
        return false;
    }
    else if($vestitor->id > 9000 || $vestitor->id < 0){
        return false;
    }
    else if(check_twin_id($obiect, $vestitor->id)){
        return false;
    }
    return true;
}
function check_twin_name($obiect, $name){
    $count_array = count($obiect);
    $double = 0;
    $checker_name = 0;
    for ($i=0; $i < $count_array; $i++) { 
        if($name == $obiect[$i]->nume){
            $double++;
        }
        else if(strlen($name) == strlen($obiect[$i]->nume)){
            if(str_contains($obiect[$i]->nume,'-')){
                $twin_name = str_replace('-', ' ', $obiect[$i]->nume);
            }
            else{
                $twin_name = $obiect[$i]->nume;
            }
            if(str_contains($name,'-')){
                $new_name = str_replace('-', ' ', $name);
            }
            else{
                $new_name = $name;
            }
            $new_name = explode(' ', $new_name);
            $twin_name = explode(' ', $twin_name);
            if(count($new_name) == count($twin_name)){
                $validare = 0;
                for ($n=0; $n < count($new_name); $n++) { 
                    for ($j=0; $j < count($new_name); $j++) { 
                        if($new_name[$n] == $twin_name[$j]){
                            $validare++;
                        }
                    }
                }
                if($validare == count($new_name)){
                    $checker_name++;
                }
            }
        }
    }
    if($double > 1){
        return true;
    }
    else if($checker_name >= 1){
        return true;
    }
    else{
        return false;
    }
}
function check_name_vestitori($obiect, $vestitor){
    if(!property_exists($vestitor, 'nume')){
        return false;
    }
    else if(strlen($vestitor->nume) >= 50 || strlen($vestitor->nume) <= 2){
        return false;
    }
    else if(preg_match('/[\'^£$%&"*()}{@#~!?><>,_|=+¬]/', $vestitor->nume)){
        return false;
    }
    else if(strpos($vestitor->nume, "  ") !== false || substr($vestitor->nume, -1) === ' ' || substr($vestitor->nume, 0, 1) === ' ') {
        return false;
    }
    else if(check_twin_name($obiect, $vestitor->nume)){
        return false;
    }
    return true;
}
function check_privilegiu_vestitori($vestitor){
    $privilegii = array('Vestitor', 'Slujitor auxiliar', 'Batran', 'Vestitor nebotezat', 'Inscris doar la scoala');
    if(!property_exists($vestitor, 'privilegiu')){
        return false;
    }
    else if(!in_array($vestitor->privilegiu, $privilegii)){
        echo 'Problema la id-ul: '.$vestitor->id.'. ';
        return false;
    }
    return true;
}
function check_gen_vestiori($vestitor){
    $genul = array('Feminin', 'Masculin');
    if(!property_exists($vestitor, 'gen')){
        return false;
    }
    else if(!in_array($vestitor->gen, $genul)){
        return false;
    }
    return true;
}
function check_each_object($contents){
    $count_array = count($contents->vestitori);
    if($count_array < 2 || $count_array > 200){
        return false;
    }
    else{
        for ($i=0; $i < $count_array; $i++) { 
            if(!is_object($contents->vestitori[$i])){
                return false;
            }
            else{
                if(!check_id_vestiori($contents->vestitori, $contents->vestitori[$i])){
                    return false;
                }
                else if(!check_name_vestitori($contents->vestitori, $contents->vestitori[$i])){
                    return false;
                }
                else if(!check_privilegiu_vestitori($contents->vestitori[$i])){
                    return false;
                }
                else if(!check_gen_vestiori($contents->vestitori[$i])){
                    return false;
                }
                else if($contents->vestitori[$i]->privilegiu == 'Slujitor auxiliar' && $contents->vestitori[$i]->gen == 'Feminin'){
                    return false;
                }
                else if($contents->vestitori[$i]->privilegiu == 'Batran' && $contents->vestitori[$i]->gen == 'Feminin'){
                    return false;
                }
            }
        }
    }
    return true;
}
function check_inside_file($inside_file){
    $contents = file_get_contents($inside_file);
    $contents = json_decode($contents);
    if(!is_object($contents)){
        echo 'Fisierul introdus are continut deteriorat!';
        return false;
    }
    else if(!property_exists($contents, 'vestitori')){
        echo 'Denumirea grupului este incorecta!';
        return false;
    }
    else if(!is_array($contents->vestitori)){
        echo 'Continutul unde sunt stocati vestitorii este deteriorat!';
        return false;
    }
    else if(!check_each_object($contents)){
        echo 'La unul dintre vestitori s-a gasit o eroare!';
        return false;
    }
    return true;
}
function check_activity($username, $name_base, $conectareDB){
    $sql = 'SELECT * FROM '.$name_base.' WHERE username = "'.$username.'"';
    $query =  mysqli_query($conectareDB, $sql);
    $exist_user = mysqli_num_rows($query);
    if($exist_user !== 0){
        $user_row =  mysqli_fetch_assoc($query);
        return $user_row['key_user'];
    }
}
function set_key($obiect, $name_file, $username, $tabel, $conectareDB){
    $privilegii = array('Vestitor', 'Slujitor auxiliar', 'Batran', 'Vestitor nebotezat', 'Inscris doar la scoala');
    $contents = file_get_contents($obiect);
    $contents = json_decode($contents);
    $count_array = count($contents->vestitori);

    $name_file = mysqli_real_escape_string($conectareDB, $name_file);
    $sql = "UPDATE ".$tabel." SET key_user = '".$name_file."' WHERE username='".$username."'";
    mysqli_query($conectareDB, $sql);

    $name_tabel = $username.'_vestitori';
    $sql = "
    CREATE TABLE ".$name_tabel." (
        id VARCHAR(255) NOT NULL,
        space VARCHAR(255) NOT NULL,
        rugaciune VARCHAR(255) NOT NULL,
        presedinte_viata_crestina VARCHAR(255) NOT NULL,
        comori_cuvantare VARCHAR(255) NOT NULL,
        nestemate VARCHAR(255) NOT NULL,
        citirea_din_biblie VARCHAR(255) NOT NULL,
        incepe_o_conversatie VARCHAR(255) NOT NULL,
        fa_vizite_ulterioare VARCHAR(255) NOT NULL,
        fa_discipoli VARCHAR(255) NOT NULL,
        explica_ti_convingerile VARCHAR(255) NOT NULL,
        cursant_cuvantare VARCHAR(255) NOT NULL,
        discutie_eficienti_in_predicare VARCHAR(255) NOT NULL,
        viata_de_crestin_teme VARCHAR(255) NOT NULL,
        necesitati_locale VARCHAR(255) NOT NULL,
        conducator_studiu_de_carte VARCHAR(255) NOT NULL,
        cititor_la_studiu_de_carte VARCHAR(255) NOT NULL,
        cuvantare_de_weekend VARCHAR(255) NOT NULL,
        presedinte_la_final_de_saptamana VARCHAR(255) NOT NULL,
        cititor_la_turn VARCHAR(255) NOT NULL,
        om_de_ordine VARCHAR(255) NOT NULL
    )";
    mysqli_query($conectareDB, $sql);

    $name_tabel_meeting = $username.'_meetings';
    $sql = "
    CREATE TABLE ".$name_tabel_meeting." (
        year VARCHAR(255) NOT NULL,
        week VARCHAR(255) NOT NULL,
        tema VARCHAR(255) NOT NULL,
        range_pagina VARCHAR(255) NOT NULL,
        vestitor_id VARCHAR(255) NOT NULL 
    )";
    mysqli_query($conectareDB, $sql);

    for ($i=0; $i < $count_array; $i++) { 

        $id = mysqli_real_escape_string($conectareDB, $contents->vestitori[$i]->id);
        $name = strlen($contents->vestitori[$i]->nume);
        $privilegiu = $contents->vestitori[$i]->privilegiu;
        $genul = $contents->vestitori[$i]->gen;
        $rugaciune = 'nu'; 
        $presedinte_viata_crestina = 'nu';
        $comori_cuvantare = 'nu';
        $nestemate = 'nu';
        $citirea_din_biblie = 'nu';
        $incepe_o_conversatie = 'nu';
        $fa_vizite_ulterioare = 'nu';
        $fa_discipoli = 'nu';
        $explica_ti_convingerile = 'nu';
        $cursant_cuvantare = 'nu';
        $discutie_eficienti_in_predicare = 'nu';
        $viata_de_crestin_teme = 'nu';
        $necesitati_locale = 'nu';
        $conducator_studiu_de_carte = 'nu';
        $cititor_la_studiu_de_carte = 'nu';
        $cuvantare_de_weekend = 'nu';
        $presedinte_la_final_de_saptamana = 'nu';
        $cititor_la_turn = 'nu';
        $om_de_ordine = 'nu';

        if($privilegiu == $privilegii[1] && $genul == 'Masculin'){
            $rugaciune = 'da'; 
            $comori_cuvantare = 'da';
            $nestemate = 'da';
            $citirea_din_biblie = 'da';
            $incepe_o_conversatie = 'da';
            $fa_vizite_ulterioare = 'da';
            $fa_discipoli = 'da';
            $explica_ti_convingerile = 'da';
            $cursant_cuvantare = 'da';
            $discutie_eficienti_in_predicare = 'da';
            $viata_de_crestin_teme = 'da';
            $cititor_la_studiu_de_carte = 'da';
            $cuvantare_de_weekend = 'da';
            $presedinte_la_final_de_saptamana = 'da';
            $cititor_la_turn = 'da';
            $om_de_ordine = 'da';
        }
        else if($privilegiu == $privilegii[2] && $genul == 'Masculin'){
            $rugaciune = 'da'; 
            $presedinte_viata_crestina = 'da';
            $comori_cuvantare = 'da';
            $nestemate = 'da';
            $citirea_din_biblie = 'da';
            $incepe_o_conversatie = 'da';
            $fa_vizite_ulterioare = 'da';
            $fa_discipoli = 'da';
            $explica_ti_convingerile = 'da';
            $cursant_cuvantare = 'da';
            $discutie_eficienti_in_predicare = 'da';
            $viata_de_crestin_teme = 'da';
            $necesitati_locale = 'da';
            $conducator_studiu_de_carte = 'da';
            $cititor_la_studiu_de_carte = 'da';
            $cuvantare_de_weekend = 'da';
            $presedinte_la_final_de_saptamana = 'da';
            $cititor_la_turn = 'da';
            $om_de_ordine = 'da';
        }
        else if (($privilegiu == $privilegii[0] || $privilegiu == $privilegii[3] || $privilegiu == $privilegii[4]) && $genul == 'Masculin'){
            $citirea_din_biblie = 'da';
            $incepe_o_conversatie = 'da';
            $fa_vizite_ulterioare = 'da';
            $fa_discipoli = 'da';
            $explica_ti_convingerile = 'da';
            $cursant_cuvantare = 'da';
        }
        else if (($privilegiu == $privilegii[0] || $privilegiu == $privilegii[3] || $privilegiu == $privilegii[4]) && $genul == 'Feminin'){
            $incepe_o_conversatie = 'da';
            $fa_vizite_ulterioare = 'da';
            $fa_discipoli = 'da';
            $explica_ti_convingerile = 'da';
        }
        $sql = "INSERT INTO ".$name_tabel." 
        (id, space, rugaciune, presedinte_viata_crestina, comori_cuvantare, nestemate, citirea_din_biblie, incepe_o_conversatie, 
        fa_vizite_ulterioare, fa_discipoli, explica_ti_convingerile, cursant_cuvantare, discutie_eficienti_in_predicare, 
        viata_de_crestin_teme, necesitati_locale, conducator_studiu_de_carte, cititor_la_studiu_de_carte, cuvantare_de_weekend, 
        presedinte_la_final_de_saptamana, cititor_la_turn, om_de_ordine)
        VALUES ('".$id."', '".$name."', '".$rugaciune."', '".$presedinte_viata_crestina."', '".$comori_cuvantare."',
        '".$nestemate."', '".$citirea_din_biblie."', '".$incepe_o_conversatie."', '".$fa_vizite_ulterioare."', 
        '".$fa_discipoli."', '".$explica_ti_convingerile."', '".$cursant_cuvantare."', '".$discutie_eficienti_in_predicare."', 
        '".$viata_de_crestin_teme."', '".$necesitati_locale."', '".$conducator_studiu_de_carte."', 
        '".$cititor_la_studiu_de_carte."', '".$cuvantare_de_weekend."', 
        '".$presedinte_la_final_de_saptamana."', '".$cititor_la_turn."', '".$om_de_ordine."')";
        mysqli_query($conectareDB, $sql);
    }
}
function tabel_key($inside_file, $username, $conectareDB){
    $name_tabel = $username.'_vestitori';
    $sql = 'SELECT * FROM '.$name_tabel.'';
    $query =  mysqli_query($conectareDB, $sql);
    $exist_user = mysqli_num_rows($query);

    $contents = file_get_contents($inside_file);
    $contents = json_decode($contents);
    $count_array = count($contents->vestitori);

    if($count_array == $exist_user){
        for ($i=0; $i < $count_array; $i++) { 
            $id = $contents->vestitori[$i]->id;
            $space = strlen($contents->vestitori[$i]->nume);
            $sql = 'SELECT * FROM '.$name_tabel.' WHERE id = "'.$id.'" AND space = "'.$space.'"';
            $query =  mysqli_query($conectareDB, $sql);
            $exist_user = mysqli_num_rows($query);
            if($exist_user <= 0){
                return false;
            }
        }
    }
    else{
        return false;
    }
    return true;
}
function check_key($file, $inside_file, $username, $tabel, $conectareDB){
    $sql = 'SELECT * FROM '.$tabel.' WHERE username = "'.$username.'"';
    $query =  mysqli_query($conectareDB, $sql);
    $exist_user = mysqli_num_rows($query);
    if($exist_user !== 0){
        $user_row =  mysqli_fetch_assoc($query);
        if($user_row['key_user'] != $file){
            echo 'Numele fisierului nu se potriveste, ultimul fisier a avut denumirea:'.$user_row['key_user'];
            return false;
        }
        else if(!tabel_key($inside_file, $username, $conectareDB)){
            echo 'Cheia nu se potriveste!';
            return false;
        }
    }
    return true;
}

function create_session_key($file){
    $contents = file_get_contents($file);
    $contents = json_decode($contents);
    $_SESSION['key'] = $contents;
}

function extragere_file_check($data){
    if (!$data) {
        echo "JSON invalid sau lipsă date.";
        return false;
    } 
    else if(!is_object($data)){
        echo 'Fisierul introdus are continut deteriorat!';
        return false;
    }
    else if(!property_exists($data, 'vestitori')){
        echo 'Denumirea grupului este incorecta!';
        return false;
    }
    else if(!is_array($data->vestitori)){
        echo 'Continutul unde sunt stocati vestitorii este deteriorat!';
        return false;
    }
    else if(!check_each_object($data)){
        echo 'La unul dintre vestitori s-a gasit o eroare!';
        return false;
    }
    return true;
}
function arr_to_obj($content) {
    $numara_array = count($content);
    $new_content = [];

    for ($i = 0; $i < $numara_array; $i++) { 
        $new_content[] = (object)[
            'id' => $content[$i]['id'],
            'nume' => $content[$i]['nume'],
            'privilegiu' => $content[$i]['privilegiu'],
            'gen' => $content[$i]['gen']
        ];
    }
    return $new_content;
}

function tran_arr_in_obj($content){
    $new_content = (object)[
        'vestitori' => arr_to_obj($content['vestitori']),
        'congregatie' => "Nedefinita",
        'supraveghetor' => "Nedefinita",
        'vorbitori' => [],
        'emailuri' => []
    ];

    return $new_content;
}

function check_two_step_key($inside_file, $username, $tabel, $conectareDB){
    $sql = 'SELECT * FROM '.$tabel.' WHERE username = "'.$username.'"';
    $query =  mysqli_query($conectareDB, $sql);
    $exist_user = mysqli_num_rows($query);
    if($exist_user !== 0){
        $user_row =  mysqli_fetch_assoc($query);
        if(!tabel_two_key($inside_file, $username, $conectareDB)){
            echo 'Cheia nu se potriveste!';
            return false;
        }
    }
    return true;
}

function tabel_two_key($inside_file, $username, $conectareDB){
    $name_tabel = $username.'_vestitori';
    $sql = 'SELECT * FROM '.$name_tabel.'';
    $query =  mysqli_query($conectareDB, $sql);
    $exist_user = mysqli_num_rows($query);

    $contents = $inside_file;
    $count_array = count($contents->vestitori);

    if($count_array == $exist_user){
        for ($i=0; $i < $count_array; $i++) { 
            $id = $contents->vestitori[$i]->id;
            $space = strlen($contents->vestitori[$i]->nume);
            $sql = 'SELECT * FROM '.$name_tabel.' WHERE id = "'.$id.'" AND space = "'.$space.'"';
            $query =  mysqli_query($conectareDB, $sql);
            $exist_user = mysqli_num_rows($query);
            if($exist_user <= 0){
                return false;
            }
        }
    }
    else{
        return false;
    }
    return true;
}

?>