<?php
    include "connexion.php";

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = intval($_GET['id']);
        
        $query = "SELECT pdf_data, pdf_type FROM livres WHERE id = $id";
        $result = $con->query($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            if (!empty($row['pdf_data'])) {
                // Envoyer les bons en-têtes HTTP
                header('Content-Type: ' . $row['pdf_type']);
                header('Content-Disposition: inline; filename="livre_' . $id . '.pdf"');
                header('Content-Length: ' . strlen($row['pdf_data']));
                
                // Afficher le PDF
                echo $row['pdf_data'];
                exit;
            }
        }
    }
    
    // Si pas de PDF, afficher une page d'erreur simple
    header('HTTP/1.0 404 Not Found');
    echo '<h1>PDF non trouvé</h1>';
?>
