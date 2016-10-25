<?php
    if(empty($_SESSION['id'])) {
        header("location:index.php");
    }else{
        $id = $_SESSION['id'];
        $sql = "SELECT * FROM users WHERE user_id = $id";
        $result = $connection->query($sql);
        if($result->num_rows == 0)
            header("location:index.php");
    }
    session_start();
    unset($_SESSION['id']);
    session_destroy();
//Redirecting to login page
    header("Location: index.php");
    exit;
?>