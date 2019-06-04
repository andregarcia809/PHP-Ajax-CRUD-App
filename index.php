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
$rowID = 14;

$sql = 'SELECT countryName, shortDesc, longDesc FROM country WHERE id = :rowID';
$stmt = $pdo->prepare($sql);
$stmt->execute(['rowID' => $rowID]);
$data = $stmt->fetch(PDO::FETCH_OBJ);

print_r($data);
echo '<br>';
echo '<br>';
echo json_encode($data);