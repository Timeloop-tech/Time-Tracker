<?php
    include_once('header.php');
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

    $sql = "SELECT username FROM users WHERE user_id = $id";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();
    echo "<h2>Welcome,".$row['username']." </h2>";
?>
<!--Link for Logout. -->
<a id="loguot" href="logout.php"><h3>Logout</h3></a>

<!--User's log table containing Date, Start_time, Stop_time and Comments. -->
<table id = "userLogTable">
    <thead>
        <th>Date</th>
        <th>Start Time</th>
        <th>Stop Time</th>
        <th colspan="2">Comment</th>
    </thead>
    <?php
        $id = $_SESSION['id'];
        $sql = "SELECT user_id , created_at , start_time , stop_time , comment From user_data WHERE user_id = $id";
        $result = $connection->query($sql);
        if (!$result->num_rows == 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tbody><tr>";
                echo "<td>" . $row['created_at'] . "</td>";
                echo "<td>" . $row['start_time'] . "</td>";
                echo "<td>" . $row['stop_time'] . "</td>";
                echo "<td colspan='2'>" . $row['comment'] . "</td>";
                echo "</tr></tbody>";
            }
        }
    ?>
        </tr>
    </tbody>
</table>
<br/><br/><br/>

<!--Form to Add Time Log.  -->
<form onsubmit="return submitData();">
    <fieldset>
        <legend>Log Time</legend>
        <div>
            <label>Date</label>
            <div>
                <input type="date" id="created_at" required autofocus>
            </div>
        </div><br/>
        <div>
            <label>Start Time</label>
            <div>
                <input type="time" id="st_time" required>
            </div>
        </div><br/>
        <div>
            <label>Stop Time</label>
            <div>
                <input type="time" id="end_time" required>
            </div>
        </div><br/>
        <div>
            <label>Comment</label>
            <div>
                <textarea id="comment" rows="3" required></textarea>
            </div>
        </div><br/>
        <div>
            <p id="logMessage"></p>
        </div>
        <button type="reset">Reset</button>
        <button type="submit">Log Time</button>
    </fieldset>
</form>
<br/><br/><br/>

<script>
    var user_id = <?= $_SESSION['id']; ?>;
    function submitData() {
        var created_at = document.getElementById('created_at').value;
        var st_time = document.getElementById('st_time').value;
        var end_time = document.getElementById('end_time').value;
        var comment = document.getElementById('comment').value;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4) {
                if (xhttp.status == 200) {
                    var data = JSON.parse(xhttp.responseText);
                    if(data.response_code == 1) {
                        var table = document.getElementById("userLogTable");
                        var row = table.insertRow(-1);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);
                        var cell3 = row.insertCell(2);
                        var cell4 = row.insertCell(3);
                        cell4.colSpan = 2;
                        cell1.innerHTML = data.response_data.created_at;
                        cell2.innerHTML = data.response_data.start_time;
                        cell3.innerHTML = data.response_data.stop_time;
                        cell4.innerHTML = data.response_data.comment;
                        document.getElementById('created_at').value = "";
                        document.getElementById('st_time').value = "";
                        document.getElementById('end_time').value = "";
                        document.getElementById('comment').value = "";
                        document.getElementById('logMessage').innerHTML = "Your log is added successfully."
                    }
                    else{
                        document.getElementById('logMessage').innerHTML = data.response_data;
                    }
                }
                else {
                    alert("Error communicating with Server!");
                }
            }
        }
        var parameters = "id=" + user_id + "&date=" + created_at + "&st_time=" + st_time + "&end_time=" + end_time + "&comment=" + comment;
        xhttp.open("POST", "add_log.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(parameters);
        return false;
    }
</script>

