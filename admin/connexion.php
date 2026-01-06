<?php
// admin/connexion.php recommandé (PDO)
// Chargement des variables d'environnement si non déjà chargées
if (!getenv('DB_HOST') && file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || !strpos($line, '=')) continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . "=" . trim($value));
    }
}

$DB_HOST = getenv('DB_HOST') ?: 'gkg8okscco40wc4c44w8cc4g';
$DB_NAME = getenv('DB_NAME') ?: 'bibliotheques_db';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '0qsbHlBRl49m5EfI7TndcForVm5j3t8fubWAqoqCIzYlSm75GWmZGjFmbaEO0BQ0';

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