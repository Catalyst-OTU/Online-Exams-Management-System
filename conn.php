<?php 

$host = "localhost";
$user = "root";
$pass = "";
$db   = "online_exam_db";
$conn = null;

try {
  $conn = new PDO("mysql:host={$host};dbname={$db};charset=utf8mb4",$user,$pass,[
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
  ]);
} catch (Exception $e) {
  // You may log the error in production
  // die("Database connection failed");
}

?>
