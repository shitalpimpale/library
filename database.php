<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "library";

try{
// Create connection
//$dbConnection = new mysqli($servername, $username, $password, $database);
$dbConnection = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
}
catch(PDOException $e){
    echo $sql . "<br>" . $e->getMessage();
}