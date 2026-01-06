<?php
session_start();
include __DIR__ . '/../connexion.php';

// Vérifier si admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: /login.php");
    exit;
}

$livre = null;
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);
    try {
        $stmt = $pdo->prepare("SELECT * FROM livres WHERE id = ?");
        $stmt->execute([$id]);
        $livre = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$livre) { header("Location: ../index.php"); exit; }
    } catch (PDOException $e) {
        error_log("Fetch failed: " . $e->getMessage());
        header("Location: ../index.php"); exit;
    }
} else {
    header("Location: ../index.php"); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $auteur = trim($_POST['auteur'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $maison_edition = trim($_POST['maison_edition'] ?? '');
    $nombre_exemplaire = intval($_POST['nombre_exemplaire'] ?? 0);

    // handle optional image
    $image_data = null; $image_type = null;
    $maxImage = 2 * 1024 * 1024; // 2 MB
    if (!empty($_FILES['image_upload']['name'])) {
        if ($_FILES['image_upload']['error'] === UPLOAD_ERR_INI_SIZE) {
            echo "❌ L'image dépasse la limite serveur (upload_max_filesize). Réduisez la taille ou augmentez la configuration PHP.";
            exit;
        }
        if ($_FILES['image_upload']['error'] !== UPLOAD_ERR_OK) {
            echo "❌ Erreur lors de l'upload de l'image (code: " . intval($_FILES['image_upload']['error']) . ").";
            exit;
        }
        $imgSize = $_FILES['image_upload']['size'];
        if ($imgSize > $maxImage) {
            echo "❌ Image trop grosse (max 2 MB).";
            exit;
        }
        $image_type = $_FILES['image_upload']['type'];
        $image_data = file_get_contents($_FILES['image_upload']['tmp_name']);
    }

    // handle optional pdf
    $pdf_data = null; $pdf_type = null;
    $maxPdf = 10 * 1024 * 1024; // 10 MB
    if (!empty($_FILES['pdf_upload']['name'])) {
        if ($_FILES['pdf_upload']['error'] === UPLOAD_ERR_INI_SIZE) {
            echo "❌ Le PDF dépasse la limite serveur (upload_max_filesize). Réduisez la taille ou augmentez la configuration PHP.";
            exit;
        }
        if ($_FILES['pdf_upload']['error'] !== UPLOAD_ERR_OK) {
            echo "❌ Erreur upload PDF (code: " . intval($_FILES['pdf_upload']['error']) . ").";
            exit;
        }
        $pdfSize = $_FILES['pdf_upload']['size'];
        if ($pdfSize > $maxPdf) {
            echo "❌ PDF trop gros (max 10 MB).";
            exit;
        }
        $pdf_type = $_FILES['pdf_upload']['type'];
        $pdf_data = file_get_contents($_FILES['pdf_upload']['tmp_name']);
    }

    // Build update query depending on which blob fields are present
    try {
        if ($image_data !== null && $pdf_data !== null) {
            $sql = "UPDATE livres SET titre=?, auteur=?, description=?, maison_edition=?, nombre_exemplaire=?, image_data=?, image_type=?, pdf_data=?, pdf_type=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$titre, $auteur, $description, $maison_edition, $nombre_exemplaire, $image_data, $image_type, $pdf_data, $pdf_type, $id]);
        } elseif ($image_data !== null) {
            $sql = "UPDATE livres SET titre=?, auteur=?, description=?, maison_edition=?, nombre_exemplaire=?, image_data=?, image_type=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$titre, $auteur, $description, $maison_edition, $nombre_exemplaire, $image_data, $image_type, $id]);
        } elseif ($pdf_data !== null) {
            $sql = "UPDATE livres SET titre=?, auteur=?, description=?, maison_edition=?, nombre_exemplaire=?, pdf_data=?, pdf_type=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$titre, $auteur, $description, $maison_edition, $nombre_exemplaire, $pdf_data, $pdf_type, $id]);
        } else {
            $sql = "UPDATE livres SET titre=?, auteur=?, description=?, maison_edition=?, nombre_exemplaire=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$titre, $auteur, $description, $maison_edition, $nombre_exemplaire, $id]);
        }

        header("Location: ../detail.php?id=$id");
        exit;
    } catch (PDOException $e) {
        echo "Erreur update: " . htmlspecialchars($e->getMessage());
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="stylesheet" href="css/style.css">
   <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <title>Modifier le livre</title>
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
            <?php if ($livre): ?>
            <form action="" method="post" enctype="multipart/form-data">
                <div>
                    <input type="text" name="titre" placeholder="Titre du livre" value="<?php echo htmlspecialchars($livre['titre']); ?>" required>
                </div>
                <div>
                    <input type="text" name="auteur" placeholder="Auteur du livre" value="<?php echo htmlspecialchars($livre['auteur']); ?>" required>
                </div>
                <div>
                    <input type="text" name="maison_edition" placeholder="Maison d'édition du livre" value="<?php echo htmlspecialchars($livre['maison_edition'] ?? ''); ?>">
                </div>
                <div>
                    <input type="number" name="nombre_exemplaire" placeholder="Nombre d'exemplaires" value="<?php echo intval($livre['nombre_exemplaire'] ?? 1); ?>" min="1">
                </div>
                <div class="">
                    <textarea name="description" rows="10" placeholder="Petite description du livre" required><?php echo htmlspecialchars($livre['description']); ?></textarea>
                </div>
                <div>
                    <label for="image_upload">Image du livre:</label>
                    <?php if (!empty($livre['image_data'])): ?>
                        <img src="admin/get_image.php?id=<?php echo $livre['id']; ?>" alt="Image actuelle" width="100"><br>
                        <p>Image actuelle chargée depuis la base de données</p>
                    <?php endif; ?>
                    <input type="file" name="image_upload" id="image_upload" accept="image/*">
                    <small>Laissez vide pour conserver l'image actuelle.</small>
                </div>
                <div>
                    <label for="pdf_upload">PDF du livre (optionnel):</label>
                    <?php if (!empty($livre['pdf_data'])): ?>
                        <p>✅ Un PDF est actuellement attaché au livre</p>
                    <?php endif; ?>
                    <input type="file" name="pdf_upload" id="pdf_upload" accept=".pdf">
                    <small>Laissez vide pour conserver le PDF actuel.</small>
                </div>
                <button type="submit">Modifier le livre</button>
            </form>
            <?php else: ?>
                <p class="no-results">Livre non trouvé pour modification.</p>
            <?php endif; ?>
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