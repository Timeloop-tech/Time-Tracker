<?php include_once('header.php');
      include_once('connection.php'); ?>

<?php if(isset($_POST['login'])):
    $stmt = $connection->prepare($user_login);
    $username = mysqli_real_escape_string($connection , $_POST['username']);
    $password = mysqli_real_escape_string($connection , $_POST['password']);
    $password = md5($password);
    $stmt->bind_param("ss" , $username , $password);
    $stmt->execute();
    $stmt->bind_result($user_id , $is_admin);
    $stmt->fetch();

    if(empty($user_id)){
        header("Location: index.php?is_valid=false");
    }else{
        $_SESSION['id'] = $user_id;
        unset($_SESSION['login_error']);
        if($is_admin == 0){
            header("location:tracker.php");
        }
        else{
            header("location:admin.php");
        }
    }
endif;?>

<!-- Login form -->
<div>
    <form id="loginForm"  method="post">
        <fieldset>
            <legend>Login</legend>
            <div>
                <label>Username</label>
                <div>
                    <input type="text" name="username"  placeholder="Username" required autofocus minlength="6" maxlength="15">
                </div>
            </div><br/>
            <div>
                <label>Password</label>
                <div style="margin-top: -15px;">
                    <div style="float:left;"><input type="password" name="password" placeholder="Password" required maxlength="15" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"></div>
                    <div style="clearfix:both;width: 300px;padding-left: 200px;"><p>* Password must contain at least one number, one uppercase and lowercase letter, at least 8 or more characters</p></div>
                </div>
            </div>
            <div>
                <div>
                    <button type="reset">Reset</button>
                    <button type="submit" name="login">Login</button>
                </div>
            </div>
        </fieldset>
    </form>
    <p id="errorMessage"><?php echo isset($_GET['is_valid']) ?  "Username Or Password Is Incorrect!!" : ""; ?></p>
</div>

<?php include_once('footer.php'); ?>