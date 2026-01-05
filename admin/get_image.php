<?php
    include "connexion.php";

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = intval($_GET['id']);
        
        $query = "SELECT image_data, image_type FROM livres WHERE id = $id";
        $result = $con->query($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            if (!empty($row['image_data'])) {
                // Envoyer les bons en-têtes HTTP
                header('Content-Type: ' . $row['image_type']);
                header('Content-Length: ' . strlen($row['image_data']));
                
                // Afficher l'image
                echo $row['image_data'];
                exit;
            }
        }
    }
    
    // Si pas d'image, afficher une image par défaut
    header('Content-Type: image/svg+xml');
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="250" height="350"><rect fill="#ccc" width="250" height="350"/><text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="#999">Pas d\'image</text></svg>';
?>