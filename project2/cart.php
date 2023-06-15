<?php
    ini_set('session.gc_maxlifetime', 3600);
    session_set_cookie_params(3600);
    session_start();

    $dbFile = "store.db";
    $con = new SQLite3($dbFile);
    $con->exec('PRAGMA foreign_keys = ON;');

    function getProductName($productid) {
        global $con;
        
        $statement = "";
        $statement = $con->prepare("select productname from product where productid like :productID");
        $statement->bindValue(":productID", $productid, SQLITE3_INTEGER);
        
        $result = $statement->execute();
        $result->finalize();

        $results = [];

        while($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $results []= $row["productname"]; 
        }

        return $results;
    }

    function getProductPrice($productid) {
        global $con;
        
        $statement = "";
        $statement = $con->prepare("select price from product where productid like :productID");
        $statement->bindValue(":productID", $productid, SQLITE3_INTEGER);
        
        $result = $statement->execute();
        $result->finalize();

        $results = [];

        while($row = $result->fetchArray(SQLITE3_ASSOC)) {
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
        <title>CMPS 3680 Project 2</title>
        <style>
            table, th, td {
                border: 1px solid #aaa;
                border-collapse: collapse;
                padding: 8px;
            }

            tr:nth-child(even){background-color: #f2f2f2;}
        </style>
    </head>
    <body style = "background-color: beige;">
        <a href = "index.php">Back to store</a>
        <h1 style = "font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 
        'Lucida Grande', 'Lucida Sans', Arial, sans-serif; padding-left: 650px;">Elijah's Shop</h1>
        <br><br>
<?php
    if($_SESSION["clearCart"] == true && $_SESSION["set"] == false) {
        echo "Your cart is empty<br><br>";
    }
    else{
        echo "<table border = '1'>";
        echo "<tr>";
        echo "<th>Product Name</th>";
        echo "<th>Price</th>";
        echo "<th>Quantity</th>";
        echo "<th>Amount</th>";
        echo "</tr>";
        $subtotal = 0;
        for($i = 1; $i < 6; $i++) {
            echo "<tr>";
            if($_SESSION["cart"][$i] > 0) {
                echo "<td>";
                $nameRes = getProductName($i);
                foreach($nameRes as $name) {
                    echo $name;
                }
                echo "</td>";
                echo "<td>";
                $priceRes = getProductPrice($i);
                $p = 0;
                foreach($priceRes as $price) {
                    $p = $price;
                    echo $price;
                }
                echo "</td>";
                echo "<td>";
                echo $_SESSION["cart"][$i];
                echo "</td>";
                echo "<td>";
                $amount = ($_SESSION["cart"][$i] * $p);
                echo $amount;
                echo "</td>";
                $subtotal = $subtotal + $amount;
            
            }
        }   
        $tax = round(($subtotal * 0.08), 2); 
        $total = ($subtotal + $tax);
        echo "</tr>";
        echo "<tr>";
        echo "<td><b> Subtotal </b></td>"; 
        echo "<td>====</td>";
        echo "<td>======></td>";
        echo "<td>".$subtotal."</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td><b> Tax </b></td>"; 
        echo "<td>====</td>";
        echo "<td>======></td>";
        echo "<td>".$tax."</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td><b> Total </b></td>"; 
        echo "<td>====</td>";
        echo "<td>======></td>";
        echo "<td>".$total."</td>";
        echo "</tr>";
        echo "</table><br>";
    }
    
?>
        <form action = "<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method = "POST">
            <input type = "submit" name = "clear" value = "Clear cart">
        </form>
<?php 
    if(isset($_POST["clear"])) {
        session_destroy();
        $_SESSION["clearCart"] = true;
        header("Location: cart.php");
    }
?>
    </body>
</html>