<?php
$dsn = "mysql:host=localhost;dbname=esnafsho_ecommerce;charset=utf8";
$user = "esnafsho_ecommerce";
$pass = "sEQwU8uZyR2xLMNpMqDB";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Veritabanına bağlanırken hata oluştu: " . $e->getMessage());
}