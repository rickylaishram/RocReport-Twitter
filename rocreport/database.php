<?php
$db = new mysqli($_db["host"], $_db["user"], $_db["pass"], $_db["database"]);
if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}
//print_r($db);