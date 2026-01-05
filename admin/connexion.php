<?php
// admin/connexion.php recommandé (PDO)
$DB_HOST = getenv('DB_HOST') ?: 'mysql-database-o84o4skkk8000c0kkcsos4oc';
$DB_NAME = getenv('DB_NAME') ?: 'default';
$DB_USER = getenv('DB_USER') ?: 'mysql';
$DB_PASS = getenv('DB_PASS') ?: 'rFzwnPxDKQa9dbTjuhbGt94mqJwOaJ5erpUjU8pjqqoil58bjtdPb6Lq1XMYPino';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
    error_log('DB Connexion échouée: '.$e->getMessage());
    exit('Erreur de connexion à la base de données.');
}