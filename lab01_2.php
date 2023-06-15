<?php

define('DEVELOPER', true);
$name = "Elijah"; 
if(DEVELOPER) {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    ini_set("display_startup_errors", 1);
    ini_set("error_log", './lab1.log');
}


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>My website is the coolest</title>
    </head>
    <body>
        <h1>My cool website</h1>
        <p>Welcome to my cool website!</p>
        <p><?php echo $name; ?></p>
        <p><?php echo $myName; ?></p>
    </body>
</html>