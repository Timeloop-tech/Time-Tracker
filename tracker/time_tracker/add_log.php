<?php
    session_start();
    include_once('connection.php');
if(empty($_SESSION['id'])) {
    header("location:index.php");
}else {
    $id = $_SESSION['id'];
    $sql = "SELECT * FROM users WHERE user_id = $id";
    $result = $connection->query($sql);
    if ($result->num_rows == 0)
        header("location:index.php");
}

//Insert User's log to database.
    $sql = $connection->prepare($log_insert);
    $comment = htmlspecialchars($_POST['comment']);
    $sql->bind_param("issss", $_SESSION['id'], $_POST['date'], $_POST['st_time'], $_POST['end_time'], $comment);
    $sql->execute();

//Create a JSON Response string.
    $response_JSON = "{\"response_code\" :";
    if ($sql->error) {
        $response_JSON = $response_JSON . "\"-1\", \"response_data\" : \"";
        $response_JSON = $response_JSON . $sql->error;
        $response_JSON = $response_JSON . "\" }";
    } else {
        //Select last log entry from user_data table of particular User.
        $user_id = $_SESSION['id'];
        $sql = "SELECT user_id , created_at , start_time , stop_time , comment FROM user_data WHERE id=(SELECT id FROM user_data WHERE user_id=$user_id ORDER BY id DESC LIMIT 1)";
        $result = $connection->query($sql);
        $row = $result->fetch_assoc();
        $response_JSON = $response_JSON . "\"1\", \"response_data\" : ";
        $response_JSON = $response_JSON . json_encode($row);
        $response_JSON = $response_JSON . " }";
    }
    echo $response_JSON;
?>
