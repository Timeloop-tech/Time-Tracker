<?php
    include_once('../config.php');

//Create a connection to database.
    $connection = new mysqli($servername , $username , $password , $dbname);

    if($connection->connect_error){
        die("Connection Failed:".$connection->connect_error);
    }else{
        $user_insert = "INSERT INTO users(username , password , is_admin) VALUES(?,?,?)";
        $user_login = "SELECT user_id,is_admin FROM users where  BINARY username=? and BINARY password=?";
        $log_insert = "INSERT INTO user_data(user_id , created_at , start_time , stop_time , comment) VALUES (?,?,?,?,?)";
    }
?>