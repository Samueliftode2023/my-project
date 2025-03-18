<?php
// FUNCTIE PENTRU IMBUNATATIREA NUMELUI
    function improve_name($full_name){
        $full_name_edit = '';
        $full_name = InlocuireCharactere($full_name);
        $full_name = explode(' ',$full_name);
        $count_name = count($full_name);
        for ($i=0; $i < $count_name; $i++) { 
        $full_name_edit .= ucfirst(strtolower($full_name[$i])).' ';
        }
        $full_name = InlocuireCharactere($full_name_edit);

        if(str_contains($full_name, '-')){
            $full_name_edit = '';
            $full_name = explode('-',$full_name);
            $count_name = count($full_name);
            for ($i=0; $i < $count_name ; $i++) { 
                if(str_contains($full_name[$i], ' ')){
                    $space_string = '';
                    $with_space = explode(' ',$full_name[$i]);
                    $count_space = count($with_space);
                    for ($j=0; $j < $count_space; $j++) { 
                        $space_string .= ucfirst(strtolower($with_space[$j])).' ';
                    }
                    $full_name[$i] = InlocuireCharactere($space_string);
                    $full_name_edit .= $full_name[$i].'-';
                }
                else{
                    $full_name_edit .= ucfirst(strtolower($full_name[$i])).'-';
                }
            }
            $full_name = substr($full_name_edit, 0, -1);
        }
        return $full_name;
    }
//
// FUNCTIE PENTRU VERIFICAREA DUBLURILOR 
    function verify_dubble($full_name, $verificare_dubluri){
        $count_name_f = strlen($full_name);
        $count_name_s = strlen($verificare_dubluri);
        if($full_name == $verificare_dubluri){
            return true;
        }
        else if($count_name_f == $count_name_s){
            if(str_contains($full_name,'-')){
                $full_name = str_replace('-', ' ', $full_name);
            }
            if(str_contains($verificare_dubluri,'-')){
                $verificare_dubluri = str_replace('-', ' ', $verificare_dubluri);
            }
            $full_name = explode(' ',$full_name);
            $verificare_dubluri = explode(' ',$verificare_dubluri);
            $count_name_f = count($full_name);
            $count_name_s = count($verificare_dubluri);
            if($count_name_f == $count_name_s){
                $validare = 0;
                for ($i=0; $i < $count_name_f ; $i++) { 
                    for ($j=0; $j < $count_name_f ; $j++) { 
                        if($full_name[$i] == $verificare_dubluri[$j]){
                            $validare++;
                        }
                    }
                }
                if($validare == $count_name_f){
                    return true;
                }
            }
        }
        return false;
    }
//
// FUNCTII DE VERIFICARE A CAMPURILOR INSERATE    
    function check_vestitor($obiect){
        if($obiect['lista'] != 'delete'){
            $privilege = $obiect['privilege'];
            $genul = $obiect['genul'];
            $gen = ['Masculin','Feminin'];
            $privilege_array = ['Vestitor', 'Slujitor auxiliar', 'Batran', 'Vestitor nebotezat', 'Inscris doar la scoala'];

            if($genul === 'Feminin' && $privilege !== 'Vestitor' && $privilege !== 'Vestitor nebotezat' && $privilege !== 'Inscris doar la scoala'){
                echo 'Atentie - Formatul pe care l-au introdus nu este corect, pentru genul feminin exista doar privilegiul de vestitor!';
                return false;
            }
            if(!in_array($genul, $gen)){
                echo 'Atentie - Exista doar doua genuri prestabilite!';
                return false;
            }
            if(!in_array($privilege, $privilege_array)){
                echo 'Atentie - Nu este permisa adaugarea de noi privilegii!';
                return false;
            }
            if(strlen($obiect['full-name']) >= 50 || strlen($obiect['full-name']) <= 2 ){
                echo 'Atentie - Numele este prea mare sau prea mic!';
                return false;
            }
            if(preg_match('/[\'^£$%&"*()}{@#~!?><>,_|=+¬]/', $obiect['full-name'])){
                echo 'Atentie - Nu este permisa folosirea simbolurilor speciale!';
                return false;
            }
            if(!check_lists($obiect)){
                return false;
            }

            if($obiect['lista'] == 'edit'){
                if(isset($_SESSION['key'])){
                    $vestitori_key = $_SESSION['key']->vestitori;
                    $numar_vestitori = count($vestitori_key);
                    for ($i=0; $i < $numar_vestitori; $i++) { 
                        $verificare_dubluri = $vestitori_key[$i]->nume;
                        if(verify_dubble($obiect['full-name'], $verificare_dubluri) && $obiect['id-vestitor'] != $vestitori_key[$i]->id){
                            echo 'Atentie - Acest nume a fost deja adaugat!';
                            return false;
                        }
                        else if($obiect['id-vestitor'] == $vestitori_key[$i]->id && $obiect['full-name'] ==  $vestitori_key[$i]->nume && $privilege == $vestitori_key[$i]->privilegiu && $genul ==  $vestitori_key[$i]->gen){
                            echo 'Atentie - Nu ai produs modificari!';
                            return false;
                        }
                    }
                }
                if(isset($_SESSION['new_key'])){
                    $vestitori_key = $_SESSION['new_key'] -> delete;
                    if(in_array($obiect['id-vestitor'], $vestitori_key)){
                        echo 'Atentie - Acest nume a fost sters!';
                        return false;
                    }
                }
            }
        }
        return true;
    }

    function check_lists($obiect) {
        $lista = $obiect['lista'];
        $liste = (object)[
            'add' => [
                'new_key1' => 'add', 
                'new_key2' => 'edit',
                'key3' => 'vestitori'
            ],
            'edit' => [
                'new_key1' => 'add'
            ]
        ];

        $chei = array_keys($liste->$lista);
        $numar_verificari = count($liste -> $lista);

       for ($k = 0; $k < $numar_verificari; $k++) {
            $cheie = substr($chei[$k], 0, -1);
            if (isset($_SESSION[$cheie])) {
                $valoare_lista = array_values($liste -> $lista)[$k];
                $vestitori_key = $_SESSION[$cheie] -> $valoare_lista;
                $numar_vestitori = count($vestitori_key);

                for ($i = 0; $i < $numar_vestitori; $i++) { 
                    $verificare_dubluri = $vestitori_key[$i]->nume;
                    if (verify_dubble($obiect['full-name'], $verificare_dubluri)) {
                        echo 'Atentie - Acest nume a fost deja adaugat in noua cheie!';
                        return false;
                    }
                }
            }
        }
        return true;
    }
//
// FUNCTIi PENTRU CREAREA NOII CHEI
    function create_object(){
        if(isset($_SESSION['new_key'])){
            $comanda_compusa = $_SESSION['new_key'];
        }
        else{
            $comanda_compusa = new stdClass();
            $comanda_compusa -> add = [];
            $comanda_compusa -> edit = [];
            $comanda_compusa -> delete = [];
        }
        return $comanda_compusa;
    }
    
    function add_in_object($array, $new_key){
        $lista = $array['lista'];

        if($array['lista'] != 'delete'){
            $content = (object) [
                'id' => $array['id-vestitor'] ?? null,
                'nume' => $array['full-name'],
                'privilegiu' => $array['privilege'],
                'gen' => $array['genul']
            ];
            
            if($array['lista'] == 'edit'){
                $id = $array['id-vestitor'];
                for ($i=0; $i < count($new_key -> edit); $i++) { 
                    if($new_key -> edit[$i] -> id == $id){
                        unset($new_key -> edit[$i]);
                    }
                }
                $new_key->edit = array_values($new_key->edit);
            }
            array_push($new_key -> $lista, $content);
        }
        else{
            unset($array['lista']);
            $array = array_values($array);
            $deleted = count($array);

            for ($i=0; $i < $deleted; $i++) { 
                if(!in_array($array[$i], $new_key -> $lista)){
                    array_push($new_key -> $lista, add_in_delete($array[$i]));
                }
                for ($j=0; $j < count($new_key -> edit); $j++) { 
                    if($new_key -> edit[$j] -> id == $array[$i]){
                        unset($new_key -> edit[$j]);
                    }
                    $new_key->edit = array_values($new_key->edit);
                }
            }
        }
        return $new_key;
    }

    function create_new_key($array){
        if(isset($array['full-name'])){
            $array['full-name'] = improve_name($array['full-name']); 
        }

        if(check_vestitor($array)){
            $new_key = create_object();
            $new_key = add_in_object($array, $new_key);
            
            $_SESSION['new_key'] = $new_key;
            echo json_encode($_SESSION['new_key']);
        }
    }
//

function add_in_delete($id){
    if(isset($_SESSION['key'])){
        for ($i=0; $i < count($_SESSION['key'] -> vestitori); $i++) { 
            if($_SESSION['key'] -> vestitori[$i] -> id == $id){
                return $_SESSION['key'] -> vestitori[$i];
            }
        }
    }
}

function anuleaza($object){
    if(isset($_SESSION['new_key'])){
        $list = $object['anulare'];
        $obiecte = $_SESSION['new_key']->$list;
        $nume_nou = $object['nume-anulare'];

        $_SESSION['new_key']->$list = array_filter($obiecte, function($obiect) use ($nume_nou) {
            return $obiect->nume !== $nume_nou; 
        });
    
        $_SESSION['new_key']->$list = array_values($_SESSION['new_key'] -> $list);

        if(count($_SESSION['new_key']->add) == 0 && count($_SESSION['new_key']->edit) == 0 && count($_SESSION['new_key']->delete) == 0){
            unset($_SESSION['new_key']);
        }
        else{
            echo json_encode($_SESSION['new_key']);
        }
    }
}

function object_list($list){
    if(isset($_SESSION['new_key'])){
        $lista = $_SESSION['new_key'] -> $list;
        echo json_encode($lista);
    }
    else{
        echo '<div class="mesaj-lista">Momentan nu au fost facute modificari.</div>';
    }
}

function get_data_vestitori($id){
    if(isset($_SESSION['key'])){
        $vestitori_key = $_SESSION['key'] -> vestitori;
        $numar_vestitori = count($vestitori_key);
        
        for ($i=0; $i < $numar_vestitori; $i++) { 
            if($vestitori_key[$i]-> id == $id){
                $stocare_fractiune = [$vestitori_key[$i]-> nume, $vestitori_key[$i]-> privilegiu, $vestitori_key[$i]-> gen];
                return $stocare_fractiune;
            }
        }
    }
}

function get_last_id_table($conectareDB){
    $name_table = $_SESSION['username'].'_vestitori';
    //$sql = 'SELECT * FROM '.$name_table .' ORDER BY id DESC LIMIT 1;';
    $sql = 'SELECT * FROM '.$name_table.' ORDER BY CAST(id AS UNSIGNED) DESC LIMIT 1;';
    $result = mysqli_query($conectareDB, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return (int)$row['id'] + 1;
    }
    else{
        return 'err';
    }
}

function generate_key_key($conectareDB){
    unset($_SESSION['compilate-key']);
    if (isset($_SESSION['key']) && isset($_SESSION['new_key'])) {
        $id_starter = get_last_id_table($conectareDB);
        if($id_starter != 'err'){
            $copy_key = clone $_SESSION['key'];
            $copy_new_key = clone $_SESSION['new_key'];

            $_SESSION['compilate-key'] = add_in_key($copy_key, $copy_new_key, $id_starter);
            echo json_encode($_SESSION['compilate-key']);
        }
        else{
            echo 'Atentie - Ceva nu a functionat! - 00';
        }
    }
    else{
        echo 'Atentie - Ceva nu a functionat! - 01';
    }
}

function add_in_key($old_key, $new_key, $id_starter){

    for ($i = 0; $i < count($new_key -> add); $i++) { 
        $new_key -> add[$i] -> id = $id_starter + $i;
        array_push($old_key -> vestitori, $new_key -> add[$i]);
    }

    for ($i=0; $i < count($new_key -> edit); $i++) { 
        for ($k=0; $k < count($old_key -> vestitori); $k++) { 
            if($new_key -> edit[$i] -> id == $old_key -> vestitori[$k] -> id){
                $old_key -> vestitori[$k] = $new_key -> edit[$i];
            }
        }
    }

    for ($i = 0; $i < count($new_key->delete); $i++) {
        $id_sters = $new_key -> delete[$i] -> id;
        $obj = $old_key -> vestitori;
        
        $old_key -> vestitori = array_filter($obj, function($obiect) use ($id_sters) {
            return $obiect -> id !== $id_sters; 
        });
    
        $old_key -> vestitori = array_values($old_key -> vestitori);
    }

    return $old_key;
}

// FUNCTII DE MODIFICARE A TABELULUI 
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

    function exe_new_key($fisier, $conectareDB){
        if(check_file($fisier['file']['name'])){
            if(check_inside_file($fisier['file']['tmp_name'])){
                if(isset($_SESSION['new_key'])){
                    try{
                        mysqli_begin_transaction($conectareDB);

                        add_in_table($fisier['file']['name'], $_SESSION['username'], $conectareDB);
                        modify_table($conectareDB);
                        delete_vestitori($conectareDB);
                        anuleaza_noile_sesiuni();

                        mysqli_commit($conectareDB);
                    }
                    catch(Exception $e){
                        mysqli_rollback($conectareDB);
                        echo 'Atentie - a aparut o eroare, incearca din nou!';
                    }
                }
            }
        }
    }

    function add_in_table($name_file, $username, $conectareDB){
        $privilegii = array('Vestitor', 'Slujitor auxiliar', 'Batran', 'Vestitor nebotezat', 'Inscris doar la scoala');
        $new_key = $_SESSION['new_key'];
        $count_array = count($new_key -> add);
        $name_file = mysqli_real_escape_string($conectareDB, $name_file);
        $name_tabel = $username.'_vestitori';

        $sql = "UPDATE users SET key_user = '".$name_file."' WHERE username='".$username."'";
        mysqli_query($conectareDB, $sql);

        for ($i=0; $i < $count_array; $i++) { 

            $id = mysqli_real_escape_string($conectareDB, $new_key -> add[$i] -> id);
            $name = strlen($new_key -> add[$i]->nume);
            $privilegiu = $new_key -> add[$i]->privilegiu;
            $genul = $new_key -> add[$i]->gen;
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

            $sql = 'SELECT * FROM '.$name_tabel.' WHERE id = "'.$id.'"';
            $result = mysqli_query($conectareDB, $sql);

            if(mysqli_num_rows($result) == 0){
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
    }

    function modify_table($conectareDB){
        $new_key = $_SESSION['new_key'];
        $name_table = $_SESSION['username']."_vestitori";
        $count_array = count($new_key -> edit);

        for ($i=0; $i < $count_array; $i++) { 
            $nume_len = strlen($new_key -> edit[$i] -> nume);
            $id = $new_key -> edit[$i]->id;

            $sql = "UPDATE ".$name_table." SET space = '".$nume_len."' WHERE id='".$id."'";
            mysqli_query($conectareDB, $sql);
        }
    }

    function delete_vestitori($conectareDB){
        $new_key = $_SESSION['new_key'];
        $name_table_vestitori = $_SESSION['username']."_vestitori";
        $name_table_meetings = $_SESSION['username']."_meetings";
        $count_array = count($new_key -> delete);

        for ($i=0; $i < $count_array; $i++) { 
            $id = $new_key -> delete[$i] -> id;

            $sql = "DELETE FROM ".$name_table_vestitori." WHERE id = '".$id."'";
            mysqli_query($conectareDB, $sql);

            $sql = "DELETE FROM ".$name_table_meetings." WHERE vestitor_id = '".$id."'";
            mysqli_query($conectareDB, $sql);
        }
    }

    function anuleaza_noile_sesiuni(){
        session_destroy();
        echo 'ok';
    }
//    
?>