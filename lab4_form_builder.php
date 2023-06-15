<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>PHP Form Builder demo</title>
    </head>
    <body>
        <h1>PHP Form Builder demo</h1>
        <p>Source: <a href="https://github.com/joshcanhelp/php-form-builder">PHP Form Builder repo</a></p>

        <?php
set_include_path(get_include_path() . ":/home/fac/nick/public_html/3680/include/");
include_once("FormBuilder.php");

$new_form = new PhpFormBuilder();

// Use GET for the form method
$new_form->set_att('method', 'get');

// Text input
$new_form->add_input('Name:', array(), 'name');

$new_form->add_input('Password:', array(
    "type"      => "password"
), 'pwd');

// True/false radio input
$new_form->add_input('I am not a robot', array(
    "type"      => "radio",
    "options"   => array(
        "true" => "True",
        "false" => "False"
    )

), 'am_robot');

// checkbox input
$new_form->add_input('I am kind of a robot', array(
    "type"      => "checkbox",
    "options"   => array(
        "true" => "True",
        "false" => "False"
    )

), 'am_robot_checkbox');

$new_form->add_input('Video game platform', array(
    "type"      => "radio",
    "options"   => array(
        "nintendo" => "Nintendo",
        "playstation" => "Playstation",
        "xbox" => "Xbox",
        "pc" => "PC"
    )

), 'vgplatform');

$new_form->add_input('Pick your favorite color', array(
    "type"      => "color"
), 'favcolor');

$new_form->build_form();

if (isset($_REQUEST["am_robot"])) {
    echo " You said " . $_REQUEST["am_robot"][0] . " for 'I am not a robot'<br>";
}
if (isset($_REQUEST["vgplatform"])) {
    echo " You said " . $_REQUEST["vgplatform"][0] . " was your video game platform<br>";
}
if (isset($_REQUEST["favcolor"])) {
    echo " You said " . $_REQUEST["favcolor"] . " was your favorite color";
}
        ?>
    </body>
</html>