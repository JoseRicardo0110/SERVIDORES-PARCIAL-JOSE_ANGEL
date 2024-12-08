<?php
$host = "mysql";
$user = "user";
$pass = "password";
$database = "testdb";

$conn = new mysqli($host, $user, $pass, $database);

if ($conn->connect_errno) {
    die("Error de conexión: " . $conn->connect_error);
}

echo "Conexión establecida con éxito a la base de datos MySQL.";
?>
