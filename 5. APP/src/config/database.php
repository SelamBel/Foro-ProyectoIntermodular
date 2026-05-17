<?php

function getDB(): PDO {
    $host = $_ENV['DB_HOST'] ?? 'mysql';
    $port = $_ENV['DB_PORT'] ?? '3306';
    $name = $_ENV['DB_NAME'] ?? 'AntNetDDBB';
    $user = $_ENV['DB_USER'] ?? 'user';
    $pass = $_ENV['DB_PASS'] ?? 'pass';

    $dsn = "mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
        $pdo->exec("SET time_zone = '+02:00'");
        return $pdo;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        die('Error de conexión a la base de datos.');
    }
}