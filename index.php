<?php

    // Connect to DB
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db = 'mysqlDataManager';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$countryName = 'santo domingo';
$stmt = $pdo->prepare('SELECT * FROM country WHERE countryName = ?');
$stmt->execute([$countryName]);
$user = $stmt->fetch();
print_r($user);
// // or
// $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email AND status=:status');
// $stmt->execute(['email' => $email, 'status' => $status]);
// $user = $stmt->fetch();