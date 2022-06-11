<?php

$Servername = "localhost";
$username = "root";
$password = "";
$dbname = "transparentes_bewertungssystem";


$conn = new mysqli($Servername, $username, $password, $dbname);
if ($conn->connect_error){
    die("connect error!");
}

?>