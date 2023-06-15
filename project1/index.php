<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <style>
            #q{
                font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
                font-weight: bold;
            }
        </style>
        <title>CMPS 3680 Project 1</title>
    </head>
    <body style = "background-color: beige">
        <h1 style = "font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif; 
        padding-left: 500px; text-decoration: underline">Project 1 Computer Science Quiz</h1><br>
        <form action = "<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method = "POST">
            <label for = "fullName">Name:</label><br>
            <input type = "text" id = "fullName" name = "fullName"required><br><br>

            <label for = "q1_response" class = "q1" id = "q">1. PHP is the most hated programming language.</label><br>
            <input type="radio" id = "q1_response" name="q1_response" value="true">True<br>
            <input type="radio" id = "q1_response" name="q1_response" value="false">False<br><br>

            <label for = "q2_response" class = "q2" id = "q">2. CSUB doesn't have a Master's program for Computer Science.</label><br>
            <input type="radio" id = "q2_response" name="q2_response" value="true">True<br>
            <input type="radio" id = "q2_response" name="q2_response" value="false">False<br><br>

            <label for = "q3_response" class = "q3" id = "q">3. FORTRAN is the most popular programming language.</label><br>
            <input type="radio" id = "q3_response" name="q3_response" value="true">True<br>
            <input type="radio" id = "q3_response" name="q3_response" value="false">False<br><br>

            <label for = "q4_response" class = "q4" id = "q">4. The D programming language was meant as a successor to C++.</label><br>
            <input type="radio" id = "q4_response" name="q4_response" value="true">True<br>
            <input type="radio" id = "q4_response" name="q4_response" value="false">False<br><br>

            <label for = "q5_response" class = "q5" id = "q">5. The most loved programming language is Rust.</label><br>
            <input type="radio" id = "q5_response" name="q5_response" value="true">True<br>
            <input type="radio" id = "q5_response" name="q5_response" value="false">False<br><br>

            <label for = "q6_response" class = "q6" id = "q">6. Which of these programming languages is object-oreinted?</label><br>
            <input type="radio" id = "q6_response" name="q6_response" value="js">Javascript<br>
            <input type="radio" id = "q6_response" name="q6_response" value="cpp">C++<br>
            <input type="radio" id = "q6_response" name="q6_response" value="java">Java<br>
            <input type="radio" id = "q6_response" name="q6_response" value="perl">Perl<br><br>

            <label for = "q7_response" class = "q7" id = "q">7. What programming language does Unity use for video games?</label><br>
            <input type="radio" id = "q7_response" name="q7_response" value="cs">C#<br>
            <input type="radio" id = "q7_response" name="q7_response" value="java">Java<br>
            <input type="radio" id = "q7_response" name="q7_response" value="c">C<br>
            <input type="radio" id = "q7_response" name="q7_response" value="py">Python<br><br>

            <label for = "q8_response" class = "q8" id = "q">8. What Computer Science concentration at CSUB requires the least amount of math?</label><br>
            <input type="radio" id = "q8_response" name="q8_response" value="cst">Computer Science - Traditional<br>
            <input type="radio" id = "q8_response" name="q8_response" value="is">Computer Science - Information Security<br>
            <input type="radio" id = "q8_response" name="q8_response" value="ce">Computer Engineering<br>
            <input type="radio" id = "q8_response" name="q8_response" value="cis">Computer Science - Computer Information Systems<br><br>

            <label for = "q9_response" class = "q9" id = "q">9. What programming languages are scripting languages?</label><br>
            <input type="checkbox" id = "q9_response" name="q9_response[]" value="php">PHP<br>
            <input type="checkbox" id = "q9_response" name="q9_response[]" value="rb">Ruby<br>
            <input type="checkbox" id = "q9_response" name="q9_response[]" value="js">Javascript<br>
            <input type="checkbox" id = "q9_response" name="q9_response[]" value="py">Python<br><br>

            <input type = "submit" name = "SendIt" value="Submit Quiz"><br><br>
        </form>
<?php
    $answers = array("q1" => "false", "q2" => "false", "q3" => "false", "q4" => "true", "q5" => "true", 
    "q6" => "java", "q7" => "cs", "q8" => "cis", "q9" => ["php", "js", "py"]);
    $mult_answers = array();
    $wrong = array();
    $total = 0;
    $perc = 0;
    $counter = 1;
    $i = 0;

    foreach($_POST["q9_response"] as $check) {
        if($check != NULL) {
            $mult_answers[$i] = $check;
            $i++;
        }   
    }

    if(isset($_POST["SendIt"])) {
        $name = htmlspecialchars($_POST["fullName"]);
        foreach($answers as $quest => $answer) {
            $user_res = htmlspecialchars($_POST["q".$counter."_response"]);
            if($counter == 9) {
                $i = 0;
                $count = 1;
                foreach($answer as $mult) {
                    if($mult_answers[$i] == $mult) {
                        $count += 1;
                        if($count == 3) {
                            $total += 1;
                        }
                    }
                    else if($count <= 2){
                        echo "<style> .q" . $counter . "{ background-color: red; } </style>";
                    }
                    $i++;
                }
            }
            else if(isset($_POST["q".$counter."_response"]) && $user_res == $answer ) {
                $total += 1;
            }
            else {
               echo "<style> .q" . $counter . "{ background-color: red; } </style>";
            }

            $counter += 1;
        }
        $perc = ($total / 9) * 100;
        echo "Hello " . $name . ", you got: " . $total . "/9 or " . round($perc, 2) . "%<br>";
    }

    if($total == 9) {
        echo "Congratulations, you got 100%! Here's a treat :)<br>";
        echo "<a href = 'https://www.youtube.com/watch?v=bzJDimvPW1Y'>Funny Dark Souls video</a><br>";
    }
    

?>
    </body>
</html>