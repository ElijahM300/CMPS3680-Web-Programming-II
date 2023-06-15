<?php
    ini_set('session.gc_maxlifetime', 3600);
    session_set_cookie_params(3600);
    session_start();

    $dbFile = "store.db";
    $con = new SQLite3($dbFile);
    $con->exec('PRAGMA foreign_keys = ON;');

    function getProducts($productName) {
        global $con;

        $statement = "";
        if($productName != "") {
            $statement = $con->prepare("select * from product where productname like :productName");
            $statement->bindValue(":productName", $productName, SQLITE3_TEXT);
        }

        $result = $statement->execute();
        $result->finalize();

        while($row = $result->fetchArray(SQLITE3_ASSOC)) {
            return $row;
        }

        return array();
    }

    function getCategories() {
        global $con;

        $statement = $con->prepare("select * from category");

        $result = $statement->execute();
        $result->finalize();

        $results = [];

        while($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $results []= $row["categoryname"]; 
        }

        return $results;
    }
    
    function getProductCategories($category) {
        global $con;

        if($category == "all") {
            $statement = $con->prepare("select * from product");
        }
        else {
            $statement = $con->prepare("select product.* from productcategory natural join 
            product natural join category where categoryname like :category");
            $statement->bindValue(":category", $category, SQLITE3_TEXT);
        }

        $result = $statement->execute();
        $result->finalize();

        $results = [];

        while($row = $result->fetchArray(SQLITE3_ASSOC)) {
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
        <title>CMPS 3680 Project 2</title>
        <style>
            table, th, td {
                border: 1px solid #aaa;
                border-collapse: collapse;
                padding: 8px;
                margin-left: 20px;
            }

            tr:nth-child(even){background-color: #f2f2f2;}
        </style>
    </head>
    <body style = "background-color: beige;">
        <a href = "cart.php" style = "padding-left: 1500px;">View Cart</a>
        <h1 style = "font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 
        'Lucida Grande', 'Lucida Sans', Arial, sans-serif; padding-left: 650px;">Elijah's Shop</h1>
        <?php
            echo "<a style = 'padding-right: 10px; padding-left: 585px;' href =" . htmlspecialchars($_SERVER['PHP_SELF']) . 
            "?cat=all>all</a>" . " ";
            foreach($categories as $category){
                echo "<a style = 'padding-right: 10px' href =" . htmlspecialchars($_SERVER['PHP_SELF']) . 
                "?cat=" . $category . ">" . $category . "</a>" . " ";
            }
            
        ?>
        <form style = "padding-bottom: 15px; padding-top: 10px" 
        action = "<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method = "GET">
            <input style = "margin-left: 625px;"type = "text" name = "ItemSearch" placeholders = "Item search" required>
            <input type = "submit" name = "SendIt" value = "Search">
        </form>
        <br><br>
<?php
    if(isset($_GET["SendIt"])) {
        $name = htmlspecialchars($_GET["ItemSearch"]);
        $query = getProducts($name);
        echo "<table>";
        echo "<tr>";
        echo "<th>Product ID</th>";
        echo "<th>Product Name</th>";
        echo "<th>Price</th>";
        echo "<th>Stock</th>";
        echo "<th>Action</th>";
        echo "</tr>";
        echo "<tr>";
        foreach($query as $query=>$results) {
            if($query == "productid") {
                $i = $results;
            }
            echo "<td>".$results."</td>";
        }
        echo "<td><form method = 'POST'><input type = 'submit' name = 'add".$i."' value = 'Add to cart'></form></td>";
        echo "</tr>";
        echo "</table>";
    }

    if(isset($_GET["cat"])) {
        $cat = htmlspecialchars($_GET["cat"]);
        $query = getProductCategories($cat);
        echo "<table border = '1'>";
        echo "<tr>";
        echo "<th>Product ID</th>";
        echo "<th>Product Name</th>";
        echo "<th>Price</th>";
        echo "<th>Stock</th>";
        echo "<th>Action</th>";
        echo "</tr>";
        foreach($query as $query=>$results) {
            echo "<tr>";
            foreach($results as $results=>$result) {
                if($results == "productid") { 
                    $i = $result;
                }
                echo "<td>".$result."</td>";
            }
            echo "<td><form method = 'POST'><input type = 'submit' name = 'add" . $i ."' value = 'Add to cart'></form></td>";
            echo "</tr>";
        }
        echo "</table>";      
    }

    $set = 0;
    for($i = 1; $i < 6; $i++) {
        if(isset($_POST["add".$i])) {
            $_SESSION["cart"][$i]++;
            $set++;
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