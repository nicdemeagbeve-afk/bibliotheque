<?php
session_start();
include __DIR__ . '/connexion.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Vérifier si admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titre = trim($_POST['titre'] ?? '');
    $auteur = trim($_POST['auteur'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $maison_edition = trim($_POST['maison_edition'] ?? '');
    $nombre_exemplaire = intval($_POST['nombre_exemplaire'] ?? 1);

    // Limits (conservative server-friendly defaults)
    $maxImage = 2 * 1024 * 1024; // 2 MB
    $maxPdf   = 10 * 1024 * 1024; // 10 MB

    // image (required)
    if (!isset($_FILES['image_upload'])) {
        echo "❌ Veuillez sélectionner une image.";
        exit;
    }
    if ($_FILES['image_upload']['error'] === UPLOAD_ERR_INI_SIZE) {
        echo "❌ L'image dépasse la limite serveur (upload_max_filesize). Réduisez la taille ou augmentez la configuration PHP.";
        exit;
    }
    if ($_FILES['image_upload']['error'] !== UPLOAD_ERR_OK) {
        echo "❌ Erreur lors de l'upload de l'image (code: " . intval($_FILES['image_upload']['error']) . ").";
        exit;
    }
    if ($_FILES['image_upload']['size'] > $maxImage) {
        echo "❌ Image trop grosse (max 2 MB).";
        exit;
    }
    $image_type = $_FILES['image_upload']['type'];
    $image_data = file_get_contents($_FILES['image_upload']['tmp_name']);

    // pdf (optional)
    $pdf_data = '';
    $pdf_type = '';
    if (!empty($_FILES['pdf_upload']['name'])) {
        if ($_FILES['pdf_upload']['error'] === UPLOAD_ERR_INI_SIZE) {
            echo "❌ Le PDF dépasse la limite serveur (upload_max_filesize). Réduisez la taille ou augmentez la configuration PHP.";
            exit;
        }
        if ($_FILES['pdf_upload']['error'] !== UPLOAD_ERR_OK) {
            echo "❌ Erreur upload PDF (code: " . intval($_FILES['pdf_upload']['error']) . ").";
            exit;
        }
        if ($_FILES['pdf_upload']['size'] > $maxPdf) {
            echo "❌ PDF trop gros (max 10 MB).";
            exit;
        }
        $pdf_type = $_FILES['pdf_upload']['type'];
        $pdf_data = file_get_contents($_FILES['pdf_upload']['tmp_name']);
    }

    // Prepared insert
    try {
        $sql = "INSERT INTO livres (titre, auteur, description, maison_edition, nombre_exemplaire, image_data, image_type, pdf_data, pdf_type)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            $titre, 
            $auteur, 
            $description, 
            $maison_edition, 
            $nombre_exemplaire, 
            $image_data, 
            $image_type, 
            $pdf_data, 
            $pdf_type
        ]);

        header("Location: ../index.php");
        exit;
    } catch (PDOException $e) {
        // Common cause: packet too large
        echo "Erreur base de données: " . htmlspecialchars($e->getMessage()) . "<br>";
        echo "Si le message contient 'max_allowed_packet', augmentez cette valeur dans la configuration MySQL.";
        exit;
    } catch (Exception $e) {
        echo "Erreur lors de l'insertion: " . htmlspecialchars($e->getMessage());
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Ajout de livre</title>
</head>
<body>
    <header>
        <h1>Bibliothèques De la Reussite</h1>
        <nav>
            <ul>
                <li><a href="../index.php">Accueil</a></li>
                <li><a href="../liste.php">Parcourir</a></li>
                <li><a href="../index.php#favoris">Favoris</a></li>
                <li><a href="create.php">Ajouter</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="ajout">
            <h2>Ajouter un nouveau livre</h2>
            <form action="" method="post" enctype="multipart/form-data">
                <div>
                    <label for="titre">Titre du livre:</label>
                    <input type="text" name="titre" id="titre" placeholder="Titre du livre" required>
                </div>
                <div>
                    <label for="auteur">Auteur:</label>
                    <input type="text" name="auteur" id="auteur" placeholder="Auteur du livre" required>
                </div>
                <div>
                    <label for="maison_edition">Maison d'édition:</label>
                    <input type="text" name="maison_edition" id="maison_edition" placeholder="Maison d'édition du livre">
                </div>
                <div>
                    <label for="nombre_exemplaire">Nombre d'exemplaires:</label>
                    <input type="number" name="nombre_exemplaire" id="nombre_exemplaire" placeholder="Nombre d'exemplaires" value="1" min="1">
                </div>
                <div>
                    <label for="description">Description:</label>
                    <textarea name="description" id="description" rows="10" placeholder="Petite description du livre" required></textarea>
                </div>
                <div>
                    <label for="image_upload">Image du livre:</label>
                    <input type="file" name="image_upload" id="image_upload" accept="image/*" required>
                </div>
                <div>
                    <label for="pdf_upload">PDF du livre (optionnel):</label>
                    <input type="file" name="pdf_upload" id="pdf_upload" accept=".pdf">
                </div>
                <button type="submit">Ajouter le livre</button>
            </form>
        </section>

    </main>

    <footer> 
        <nav>
            <ul>
                <li> <a href="../faq.php">FAQ</a></li>
                <li> <a href="../conditions.php">Conditions d'utilisation</a></li>
                <li><a href="../apropos.php">À propos</a></li>
            </ul>
        </nav>

        <h1>Bibliothèques De la Reussite</h1>

        <section class="sect2">
            <div><p>&copy; 2026 - Tous droits réservés</p></div>
        </section>
    </footer>
    <script src="https://kit.fontawesome.com/a2b4a29c24.js" crossorigin="anonymous"></script>
</body>
</html>