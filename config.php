<?php
// ========================================
// Configuration Centralisée du Projet
// ========================================

// Chargement des variables d'environnement depuis le fichier .env si présent
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || !strpos($line, '=')) continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . "=" . trim($value));
        $_ENV[trim($name)] = trim($value);
    }
}

// Base de données (ENV PRIORITAIRE)
define('DB_HOST', getenv('DB_HOST') ?: 'gkg8okscco40wc4c44w8cc4g');
define('DB_PORT', getenv('DB_PORT') ?: 3306);
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '0qsbHlBRl49m5EfI7TndcForVm5j3t8fubWAqoqCIzYlSm75GWmZGjFmbaEO0BQ0');
define('DB_NAME', getenv('DB_NAME') ?: 'bibliotheques_db');

// Site
define('SITE_NAME', 'Bibliothèques De la Reussite');
define('SITE_URL', 'https://bibliotheque.miabesite.site');
define('CURRENT_YEAR', 2026);

// Upload
define('MAX_IMAGE_SIZE', 20 * 1024 * 1024); // 20 MB
define('MAX_PDF_SIZE', 100 * 1024 * 1024); // 100 MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif']);
define('ALLOWED_PDF_TYPE', 'application/pdf');

// Admin
define('ADMIN_MODE_SESSION_KEY', 'is_admin');

// Connexion à la base de données
function getConnection() {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        error_log("DB Connexion échouée: " . $e->getMessage());
        die("Erreur de connexion à la base de données.");
    }
}

// Session sécurisée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
