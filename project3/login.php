<?php

ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();

require_once("db.php");

if(!isset($_SESSION["logged_in"])) {
    $_SESSION["logged_in"] = false;
    $_SESSION["isCorrect"] = true;
}

function check_login($username, $passwd) {
    
    $db = get_connection();
    $usrname_match = false;

    $query = $db->prepare('SELECT * FROM account');

    if(!$query->execute()) {
        die(mysqli_error($db) . "<br>");
    } 

    $result = $query->get_result();

    while($row = $result->fetch_assoc()) {
        if($row["username"] == $username) {
            $usrname_match = true;
            $pwd_match = password_verify($passwd, $row["user_password"]);
        }
    }

    if($pwd_match && $usrname_match) {
        $_SESSION["logged_in"] = true;
        $_SESSION["isCorrect"] = true;
        $_SESSION["username"] = $username;
    }
    else {
        $_SESSION["logged_in"] = false;
        $_SESSION["isCorrect"] = false;
    }
}

function create_account($new_username, $new_passwd, $re_enter_passwd) {
    $db = get_connection();
    $valid = true;

    $query = $db->prepare('SELECT * FROM account');

    if(!$query->execute()) {
        die(mysqli_error($db) . "<br>");
    } 

    $result = $query->get_result();

    while($row = $result->fetch_assoc()) {
        if($row["username"] == $new_username) {
            $valid = false;
            echo "<p style = 'color: red'>Username already exists<p><br>";
        }
    }

    if(strlen($new_username) < 3 || strlen($new_passwd) < 6) {
        $valid = false;
        echo "<p style = 'color: red'>Username/password is too short<p><br>";
    }

    if($new_passwd != $re_enter_passwd) {
        $valid = false;
        echo "<p style = 'color: red'>Passwords must match<p><br>";
    }

    if($valid) {
        $hashed_passwd = password_hash($new_passwd, PASSWORD_DEFAULT);
        $query = $db->prepare('INSERT INTO account (username, user_password) VALUES (?, ?)');
        $query->bind_param("ss", $new_username, $hashed_passwd);

        if(!$query->execute()) {
            die(mysqli_error($db) . "<br>");
        } 

        $_SESSION["logged_in"] = true;
        $_SESSION["username"] = $new_username;
?>
        <script>
            alert('Account was successfully created!');
            location.replace("https://www.cs.csub.edu/~emorris/3680/project3/index.php");
        </script>
<?php

    }

    return $valid;
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Caiber</title>
        <link rel = "icon" href = "kyber-icon.jpg">
        <style>
            :root{
                --font: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 
                'Lucida Sans', Arial, sans-serif
            }
            
            .header {
                font-family: var(--font);
                color: white;
                background-color: #333333;
                height: 100px;
                padding: 20px;
                padding-top: 30px;
                margin-top: -26px;
                margin-bottom: 50px;
                margin-left: -8px;
                margin-right: -8px;
            }
        </style> 

    </head>
    <body>
        <div class = "header">
            <h1 style = "margin-bottom: -40px;">Caiber</h1><br><br><br>
            <a href = "index.php" style = "color: white">Back to store</a>
        </div>
<?php
if(!$_SESSION["logged_in"] && !isset($_GET["createAccount"])) {
?>
        <form action = "<?= htmlspecialchars($_SERVER["PHP_SELF"])?>" method = "POST">
            <label for = "username">Username</label> 
            <input type = "text" name = "username" required><br><br>
            <label for = "pwd">Password</label>
            <input type = "password" name = "pwd" required><br><br>
            <input type = "submit" name = "login" value = "Log in"><br><br>
        </form>

        <a href = "<?=htmlspecialchars($_SERVER["PHP_SELF"])."?createAccount=true"?>">Don't have an account? Create one here!</a>
<?php

}

if(isset($_GET["createAccount"])) {
    $_SESSION["isCorrect"] = true;
?>
    <a href = "<?=htmlspecialchars($_SERVER["PHP_SELF"])?>">Back</a><br><br>
    <form action = "<?= htmlspecialchars($_SERVER["PHP_SELF"])."?createAccount=true"?>" method = "POST">
        <label for = "new_username">Create a username</label>
        <input type = "text" name = "new_username" required><br><br>
        <label for = "new_pwd">Create a password</label>
        <input type = "password" name = "new_pwd" required><br><br>
        <label for = "reenter_pwd">Re-enter password</label>
        <input type = "password" name = "reenter_pwd" required><br><br>
        <input type = "submit" name = "create_account" value = "Create Account">
    </form>
<?php
    if(isset($_POST["create_account"])) {
        $new_username = $_POST["new_username"];
        $new_password = $_POST["new_pwd"];
        $re_enter_password = $_POST["reenter_pwd"];
        create_account($new_username, $new_password, $re_enter_password);
    }
}

if(isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["pwd"];
    check_login($username, $password);
    if($_SESSION["logged_in"]) {
        header("Location: index.php");
    }
    else if(!$_SESSION["isCorrect"]){
        echo "<p style = 'color: red'>Invalid login, incorrect username and/or password<p><br>";
    }
}

?>
    </body>
</html>