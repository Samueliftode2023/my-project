<?php
function checkUsername($new_user, $conectareDB){
    $name_table = 'users';
    $username = $new_user;

    if(strlen($username) <= 4 || strlen($username) >= 41){
        $str_length = strlen($username);
        echo 'Numele introdus are: '.$str_length.' caractere. 
        Este necesar ca acesta sa aiba intre 5 - 40 de caractere';
        return false;
    }
    if(strpos($username, " ") !== false){
        echo 'Numele introdus continue spatii.';
        return false;
    }
    if(preg_match('/[\'"^£$%&*()}{#~!?><>,|=+¬]/', $username)){
        echo 'Numele nu trebuie sa contina simboluri.';
        return false;
    }
    $sql_search = 'SELECT * FROM '.$name_table.' WHERE username = "'.$username.'"';
    $execute_search = mysqli_query($conectareDB, $sql_search);
    $verify_user = mysqli_num_rows($execute_search);
    if($verify_user !== 0){
        echo 'Acest nume exista in baza de date, te ruga sa alegi alt nume.';
        return false;
    }
    return true;
}

function changeUsername($new_user, $conectareDB) {
    $tablesToRename = [
        $_SESSION['username']."_vestitori" => $new_user."_vestitori",
        $_SESSION['username']."_meetings" => $new_user."_meetings",
    ];

    mysqli_begin_transaction($conectareDB);

    try {
        $sql = 'UPDATE users 
                SET username = "'.$new_user.'" 
                WHERE username = "'.$_SESSION['username'].'"';
        if (!mysqli_query($conectareDB, $sql)) {
            throw new Exception("Eroare la actualizarea username-ului!");
        }

        foreach ($tablesToRename as $oldTableName => $newTableName) {
            $sql = "RENAME TABLE `$oldTableName` TO `$newTableName`";
            if (!mysqli_query($conectareDB, $sql)) {
                throw new Exception("Eroare la redenumirea tabelului $oldTableName în $newTableName!");
            }
        }

        mysqli_commit($conectareDB);
        $_SESSION['username'] = $new_user;
        echo 'ok';
    } catch (Exception $e) {
        mysqli_rollback($conectareDB);
        echo "Ceva nu a funcționat: " . $e->getMessage();
    }

    mysqli_close($conectareDB);
}

function checkPasswords($pass, $curr_pass, $conf_pass, $conectareDB) {
    $sql_search = 'SELECT * FROM users WHERE username = "'.$_SESSION['username'].'"';
    $execute_search = mysqli_query($conectareDB, $sql_search);
    $row_user =  mysqli_fetch_assoc($execute_search);

    if(!password_verify($curr_pass, $row_user['password'])){
        echo 'Pentru a realiza modificarile trebuie sa introduci parola curenta!';
        return false;
    }
    if(strlen($pass) <= 7 || strlen($pass) >= 41){
        $str_length = strlen($pass);
        echo 'Parola introdusa are: '.$str_length.' caractere. Este necesar ca acesta sa aiba intre 8 - 40 de caractere.';
        return false;
    }
    if(!preg_match('`[A-Z]`', $pass) || !preg_match('`[a-z]`',$pass) || !preg_match('`[0-9]`', $pass)){
        echo 'Parola nu respecta formatul.';
        return false;
    }
    if(strpos($pass, " ") !== false){
        echo 'Parola nu trebuie sa contina spatii!';
        return false;
    }
    if($pass !== $conf_pass){
        echo 'Parola nu a fost confirmata!';
        return false;
    }
    return true;
}

function changePassword($pasword, $conectareDB){
    $for_session = $pasword;
    $pasword = password_hash($pasword, PASSWORD_DEFAULT);
    $sql = 'UPDATE users 
    SET password = "'.$pasword.'" 
    WHERE username = "'.$_SESSION['username'].'"';
    if (!mysqli_query($conectareDB, $sql)) {
        echo 'Ceva nu a functionat, incearca din nou!';
    }
    else{
        $_SESSION['password'] = $for_session;
        echo 'ok';
    }
}

function checkNameKey($key, $conectareDB){
    if(strlen($key) <= 4 || strlen($key) >= 41){
        $str_length = strlen($key);
        echo 'Numele introdus are: '.$str_length.' caractere. 
        Este necesar ca acesta sa aiba intre 5 - 40 de caractere';
        return false;
    }
    if(strpos($key, " ") !== false){
        echo 'Numele introdus continue spatii.';
        return false;
    }
    if(preg_match('/[\'"^£$%&*()}{#~!?><>.,|=+¬]/', $key)){
        echo 'Numele nu trebuie sa contina simboluri.';
        return false;
    }
    return true;
}

function changeNameKey($key, $conectareDB){
    $key = $key.'.json';
    $sql = 'UPDATE users 
        SET key_user = "'.$key.'" 
        WHERE username = "'.$_SESSION['username'].'"';
    if (!mysqli_query($conectareDB, $sql)) {
        echo 'Ceva nu a functionat, incearca din nou!';
    }
    else{
        echo 'ok';
    }
}
?>