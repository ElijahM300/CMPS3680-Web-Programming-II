<!DOCTYPE html>
<html lang="en">
<?php
    $items = array(
        array("itemName" => "Tablet", "price" => 199.99, "categories" => ["electronics", "portable"]),
        array("itemName" => "Scarf", "price" => 11.99, "categories" => ["apparel", "neck"]),
        array("itemName" => "Jacket", "price" => 24.99, "categories" => ["apparel", "body"]),
        array("itemName" => "Watch", "price" => 39.99, "categories" => ["portable", "electronics"]),
        array("itemName" => "Shirt", "price" => 14.99, "categories" => ["apparel", "body"])
    );
    
    $itemCategories = array();
    foreach($items as $item) {
        foreach($item["categories"] as $category){
            $itemCategories []= $category;
        }  
    }
    $itemCategories = array_unique($itemCategories);
?>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>CMPS 3680 Lab 3</title>
    </head>
    <body>
        <h1>Elijah's Shop</h1>
        <?php
            echo "<a style = 'padding-right: 10px' href =" . htmlspecialchars($_SERVER['PHP_SELF']) . 
            "?cat=all>all</a>" . " ";
            foreach($itemCategories as $unique){
                echo "<a style = 'padding-right: 10px' href =" . htmlspecialchars($_SERVER['PHP_SELF']) . 
                "?cat=" . $unique . ">" . $unique . "</a>" . " ";
            }
            
        ?>
        <form style = "padding-bottom: 15px; padding-top: 10px" 
        action = "<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method = "GET">
            <input type = "text" name = "ItemSearch" placeholders = "Item search" required>
            <input type = "submit" name = "SendIt">
        </form>
<?php
    if(isset($_GET["SendIt"])) {
        $name = htmlspecialchars($_GET["ItemSearch"]);
        foreach($items as $counter => $item) {
            foreach($item as $key => $value) {
                if($key == "itemName") {
                    similar_text($value, $name, $perc);
                }
                if($perc > 70 && ($key == "itemName" || $key == "price")) {
                    echo $value . " ";
                }
                
            }
        }
    }

    if(isset($_GET["cat"])) {
        $cat = htmlspecialchars($_GET["cat"]);
        if($cat == "all") {
            foreach($items as $count => $item) {
                foreach($item as $key => $value) {
                    if(!is_array($value)) {
                        echo $value . " ";
                    }
                }
                echo "<br><br>";
            }
        }
        else {
            foreach($items as $item) {
                foreach($item["categories"] as $category) {
                    if($cat == $category) {
                        echo $item["itemName"] . " " . $item["price"] . "<br>";
                        echo "<br>";
                    }
                }
            }
        }
    }
?>
    </body>
</html>