<?php

set_include_path(get_include_path() . ":/home/fac/nick/public_html/3680/include/");
include_once("variables.php");


$food = array("cheeseburger", "hotdog", "pasta", "cake", "pizza");
shuffle($food);

echo "<title> CMPS 3680 Lab 2 </title>";
echo "<h1 style = 'font-family: sans-serif'> Once upon a time... </h1>";
echo "<p style = 'font-size: 20px; font-family: sans-serif;'> A " . "<span style = 'text-decoration: underline'>" . 
$animals[0] . "</span>" . " wanted to go on a far away journey to the " . "<span style = 'text-decoration: underline'>" . 
$places[0] . "</span>" . " to try their world famous " . "<span style = 'text-decoration: underline'>" . $food[0] . "</span>" . 
".<br> After they " . "<span style = 'text-decoration: underline'>" . $adverbs[0] . "</span>" . " get there, they
meet a " . "<span style = 'text-decoration: underline'>" . $adjectives[0] . " " . $animals[1] . "</span>" . 
" who tells them that there is also a " . "<span style = 'text-decoration: underline'>" . $food[1] . "</span>" . 
" that tastes good and " . "<span style = 'text-decoration: underline'>" . $flavors[0] . "</span>" . " that
they should also try.<br> Once they find a restaurant to eat the food they want, they order their food from a " . 
"<span style = 'text-decoration: underline'>" . $adjectives[1] . "</span>" . " server and when they get the
food and finally taste it, they are surprised <br> to find that it tastes " . "<span style = 'text-decoration: underline'>" . 
$flavors[1] . "</span>" . " but enjoy the food nonetheless. After they finish their food and exploring the location 
a bit, they " . "<span style = 'text-decoration: underline'>" . $adverbs[1] . "</span>" . " make their way back to 
their home in the " . "<span style = 'text-decoration: underline'>" . $places[1] . "</span>. </p>";

?>

