<?php
    include "connexion.php";

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = intval($_GET['id']);
        
        try {
            $stmt = $pdo->prepare("SELECT image_data, image_type FROM livres WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row && !empty($row['image_data'])) {
                // Envoyer les bons en-têtes HTTP
                header('Content-Type: ' . $row['image_type']);
                header('Content-Length: ' . strlen($row['image_data']));
                
                // Afficher l'image
                echo $row['image_data'];
                exit;
            }
        } catch (PDOException $e) {
            error_log("Image fetch failed: " . $e->getMessage());
        }
    }
    
    // Si pas d'image, afficher une image par défaut
    header('Content-Type: image/svg+xml');
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="250" height="350"><rect fill="#ccc" width="250" height="350"/><text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="#999">Pas d\'image</text></svg>';
?>