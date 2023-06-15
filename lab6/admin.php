<?php

ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();

require_once("db.php");

if(!isset($_SESSION["logged_in"])) {
    $_SESSION["logged_in"] = false;
}

function check_passwd($passwd) {
    
    $db = get_connection();

    $query = $db->prepare('SELECT * FROM lab6_credentials');

    if(!$query->execute()) {
        die(mysqli_error($db) . "<br>");
    } 

    $result = $query->get_result();

    while($row = $result->fetch_assoc()) {
        $pwd_match = password_verify($passwd, $row["blog_password"]);
    }

    if($pwd_match == true) {
        $_SESSION["logged_in"] = true;
        header("Location: admin.php");
    }
    else {
        $_SESSION["logged_in"] = false;
        header("Location: admin.php");
    }
}

function post_blog() {
    $db = get_connection();

    if(isset($_POST["blog_header"])) {
        $blog_header = htmlspecialchars($_POST["blog_header"]);
    }

    if(isset($_POST["blog"])) {
        $blog_content = htmlspecialchars($_POST["blog"]);
    }

    if(strlen($blog_header) > 0 && strlen($blog_content) > 0) {
        $query = $db->prepare('INSERT INTO lab6 (blog_title, blog_content) VALUES (?, ?)');
        $query->bind_param("ss", $blog_header, $blog_content);

        if(!$query->execute()) {
            die(mysqli_error($db) . "<br>");
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>CMPS 3680 Lab 6</title>
    </head>
    <body>
<?php
if($_SESSION["logged_in"] == false) {
?>
        <form action = "<?= $_SERVER["PHP_SELF"]?>" method = "POST"> 
            <label for = "pwd">Password</label>
            <input type = "password" name = "pwd" required><br><br>
            <input type = "submit" name = "login" value = "Log in">
        </form>
<?php
}
else {
?>
        <form action = "<?= htmlspecialchars($_SERVER["PHP_SELF"])?>" method = "POST">  
            <label for = "blog">Write a blog post:</label><br>
            <textarea name = "blog" rows = "10" cols = "50" required></textarea><br>
            <label for = "blog_header"> Write a header for the post:</label><br>
            <input type = "text" name = "blog_header" required><br><br>
            <input type = "submit" name = "post_blog" value = "Post blog"><br><br>
        </form>
        <a href = "index.php">Click here to view blogs</a><br>
<?php
}

if(isset($_POST["login"])) {
    $password = $_POST["pwd"];
    check_passwd($password);
}

if(isset($_POST["post_blog"])) {
    post_blog();
}

#echo var_dump($_SESSION);
?>
    </body>
</html>
