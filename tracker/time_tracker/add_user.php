<?php
session_start();
include_once('connection.php');
if(empty($_SESSION['id'])) {
    header("location:index.php");
}else{
    $id = $_SESSION['id'];
    $sql = "SELECT * FROM users WHERE user_id = $id";
    $result = $connection->query($sql);
    if($result->num_rows == 0)
        header("location:index.php");
}

    $username = mysqli_real_escape_string($connection,htmlspecialchars($_POST['uname']));
    $sql = "SELECT * FROM users WHERE BINARY username = '$username'";

    $result = $connection->query($sql);
    if($result->num_rows != 0){
        $response_JSON = "{\"response_code\":\"-1\",\"response_data\":\"Username alreay exists!\"}";
    }else {
        $password = mysqli_real_escape_string($connection, htmlspecialchars($_POST['pwd']));
        $password = md5($password);
        $is_admin = $_POST['isAdmin'];
        $stmt = $connection->prepare($user_insert);
        $stmt->bind_param("ssi", $username, $password, $is_admin);
        $stmt->execute();
        $response_JSON = "{\"is_admin\":\"" . $is_admin . "\",\"response_code\" :";
        if ($stmt->error) {
            $response_JSON = $response_JSON . "\"-1\", \"response_data\" : \"";
            $response_JSON = $response_JSON . $stmt->error;
            $response_JSON = $response_JSON . "\" }";
        } else {
            $sql = "SELECT username from users ORDER BY user_id DESC LIMIT 1";
            $result = $connection->query($sql);
            $row = $result->fetch_assoc();
            $response_JSON = $response_JSON . "\"1\", \"response_data\" : ";
            $response_JSON = $response_JSON . json_encode($row);
            $response_JSON = $response_JSON . " }";
        }
    }
    echo $response_JSON;
?>



