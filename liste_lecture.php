<?php
// liste_lecture.php
// Accepts JSON POST { action: 'add'|'remove', bookId: INT, lecteurId: INT (optional) }
header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();
require_once 'admin/connexion.php';

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!$data) $data = $_POST;

$action = $data['action'] ?? '';
$bookId = isset($data['bookId']) ? intval($data['bookId']) : 0;
$lecteurId = isset($data['lecteurId']) ? intval($data['lecteurId']) : null;

if (!$bookId || !in_array($action, ['add', 'remove'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit;
}

// create table if not exists, matching exercise structure
try {
    $createSql = "CREATE TABLE IF NOT EXISTS liste_lecture (
        id_livre INT DEFAULT NULL,
        id_lecteur INT DEFAULT NULL,
        date_emprunt DATE DEFAULT NULL,
        date_retour DATE DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $pdo->exec($createSql);
} catch (PDOException $e) {
    error_log("Table creation failed: " . $e->getMessage());
}

// if no lecteur provided, try to map session to a lecteur id, else leave NULL
if (!$lecteurId) {
    $lecteurId = isset($_SESSION['lecteur_id']) ? intval($_SESSION['lecteur_id']) : null;
}

if ($action === 'add') {
    $date_emprunt = date('Y-m-d');
    try {
        $stmt = $pdo->prepare("INSERT INTO liste_lecture (id_livre, id_lecteur, date_emprunt) VALUES (?, ?, ?)");
        if ($stmt->execute([$bookId, $lecteurId, $date_emprunt])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Insert failed']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

if ($action === 'remove') {
    try {
        if ($lecteurId === null) {
            $stmt = $pdo->prepare("DELETE FROM liste_lecture WHERE id_livre = ? AND id_lecteur IS NULL");
            $ok = $stmt->execute([$bookId]);
        } else {
            $stmt = $pdo->prepare("DELETE FROM liste_lecture WHERE id_livre = ? AND id_lecteur = ?");
            $ok = $stmt->execute([$bookId, $lecteurId]);
        }
        if ($ok) echo json_encode(['success' => true]);
        else echo json_encode(['success' => false, 'message' => 'Delete failed']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Unknown action']);
