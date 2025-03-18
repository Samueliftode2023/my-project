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
?>