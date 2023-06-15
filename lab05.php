<?php

$username = $_POST["name"];
$password = $_POST["pwd"];

if($username === "lab5" && password_verify($password, '$2y$10$yXwRTwvaul3z0Pt1246iCOWyaXVYK78CJHPGcYQfCtGTcquIJCXsy')) {
    setcookie("authorized_by", get_current_user());
    $_COOKIE["authorized_by"] = get_current_user();
}

if(isset($_POST["favorite_color"])) {
    setcookie("favorite_color", $_POST["favorite_color"]);
    $_COOKIE["favorite_color"] = $_POST["favorite_color"];
}

if(isset($_POST['catchphrase'])) {
    setcookie("catchphrase", $_POST["catchphrase"]);
    $_COOKIE["catchphrase"] = $_POST["catchphrase"];
}

if(isset($_POST["logout"])) {
    setcookie("authorized_by", "", time() - 3600);
    unset($_COOKIE["authorized_by"]);
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>CMPS 3680 Lab 5</title>
    </head>
    <body>
        <h1>CMPS 3680 Lab 5</h1>
<?php
if(!isset($_COOKIE["authorized_by"])) {
?>
    <form action = "<?= $_SERVER["PHP_SELF"]?>" method = "POST">
        <label for = "name">Name:</label><br>
        <input type = "text" name = "name" value = "<?= $_POST["name"]?>"required><br>
        <label for = "pwd">Password:</label><br>
        <input type = "password" name = "pwd" value = "<?= $_POST["pwd"]?>" required><br><br>
        <input type = "submit" name = "login" value = "Log in">
    </form>
<?php
}
else {
?>
    <form action = "<?= $_SERVER["PHP_SELF"]?>" method = "POST">
        <input type = "submit" name = "logout" value = "Log out"><br><br>
    </form>

    <form action = "<?= $_SERVER["PHP_SELF"]?>" method = "POST">
        <label for = "favorite_color">Pick your favorite color:</label><br>
        <input type = "color" name = "favorite_color" value = "<?= $_COOKIE["favorite_color"] ?? "#000000" ?>"><br><br>
        <label for = "catchphrase">Enter a catchphrase:</label><br>
        <input type = "text" name = "catchphrase" value = "<?= $_COOKIE["catchphrase"] ?? "Deafult text" ?>"><br><br>
        <input type = "submit" name = "update_preferences" value = "Save">
    </form>
<?php
}
?>        
    </body>
</html>