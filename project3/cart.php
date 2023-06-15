<?php
    ini_set('session.gc_maxlifetime', 3600);
    session_set_cookie_params(3600);
    session_start();

    require_once("db.php");

    function getProductName($productid) {
        $db = get_connection();
        $query = $db->prepare('SELECT productname FROM product WHERE productid LIKE ?');
        $query->bind_param('i', $productid);

        if(!$query->execute()) {
            die(mysqli_error($db) . "<br>");
        } 
        
        $result = $query->get_result();

        $results = [];

        while($row = $result->fetch_assoc()) {
            $results []= $row["productname"]; 
        }

        return $results;
    }

    function getProductPrice($productid) {
        $db = get_connection();
        $query = $db->prepare('SELECT price FROM product WHERE productid LIKE ?');
        $query->bind_param('i', $productid);

        if(!$query->execute()) {
            die(mysqli_error($db) . "<br>");
        } 
        
        $result = $query->get_result();

        $results = [];

        while($row = $result->fetch_assoc()) {
            $results []= $row["price"]; 
        }

        return $results;
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

            .search {
                margin-left: 120px;
                height: 20px;
            }
        </style>
    </head>
    <body style = "background-color: beige;">
        <div class = "header">
            <h1 style = "margin-bottom: -30px; padding-bottom: 40px; padding-top: 15px;">Caiber</h1>
            <a style = "color: white;" href = "index.php">Back to store</a>
            <br><br>
        </div>
<?php
    if($_SESSION["clearCart"] == true && $_SESSION["set"] == false) {
        echo "<h1 class = 'content'>Your cart is empty<h1><br><br>";
    }
    else{
        echo "<div>";
        $subtotal = 0;
        for($i = 1; $i < 11; $i++) {
            if($_SESSION["cart"][$i] > 0) {
                echo "<div class = 'content'>";
                echo "<h2>";
                $nameRes = getProductName($i);
                foreach($nameRes as $name) {
                    echo "Product Name: " . $name;
                }
                echo "<br><br>";
                $priceRes = getProductPrice($i);
                $p = 0;
                foreach($priceRes as $price) {
                    $p = $price;
                    echo "Price: " . $price;
                }
                echo "<br><br>";
                echo "Quantity: " . $_SESSION["cart"][$i];
                echo "<br><br>";
                $amount = ($_SESSION["cart"][$i] * $p);
                echo "Amount: " . $amount;
                echo "</h2>";
                echo "</div>";
                $subtotal = $subtotal + $amount;
            
            }
        } 
        echo "<div class = 'content'>";
        $tax = round(($subtotal * 0.08), 2); 
        $total = ($subtotal + $tax);
        echo "<h2>";
        echo "Subtotal: " . $subtotal;
        echo "</h2>";
        echo "<h2>";
        echo "Tax: " . $tax;
        echo "</h2>";
        echo "<h2>";
        echo "Total: " . $total;
        echo "</h2>";
        echo "</div>";
        echo "</div>";
        
    }
    
?>
        <form  action = "<?= htmlspecialchars($_SERVER["PHP_SELF"])."?shouldClear=".$_GET["shouldClear"]?>"method = "POST">
            <input type = "submit" name = "clear" value = "Clear cart">
<?php 
    if($_SESSION["clearCart"] == false){
?> 
            <input type = "submit" name = "buy" value = "Buy items">
<?php
        if(isset($_POST["buy"]) && $_SESSION["logged_in"]) { 
?>
            <script>
                let con = confirm('Are you sure you want to buy?');
                if(con) {
                    alert('Thank you for buying!');
                    location.replace("https://www.cs.csub.edu/~emorris/3680/project3/index.php");
                }
                else {
                    alert('You declined to buy at this time.');
                }
            </script>
<?php

        }
        else if(isset($_POST["buy"]) && !$_SESSION["logged_in"]) {
            header("Location: login.php");
        }
    }
?>
        </form>
<?php 
    if(isset($_POST["clear"])) {
?>
        <script>
            let con = confirm('Are you sure you want to clear cart?');
            if(con) {
                alert('You cleared your cart!');
            
            }
            else {
                alert('You declined to clear your cart.');
            }
            location.replace("https://www.cs.csub.edu/~emorris/3680/project3/cart.php?shouldClear="+con);
            
        </script>
<?php
        if($_GET["shouldClear"] == true) {
            echo "<script>console.log('hello')</script>";
            $_SESSION["clearCart"] = true;
            $_SESSION["set"] = false;
            for($i = 1; $i < 11; $i++) {
                unset($_SESSION["cart"][$i]);
            }
            header("Location: cart.php");
        }
    }
?>
    </body>
</html>