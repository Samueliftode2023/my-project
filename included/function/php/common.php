<?php
    session_start();
    include_once $root.'included/data-base/index.php';
    // REDIRECTIONAM PAGINA IN FUNCTIE DE SESIUNE
        if(isset($_SESSION['create-key'])){
            $name_folder = dirname($_SERVER['PHP_SELF']);
            $name_folder = basename($name_folder);
            $name_file = basename($_SERVER['PHP_SELF']);
            if($name_folder !== 'create-key' && $name_file !== 'create-key.php'){
                header('Location:'.$root.'main/key/personal-key/create-key/');
                exit;
            }
        }
        else if(isset($_SESSION['key'])){
            $name_folder = dirname($_SERVER['PHP_SELF']);
            $check_root = explode('/',$name_folder);
            $name_file = basename($_SERVER['PHP_SELF']);
            $accepted_file = ['download-meeting.php', 'schedule-meeting.php', 'edit-herald.php', 'privilege-herald.php',
             'print-meeting.php', 'create-table.php', 'print-teme.php', 'settings.php','change-data-connect.php','custom-table.php'];
            if(!in_array('dashboard',$check_root) && !in_array('meetings',$check_root) && !in_array('herald',$check_root) && !in_array('settings',$check_root) && !in_array($name_file, $accepted_file)){
                header('Location:'.$root.'main/dashboard/');
                exit;
            }
        }  
        else{
            $name_folder = dirname($_SERVER['PHP_SELF']);
            $check_root = explode('/',$name_folder);
            $redirect = ['dashboard', 'meetings', 'herald','settings'];
            $count = count($redirect);
            for ($i=0; $i < $count; $i++) { 
                if(in_array($redirect[$i], $check_root)){
                    header('Location:'.$root.'main/key/');
                    exit;
                }
            }
        }
    //
    function InlocuireCharactere($StringDeCorectat){
        $StringDeCorectat = str_Replace("  "," ",$StringDeCorectat);
         if (strpos($StringDeCorectat, "  ") !== false){
             $StringDeCorectat=InlocuireCharactere($StringDeCorectat);
         };
         //Verificam daca stringul are un spatiu la INCEPUT daca exista il eliminam
         if (substr($StringDeCorectat, 0, 1)===' '){
             $StringDeCorectat=substr($StringDeCorectat, 1, strlen($StringDeCorectat));
             //echo $StringDeCorectat;	
         };
         //Verificam daca stringul are un spatiu la SFARSIT daca exista il eliminam
        if (substr($StringDeCorectat, -1)===' '){
             $StringDeCorectat=substr($StringDeCorectat, 0, -1);
         };
         return $StringDeCorectat;
    };
    // VERIFICAM DACA FISIRIELE EXISTA
        function verify_file($path){
            if(!file_exists($path)){
                file_put_contents($path, '');
            }
        }
    //
    // ORDONAM CODUL SI FACEM PAGINI
        function create_page($scope, $title, $root, $conectareDB){
            $navigation = array(
                'navigation' => $root.'included/html/body/navigation.php',
                'access-navigation' => $root.'included/html/body/access-navigation.php',
                'access' =>  $root.'included/html/body/access.php'
            );
            $code = array(
                'css' => $root.'included/function/css/'.$scope.'.css',
                'script' => $root.'included/function/script/'.$scope.'.js',
                'php' =>  $root.'included/function/php/'.$scope.'.php',
                'head' => $root.'included/html/head/'.$scope.'.php',
                'body' => $root.'included/html/body/'.$scope.'.php',
                'exe' => $root.'included/function/exe/'.$scope.'.php'
            );

            foreach ($code as $cheie => $valoare) {
                verify_file($valoare);
            }

            $name_folder = dirname($_SERVER['PHP_SELF']);
            $check_root = explode('/',$name_folder);
            if(in_array('access', $check_root) || in_array('key', $check_root)){
                if(!isset($_SESSION['username'])){
                    $nav = $navigation['access'];
                }
                else{
                    $nav = $navigation['access-navigation'];
                }
            }
            else{
                $nav = $navigation['navigation'];
            }
            
            include_once $root.'included/html/composer.php';
        }
    // 
    //  VERIFICAM DACA DATELE USERULUI SUNT IN BD
        function check_user($username, $password, $name_base, $data_base){
            $sql = 'SELECT * FROM '.$name_base.' WHERE username = "'.$username.'"';
            $query =  mysqli_query($data_base, $sql);
            $exist_user = mysqli_num_rows($query);
            if($exist_user == 0){
                echo 'Acest nume nu exista in baza de date.';
                session_destroy();
                return false;
            }
            else{
                $user_row =  mysqli_fetch_assoc($query);
                if(!password_verify($password,$user_row['password'])){
                    echo 'Parola nu se potriveste.';
                    session_destroy();
                    return false;
                }
            }
            return true;
        }
    // 
    // VERIFICAM DACA SESIUNEA ESTE CORECTA
        function check_session($type_session, $root, $conectareDB){
            if($type_session === 'important'){
                if(!isset($_SESSION['username']) && !isset($_SESSION['password'])){
                    header('Location:'.$root.'');
                    exit;
                }
                else{
                    check_user($_SESSION['username'], $_SESSION['password'], 'users', $conectareDB);
                }
            }
            else if($type_session === 'unimportant'){
                if(isset($_SESSION['username']) && isset($_SESSION['password'])){
                    header('Location:'.$root.'main/');
                    exit;
                }
            }
        }
    //
    // VERIFICAM ROBOTII
        function check_robot($secretKey){
            if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
                $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response']); 
                $responseData = json_decode($verifyResponse);         
                if($responseData->success){ 
                    return true;
                }
                else{
                    echo 'Completarea campului NU SUNT ROBOT - este necesara!';
                    return false;
                }
            }
            else{
                echo 'Completarea campului NU SUNT ROBOT - este necesara!';
                return false;
            }
        }
    //
    // INSERT DATA IN TABLE
        function insert_data($data_base, $name_table, $columns, $value){
            $sql = "INSERT INTO ".$name_table." (".$columns.")
            VALUES (".$value.")";
            mysqli_query($data_base, $sql);
        }    
    //
    // SORTARE AFLABETIAC ARRAY CU OBIECTE
        function comparare_alfabetica($a, $b) {
            // $collator = collator_create('ro_RO'); 
            // return $collator->compare($a->nume, $b->nume);
            $a_lower = mb_strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $a->nume));
            $b_lower = mb_strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $b->nume));
            return strcasecmp($a_lower, $b_lower);
        }
    //
    // TOT CE ESTE INCLUS IN DASHBOARD
        // CAMP DE SELECTIE A VESTITORILOR
            function select_vestitor($scop, $range, $selection, $data_base){
                $lista_vestitori = '';
                $contents = $_SESSION['key'];
                $container = $contents->vestitori;
                usort($container, 'comparare_alfabetica');
                $count_array = count($container);
                for ($i=0; $i < $count_array; $i++) { 
                    $id = $container[$i]->id;
                    $name = $container[$i]->nume;
                    $sql = 'SELECT * FROM '.$_SESSION['username'].'_vestitori WHERE id="'.$id.'"';
                    $query =  mysqli_query($data_base, $sql);
                    $exist_user = mysqli_num_rows($query);
                    if($exist_user > 0){
                        $user_row = mysqli_fetch_assoc($query);
                        if($scop == 'all'){
                            $lista_vestitori .= '<option value="'.$id.'">'.$name.'</option>';
                        }
                        else if($scop == 'not-all'){
                            if(isset($_SESSION['new_key'])){
                                if(!verificam_delete_list($id, $_SESSION['new_key'])){
                                    $lista_vestitori .= '<option value="'.$id.'">'.$name.'</option>';
                                }
                            }
                            else{
                                $lista_vestitori .= '<option value="'.$id.'">'.$name.'</option>';  
                            }
                        }
                        else if($user_row[$scop] == 'da'){
                            $select = '';
                            if($selection == $id){
                                $select = 'selected';
                            }
                            $lista_vestitori .= '<option value="'.$id.'" '.$select.'>'.$name.'</option>';
                        }
                    }
                }
                $lista_vestitori = '<select name="'.$range.'"><option value="no">...</option>'.$lista_vestitori.'</select>';
                return $lista_vestitori;
            }
            function checkbox_vestitor($filtre, $data_base){
                $lista_vestitori = '';
                $contents = $_SESSION['key'];
                $container = $contents->vestitori;
                usort($container, 'comparare_alfabetica');
                $count_array = count($container);
                for ($i=0; $i < $count_array; $i++) { 
                    $id = $container[$i]->id;
                    $name = $container[$i]->nume;
                    $sql = 'SELECT * FROM '.$_SESSION['username'].'_vestitori WHERE id="'.$id.'"';
                    $query =  mysqli_query($data_base, $sql);
                    $exist_user = mysqli_num_rows($query);
                    if($exist_user > 0){
                        $user_row = mysqli_fetch_assoc($query);
                        if($filtre == 'Totala'){
                            $lista_vestitori .= '<label>'.$name.'<input type="checkbox" name="vestitor-'.$id.'" value="'.$id.'"></label>';
                        }
                        else if($filtre == $container[$i]->gen){
                            $lista_vestitori .= '<label>'.$name.'<input type="checkbox" name="vestitor-'.$id.'" value="'.$id.'"></label>';
                        }
                        else if($filtre == $container[$i]->privilegiu){
                            $lista_vestitori .= '<label>'.$name.'<input type="checkbox" name="vestitor-'.$id.'" value="'.$id.'"></label>';
                        }
                        else if($filtre == 'new_key'){
                            if(isset($_SESSION['new_key'])){
                                if(!verificam_delete_list($id, $_SESSION['new_key'])){
                                    $lista_vestitori .= '<label>'.$name.'<input type="checkbox" name="vestitor-'.$id.'" value="'.$id.'"></label>';
                                }
                            }
                            else{
                                $lista_vestitori .= '<label>'.$name.'<input type="checkbox" name="vestitor-'.$id.'" value="'.$id.'"></label>';
                            }
                        }
                    }
                }
                return $lista_vestitori;
            }
            function checkbox_privlegii(){
                $lista_vestitori = '';
                $privilegii_table = ['rugaciune', 'presedinte_viata_crestina', 'comori_cuvantare', 'nestemate', 'citirea_din_biblie', 'incepe_o_conversatie', 
                'fa_vizite_ulterioare', 'fa_discipoli', 'explica_ti_convingerile', 'cursant_cuvantare', 'discutie_eficienti_in_predicare', 
                'viata_de_crestin_teme', 'necesitati_locale', 'conducator_studiu_de_carte', 'cititor_la_studiu_de_carte', 'cuvantare_de_weekend', 
                'presedinte_la_final_de_saptamana', 'cititor_la_turn', 'om_de_ordine'];
                $privilegii_interfata = ['rugaciune', 'presedinte viata crestina', 'comori cuvantare', 'nestemate', 'citirea din biblie', 'incepe o conversatie', 
                'fa vizite ulterioare', 'fa discipoli', 'explica-ti convingerile', 'cursant cuvantare', 'discutie eficienti in predicare', 
                'viata de crestin teme', 'necesitati locale', 'conducator studiu de carte', 'cititor la studiu de carte', 'cuvantari de weekend', 
                'presedinte la final de saptamana', 'cititor la turn', 'om de ordine'];
                for ($i=0; $i < count($privilegii_interfata); $i++) { 
                    $lista_vestitori .= '<label>'.$privilegii_interfata[$i].'<input type="checkbox" name="'.$privilegii_table[$i].'" value="nu"></label>';
                }
                return $lista_vestitori;
            }
            function get_vestitor($id){
                $contents = $_SESSION['key'];
                $container = $contents->vestitori;
                $count_array = count($container);
                for ($i=0; $i < $count_array; $i++) { 
                    $id_obiect = $container[$i]->id;
                    if($id_obiect == $id){
                        return $container[$i]->nume;
                    }
                }
            }
            function get_conditii_tabel(){
                $privilegii_table = ['rugaciune', 'presedinte_viata_crestina', 'comori_cuvantare', 'nestemate', 'citirea_din_biblie', 'incepe_o_conversatie', 
                'fa_vizite_ulterioare', 'fa_discipoli', 'explica_ti_convingerile', 'cursant_cuvantare', 'discutie_eficienti_in_predicare', 
                'viata_de_crestin_teme', 'necesitati_locale', 'conducator_studiu_de_carte', 'cititor_la_studiu_de_carte', 'cuvantare_de_weekend', 
                'presedinte_la_final_de_saptamana', 'cititor_la_turn', 'om_de_ordine'];
                for ($i=0; $i < count($privilegii_table); $i++) { 
                    echo '<option value="'.$privilegii_table[$i].'">'.$privilegii_table[$i].'</option>';
                }
            }
            function verificam_delete_list($id, $lista){
                for ($i=0; $i < count($lista -> delete); $i++) { 
                    if($id == $lista -> delete[$i] -> id){
                        return true;
                    }
                }
                return false;
            }
        //
    //
?>