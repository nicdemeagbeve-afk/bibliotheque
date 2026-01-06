
<?php
session_start();
include "connexion.php";

// Vérifier si admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = intval($_GET['id']);

        try {
            $stmt = $pdo->prepare("DELETE FROM livres WHERE id = ?");
            if ($stmt->execute([$id])) {
                echo '<div style="color: green; padding: 20px; text-align: center; background: #d4edda; border: 1px solid #28a745; border-radius: 8px; margin: 20px;">✅ Livre supprimé avec succès!</div>';
                header("Refresh: 2; url=../index.php");
                exit;
            } else {
                echo '<div style="color: red; padding: 20px; text-align: center;">❌ Erreur lors de la suppression.</div>';
                exit;
            }
        } catch (PDOException $e) {
            echo '<div style="color: red; padding: 20px; text-align: center;">❌ Erreur base de données : ' . htmlspecialchars($e->getMessage()) . '</div>';
            exit;
        }
    } else {
        echo '<div style="color: red; padding: 20px; text-align: center;">❌ ID de livre manquant!</div>';
        exit;
    }
?>