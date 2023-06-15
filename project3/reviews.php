<?php
    ini_set('session.gc_maxlifetime', 3600);
    session_set_cookie_params(3600);
    session_start();

    require_once("db.php");

    $pname = htmlspecialchars($_GET["pname"]);
    
    function post_review($productname, $review_content, $review_title, $rating) {
        $db = get_connection();
        $query = $db->prepare('SELECT product.productid FROM product WHERE productname LIKE ?');
        $query->bind_param('s', $productname);

        if(!$query->execute()) {
            die(mysqli_error($db) . "<br>");
        } 

        $result = $query->get_result();
        $row = $result->fetch_assoc();

        $productid = $row["productid"];

        $username = $_SESSION["username"];
        $query = $db->prepare('INSERT INTO reviews (username, productid, 
        review_title, review_content, rating) VALUES (?, ?, ?, ?, ?)');
        $query->bind_param('sissi', $username, $productid, $review_title, $review_content, $rating);

        if(!$query->execute()) {
            die(mysqli_error($db) . "<br>");
        } 
    }

    function get_reviews($productname) {
        $db = get_connection();
        $query = $db->prepare('SELECT reviews.* FROM reviews NATURAL JOIN 
        product WHERE productname LIKE ? ORDER BY date_written DESC');
        $query->bind_param('s', $productname);

        if(!$query->execute()) {
            die(mysqli_error($db) . "<br>");
        } 

        $result = $query->get_result();

        while($row = $result->fetch_assoc()) {
            echo "<div class = 'content'>";
            echo "<h1>" . $row["review_title"] . "</h1>";
            echo "<h2>Posted on " . $row["date_written"] . " by ". $row["username"] . "</h2>";
            echo "<div>" . $row["review_content"] . "</div><br>";
            echo "<span style = 'background-color: yellow'>Rating: " . $row["rating"] . "/10</span><br><br>";
            echo "</div>";
        }
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
                --bounds: 2px solid lightgray;
                --font: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 
                'Lucida Sans', Arial, sans-serif
            }
            .content {
                border-top: var(--bounds);
                font-family: var(--font);
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
    <body style = "background-color: beige;">
        <div class = "header">
            <a href = "cart.php" style = "padding-left: 1470px; color: white">View Cart</a>
            <h1 style = "margin-bottom: -30px;">Caiber</h1>
        </div>
        <a href = "index.php">Back</a><br><br> 
        <?php
            $currentItem = $pname;
            $review_content = "";
            if(isset($_POST["post_review"]) && isset($_GET["pname"])) {
                $review_content = htmlspecialchars($_POST["review"]);
                $review_title = htmlspecialchars($_POST["review_title"]);
                $rating = htmlspecialchars($_POST["rating"]);
                post_review($pname, $review_content, $review_title, $rating);
            }
            if(isset($_POST["write_review"]) && $_SESSION["logged_in"]) {
        ?>
            <form action = "<?= htmlspecialchars($_SERVER["PHP_SELF"])."?pname=".$currentItem?>" method = "POST">  
                <label for = "review">Write a review:</label><br>
                <textarea name = "review" id = "content" rows = "10" cols = "50" required></textarea><br>
                <label for = "review_title"> Write a title for the review:</label><br>
                <input type = "text" name = "review_title" required><br><br>
                <label for = "rating"> Rate this item:</label><br>
                <input type = "text" name = "rating" required><br><br>
                <input type = "submit" name = "post_review" id = "post_review" value = "Post review"><br><br>
            </form>
        <?php
            }
            else if(isset($_POST["write_review"]) && !$_SESSION["logged_in"]) {
                header("Location: login.php");
            }
            
            if(isset($_GET["pname"]) && !isset($_POST["write_review"])) {
        ?>
                <form action = "<?= htmlspecialchars($_SERVER['PHP_SELF'])."?pname=".$currentItem?>" method = "POST">
                    <input type = "submit" name = "write_review" value = "Write review">
                </form><br>
        <?php
                get_reviews($pname);
            }

        ?>
    </body>
</html>