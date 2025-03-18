<?php
    function check_activity($username, $name_base, $conectareDB){
        $sql = 'SELECT * FROM '.$name_base.' WHERE username = "'.$username.'"';
        $query =  mysqli_query($conectareDB, $sql);
        $exist_user = mysqli_num_rows($query);
        if($exist_user !== 0){
            $user_row =  mysqli_fetch_assoc($query);
            return $user_row['key_user'];
        }
    }
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
    function check_vestitor($full_name, $privilege, $genul){
        $gen = ['Masculin','Feminin'];
        $privilege_array = ['Vestitor', 'Slujitor auxiliar', 'Batran', 'Vestitor nebotezat', 'Inscris doar la scoala'];
        if($genul === 'Feminin' && $privilege !== 'Vestitor' && $privilege !== 'Vestitor nebotezat' && $privilege !== 'Inscris doar la scoala'){
            echo 'Atentie - Formatul pe care l-au introdus nu este corect, pentru genul feminin exista doar privilegiul de vestitor!';
            return false;
        }
        if(!in_array($genul, $gen)){
            echo 'Atentie - Exista doar doua genuri prestanilite!';
            return false;
        }
        if(!in_array($privilege, $privilege_array)){
            echo 'Atentie - Nu este permisa adaugarea de noi privilegii!';
            return false;
        }
        if(strlen($full_name) >= 50 || strlen($full_name) <= 2 ){
            echo 'Atentie - Numele este prea mare sau prea mic!';
            return false;
        }
        if(preg_match('/[\'^£$%&"*()}{@#~!?><>,_|=+¬]/', $full_name)){
            echo 'Atentie - Nu este permisa folosirea simbolurilor speciale!';
            return false;
        }
        if(isset($_SESSION['create-key'])){
            $user_data_base = json_decode($_SESSION['create-key'], true);
            $vestitori = [];
            $vestitori = array_merge($vestitori, $user_data_base['vestitori']);
            $numar_vestitori = count($vestitori);
            for ($i=0; $i < $numar_vestitori; $i++) { 
                $verificare_dubluri = $vestitori[$i]['nume'];
                if(verify_dubble($full_name, $verificare_dubluri)){
                    echo 'Atentie - Acest nume a fost deja adaugat!';
                    return false;
                }
            }
        }
        return true;
    }
    function vestitor_object($full_name, $privilege, $genul){
        $vestitori = [];
        $id = 0;
        if(isset($_SESSION['create-key'])){
            $user_data_base = json_decode($_SESSION['create-key'], true);
            $id = count($user_data_base['vestitori']);
            $vestitori = array_merge($vestitori, $user_data_base['vestitori']);
        }
        $vestitor = new stdClass();
        $vestitor -> id = $id;
        $vestitor -> nume = $full_name;
        $vestitor -> privilegiu = $privilege;
        $vestitor -> gen = $genul;
        array_push($vestitori, $vestitor);

        $user_data_base = new stdClass();
        $user_data_base -> vestitori = $vestitori;
        $user_data_base -> congregatie = 'Nedefinita';
        $user_data_base -> supraveghetor = 'Nedefinit';
        $user_data_base -> vorbitori = [];
        $user_data_base -> emailuri = [];

        $user_data_base = json_encode($user_data_base);
        $_SESSION['create-key'] = $user_data_base;
        echo $user_data_base;
    }
    // VERIFICAM DACA USERUL ARE O CHEIE EXISTENTA
        if(check_activity($_SESSION['username'], 'users', $conectareDB) != 0){
            header('Location:../');
            exit;
        }
    //
?>