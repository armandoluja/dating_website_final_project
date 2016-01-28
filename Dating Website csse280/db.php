<?php
// modify so that the website works for you on your local host
$username = "";
$password = "";
$db_name = "";
$db = new PDO("mysql:host=localhost;dbname = $db_name, $username, $password");
?>