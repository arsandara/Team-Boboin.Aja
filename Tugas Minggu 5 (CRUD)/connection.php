<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'db_boboinaja';

$conn = new mysqli($host, $user, $pass, $dbname3);

if ($conn->connect_error){
    die("Connection Failed: ". $conn->connect_error);
}

echo "Connection Success";
?>