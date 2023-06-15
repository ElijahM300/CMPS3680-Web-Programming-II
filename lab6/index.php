<?php 

require_once("db.php");

echo "<a href = admin.php>Click here to write a blog</a><br>";
echo "<h1 class = 'header'>Blog Posts</h1>";

$db = get_connection();

//Retrieve blog posts in reverse-chronological order
$query = $db->prepare('SELECT * FROM lab6 ORDER BY written_on DESC');
if(!$query->execute()) {
    die(mysqli_error($db) . "<br>");
} 

$result = $query->get_result();

while($row = $result->fetch_assoc()) {
    echo "<h1>" . $row["blog_title"] . "</h1>";
    echo "<h2>Posted on " . $row["written_on"] . "</h2>";
    echo "<div>" . $row["blog_content"] . "</div>";
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>CMPS 3680 Lab 6</title>
        <style>
            h1 {
                font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 
                'Lucida Grande', 'Lucida Sans', 'Arial', 'sans-serif';
                text-decoration: underline overline;
            }
            h2 {
                font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 
                'Lucida Grande', 'Lucida Sans', 'Arial', 'sans-serif';
            }
            div {
                font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 
                'Lucida Grande', 'Lucida Sans', 'Arial', 'sans-serif';
            }

            .header {
                font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 
                'Lucida Grande', 'Lucida Sans', 'Arial', 'sans-serif';
                text-decoration: underline; 
            }
        </style>
    </head>
    <body>
    </body>
</html>