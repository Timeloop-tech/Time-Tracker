<?php include_once('header.php');
      include_once('connection.php');
    if(empty($_SESSION['id'])) {
        header("location:index.php");
    }
    else{
        $id = $_SESSION['id'];
        $sql = "SELECT * FROM users WHERE user_id = $id";
        $result = $connection->query($sql);
        if($result->num_rows == 0)
            header("location:index.php");
    }
//Get Users from database.
        $array = [];
        $sql = "SELECT user_id FROM users WHERE is_admin=0";
        $result = $connection->query($sql);
        if($result->num_rows > 0){
            $i = 0;
            while($row = $result->fetch_assoc()){
                $array[$i] = $row['user_id'];
                $i+=1;
            }
        }
?>
<h2>Welcome, Admin</h2>
<!--Logout link-->
<a href="logout.php"><h3>Logout</h3></a>
<!--Userlog Table with Username And Total time spent.-->
<div>
    <table id="userLogTable" style="width: 30%;margin-left: 600px;">
        <thead>
        <th>Username</th>
        <th>Total Time Spent</th>
        </thead>
        <?php if(!empty($array)){
            foreach($array as $id){
            //Get user's time log from database
                $sql = "SELECT TIME (sum(stop_time - start_time)) as total FROM user_data WHERE user_id = $id";
                $result = $connection->query($sql);
                $row = $result->fetch_assoc();
            //Get username from database
                $query = "SELECT username FROM users WHERE user_id = $id";
                $r = $connection->query($query);
                $answer = $r->fetch_assoc();
                if(!empty($row['total'])){
                    echo "<tbody><tr><td>";
                    echo $answer['username'];
                    echo "</td><td>";
                    echo $row['total'];
                    echo "</td></tr></tbody>";
                }
                else{
                    echo "<tbody><tr><td>";
                    echo $answer['username'];
                    echo "</td><td>00:00:00</td></tr></tbody>";
                }
            }
        }
        ?>
    </table>
</div>
<br/><br/><br/>

<!--Form to Add new User/Admin. -->
<form onsubmit="return submitUserData();">
<fieldset>
    <legend>Create New User</legend>
    <div>
        <label>Username</label>
        <div>
            <input type="text" id="username" placeholder="Username" required autofocus minlength="6" maxlength="15">
        </div>
    </div><br/>
    <div>
        <label>Password</label>
        <div>
            <input type="password" id="password" placeholder="Password" required maxlength="15" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
        </div>
    </div><br/>
    <div>
        <label>Is Admin?</label>
        <div>
            <input type="checkbox" id="isAdmin">
        </div>
    </div><br/>
    <div>
        <p id="resultMessasge"></p>
    </div>
    <div>
        <div>
            <button type="reset">Reset</button>
            <button type="submit">Create New User</button>
        </div>
    </div>
    <br/>
</fieldset>
</form>

<script>
    function submitUserData(){
        var username = document.getElementById('username').value;
        var pwd = document.getElementById('password').value;
        if(document.getElementById('isAdmin').checked) {
            var isAdmin = 1;
        }else {
            var isAdmin = 0;
        }
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4) {
                if (xhttp.status == 200) {
                    var data = JSON.parse(xhttp.responseText);
                    if (data.response_code == 1) {
                        if (data.is_admin == "0") {
                            document.getElementById('resultMessasge').innerHTML = "User Is Added Successfully.";
                            var table = document.getElementById("userLogTable");
                            var row = table.insertRow(-1);
                            var cell1 = row.insertCell(0);
                            var cell2 = row.insertCell(1);
                            cell1.innerHTML = data.response_data.username;
                            cell2.innerHTML = "00:00:00";
                            document.getElementById('username').value = "";
                            document.getElementById('password').value = "";
                            document.getElementById('isAdmin').checked = false;
                        }
                        else {
                            document.getElementById('resultMessasge').innerHTML = "Admin Is Added Successfully.";
                            document.getElementById('username').value = "";
                            document.getElementById('password').value = "";
                            document.getElementById('isAdmin').checked = false;
                        }
                    }
                    else {
                        document.getElementById('resultMessasge').innerHTML = data.response_data;
                        document.getElementById('username').value = "";
                        document.getElementById('password').value = "";
                        document.getElementById('isAdmin').checked = false;
                    }
                }
                else {
                    alert("Error communicating with Server!");
                }
            }
        }
        var parameters = "uname=" + username + "&pwd=" + pwd + "&isAdmin=" + isAdmin;
        xhttp.open("POST", "add_user.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(parameters);
        return false;
    }
</script>



