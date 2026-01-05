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
$createSql = "CREATE TABLE IF NOT EXISTS liste_lecture (
    id_livre INT DEFAULT NULL,
    id_lecteur INT DEFAULT NULL,
    date_emprunt DATE DEFAULT NULL,
    date_retour DATE DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$con->query($createSql);

// if no lecteur provided, try to map session to a lecteur id, else leave NULL
if (!$lecteurId) {
    $lecteurId = isset($_SESSION['lecteur_id']) ? intval($_SESSION['lecteur_id']) : null;
}

if ($action === 'add') {
    $date_emprunt = date('Y-m-d');
    // insert using columns matching exercise schema
    $stmt = $con->prepare("INSERT INTO liste_lecture (id_livre, id_lecteur, date_emprunt) VALUES (?, ?, ?)");
    $stmt->bind_param('iis', $bookId, $lecteurId, $date_emprunt);
    $ok = $stmt->execute();
    if ($ok) echo json_encode(['success' => true]);
    else echo json_encode(['success' => false, 'message' => $con->error]);
    $stmt->close();
    exit;
}

if ($action === 'remove') {
    if ($lecteurId === null) {
        // remove entries without a specific lecteur
        $stmt = $con->prepare("DELETE FROM liste_lecture WHERE id_livre = ? AND id_lecteur IS NULL");
        $stmt->bind_param('i', $bookId);
    } else {
        $stmt = $con->prepare("DELETE FROM liste_lecture WHERE id_livre = ? AND id_lecteur = ?");
        $stmt->bind_param('ii', $bookId, $lecteurId);
    }
    $ok = $stmt->execute();
    if ($ok) echo json_encode(['success' => true]);
    else echo json_encode(['success' => false, 'message' => $con->error]);
    $stmt->close();
    exit;
}

echo json_encode(['success' => false, 'message' => 'Unknown action']);
