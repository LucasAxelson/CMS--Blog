<?php 

$host = "localhost";
$user = "root";
$pass = "";
$port = 3306;
$db = "cms";

try {
  $conn = new PDO("mysql:host=" . $host . ";port=" . $port . ";dbname=" . $db, $user, $pass);
} catch (PDOException $e) {
  echo "". $e->getMessage() ."";
}
?>