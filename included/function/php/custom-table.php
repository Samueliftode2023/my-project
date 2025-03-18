<?php
function check_name_table($name_table, $conectareDB){
    if(strlen($name_table) <= 4 || strlen($name_table) >= 41){
        $str_length = strlen($name_table);
        echo 'Numele introdus are: '.$str_length.' caractere. 
        Este necesar ca acesta sa aiba intre 5 - 40 de caractere';
        return false;
    }
    else if(strpos($name_table, " ") !== false){
        echo 'Numele introdus continue spatii.';
        return false;
    }
    else if(preg_match('/[^a-zA-Z0-9_]/', $name_table)){
        echo 'Numele nu trebuie sa contina simboluri.';
        return false;
    }
    else{
        $name_table_sql = $_SESSION['username'].'_'.'custom_table';
        $sql = "SHOW TABLES LIKE '".$name_table_sql."'";
        $result = mysqli_query($conectareDB, $sql);
        if (mysqli_num_rows($result) > 0) {
            $sql = 'SELECT * FROM ' .$name_table_sql . '
            WHERE nume_tabel = "'.$name_table.'"';
            $result = mysqli_query($conectareDB, $sql);
            if (mysqli_num_rows($result) > 0) {
                echo 'Acest nume exista deja!';
                return false;
            }
            else{
                $date_creation = date("y-m-d");
                $columns = "nume_tabel, structura, data";
                $value = "'".$name_table."','0','".$date_creation."'";
                insert_data($conectareDB, $name_table_sql, $columns, $value);
            }
        } 
        else {
            $sql = "
            CREATE TABLE ".$name_table_sql." (
                id int(60) AUTO_INCREMENT PRIMARY KEY,
                nume_tabel VARCHAR(255) NOT NULL,
                structura TEXT NOT NULL,
                data VARCHAR(255) NOT NULL
            )";
            if(!mysqli_query($conectareDB, $sql)){
                echo 'Ceva nu a functionat!';
                return false;
            }
            else{
                $date_creation = date("y-m-d");
                $columns = "nume_tabel, structura, data";
                $value = "'".$name_table."','0','".$date_creation."'";
                insert_data($conectareDB, $name_table_sql, $columns, $value);
            }
        }
        return true;
    }
}

function readStructuraTabel($name_str, $conectareDB){
    $name_table_sql = $_SESSION['username'].'_'.'custom_table';
    $sql = 'SELECT * FROM ' .$name_table_sql . '
    WHERE nume_tabel = "'.$name_str.'"';

    $result = mysqli_query($conectareDB, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo $row['structura'];
    }
}

?>