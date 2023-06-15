<?php
    ini_set('session.gc_maxlifetime', 3600);
    session_set_cookie_params(3600);
    session_start();

    require_once("db.php");

    function getProducts($productName) {
        $db = get_connection();
        if($productName != "") {
            $query = $db->prepare('SELECT * FROM product WHERE productname LIKE ?');
            $query->bind_param('s', $productName);
        }

        if(!$query->execute()) {
            die(mysqli_error($db) . "<br>");
        } 

        $result = $query->get_result();

        while($row = $result->fetch_assoc()) {
            return $row;
        }

        return array();
    }

    function getCategories() {
        $db = get_connection();
        $query = $db->prepare('SELECT * FROM category');

        if(!$query->execute()) {
            die(mysqli_error($db) . "<br>");
        } 

        $result = $query->get_result();

        $results = [];

        while($row = $result->fetch_assoc()) {
            $results []= $row["categoryname"]; 
        }

        return $results;
    }
    
    function getProductCategories($category) {
        $db = get_connection();
        if($category == "all") {
            $query = $db->prepare('SELECT * FROM product');
        }
        else {
            $query = $db->prepare('SELECT product.* FROM productcategory NATURAL JOIN product
            NATURAL JOIN category WHERE categoryname LIKE ?');
            $query->bind_param('s', $category);
        }

        if(!$query->execute()) {
            die(mysqli_error($db) . "<br>");
        } 
        
        $result = $query->get_result();

        $results = [];

        while($row = $result->fetch_assoc()) {
            $results []= $row;
        }
        return $results;
    }

    $categories = getCategories();
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

            .search {
                margin-left: 120px;
                height: 20px;
            }
        </style>
    </head>
    <body style = "background-color: beige;">
        <div class = "header">
            <a href = "cart.php" style = "padding-left: 1470px; color: white">View Cart</a>
            <h1 style = "margin-bottom: -30px;">Caiber</h1>
            <form style = "margin-bottom: -28px"
            action = "<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method = "GET">
                <input class = "search" type = "text" size = "50" name = "ItemSearch" placeholders = "Item search" required>
                <input type = "submit" name = "SendIt" value = "Search">
            </form>
            <br><br>
        <?php
            echo "<a style = 'padding-right: 10px; color: white' href =" . htmlspecialchars($_SERVER['PHP_SELF']) . 
            "?cat=all>all</a>" . " ";
            foreach($categories as $category){
                echo "<a style = 'padding-right: 10px; color: white' href =" . htmlspecialchars($_SERVER['PHP_SELF']) . 
                "?cat=" . $category . ">" . $category . "</a>" . " ";
            }
            
            if($_SESSION["logged_in"] == false) {
        
                echo "<a href = 'login.php' style = 'padding-left: 160px; color: white'>Login</a>";
        
            }
            else if(isset($_GET["logout"])) {
                unset($_SESSION["logged_in"]);
                unset($_SESSION["username"]);
                echo "<a href = 'login.php' style = 'padding-left: 160px; color: white'>Login</a>";
            }
            else {        
         
                echo "<a style = 'padding-left: 155px; color: white' href = " . htmlspecialchars($_SERVER['PHP_SELF']) . 
                "?logout=true >Logout</a>";
        
            }
        ?>
        </div>
<?php
    $descriptors = ["Product ID", "Product Name", "Price", "Stock"];
    $iterator = 0;
    if(isset($_GET["SendIt"])) {
        $name = htmlspecialchars($_GET["ItemSearch"]);
        $query = getProducts($name);
        echo "<div class = 'content'>";
        echo "<br>";
        if ($query["productid"] == "") {
            echo "<h1>Sorry, that item could not be found<h1><br>";
        }
        else {
            foreach($query as $query=>$results) {
                if($query == "productid") {
                    $i = $results;
                }
                if($query == "productname") {
                    $pname = $results;
                }
                echo "<h2>" . $descriptors[$iterator] . ": " . $results . "</h2>";
                $iterator++;
            }
            echo "<form method = 'POST'><input type = 'submit' name = 'add".$i."' value = 'Add to cart'></form>";
            echo "<br>";
            echo "<a href = reviews.php?pname=". $pname . ">View reviews</a><br><br>";
            echo "</div>";
        }
    }

    if(isset($_GET["cat"])) {
        $cat = htmlspecialchars($_GET["cat"]);
        $query = getProductCategories($cat);
        echo "<div>";
        foreach($query as $query=>$results) {
            echo "<div class = 'content'>";
            echo "<br>";
            foreach($results as $results=>$result) {
                if($results == "productid") { 
                    $i = $result;
                }

                if($results == "productname") {
                    $pname = $result;
                }
                echo "<h2>" . $descriptors[$iterator]. ": ". $result . "</h2>";
                $iterator++;
            }
            echo "<form method = 'POST'><input type = 'submit' name = 'add" . $i ."' value = 'Add to cart'></form>";
            echo "<br>";
            echo "<a href = reviews.php?pname=". $pname . ">View reviews</a><br><br>";
            echo "</div>";
            $iterator = 0;
        }
        echo "</div>";      
    }

    $set = 0;
    for($i = 1; $i < 11; $i++) {
        if(isset($_POST["add".$i])) {
            $_SESSION["cart"][$i]++;
            $set++;
            echo "<script>alert('Item was added to youe cart.')</script>";
        }
    }

    if($set > 0) {
        $_SESSION["clearCart"] = false;
        $_SESSION["set"] = true;
    }
    else if(!isset($_SESSION["clearCart"])){
        $_SESSION["clearCart"] = true;
        $_SESSION["set"] = false;
    }
?>
    </body>
</html>