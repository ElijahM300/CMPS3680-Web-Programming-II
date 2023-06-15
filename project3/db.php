<?php
    function get_connection() {
        static $connection;
        
        if(!isset($connection)) {
            $connection = mysqli_connect('localhost', 'emorris', 'mxan0Nowr', 'emorris')
                or die(mysqli_connect_error());
        }

        if($connection === false) {
            echo "Unable to connect to database: " . mysqli_connect_error();
        }

        return $connection;
    }
?>