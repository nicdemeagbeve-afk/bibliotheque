<?php
    include "connexion.php";

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = intval($_GET['id']);
        
        try {
            $stmt = $pdo->prepare("SELECT pdf_data, pdf_type FROM livres WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row && !empty($row['pdf_data'])) {
                // Envoyer les bons en-têtes HTTP
                header('Content-Type: ' . $row['pdf_type']);
                header('Content-Disposition: inline; filename="livre_' . $id . '.pdf"');
                header('Content-Length: ' . strlen($row['pdf_data']));
                
                // Afficher le PDF
                echo $row['pdf_data'];
                exit;
            }
        } catch (PDOException $e) {
            error_log("PDF fetch failed: " . $e->getMessage());
        }
    }
    
    // Si pas de PDF, afficher une page d'erreur simple
    header('HTTP/1.0 404 Not Found');
    echo '<h1>PDF non trouvé</h1>';
?>
