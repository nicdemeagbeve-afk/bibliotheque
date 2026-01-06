<?php
session_start();
include "connexion.php";

$livres = [];
$query = "SELECT * FROM livres";
try {
    $stmt = $pdo->query($query);
    $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="stylesheet" href="css/style.css">
   <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <title>Liste Complète des Livres</title>
</head>
<body>
    <header>
        <h1>Bibliothèques De la Reussite</h1>
        <nav>
            <ul>
                <li><a href="index.php">Acceuil</a></li>
                <li><a href="liste.php">Parcourir</a></li>
                <li><a href="index.php#favoris">Favoris</a></li>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <li><a href="admin/create.php">Ajouter</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="profile.php"><?= htmlspecialchars(substr($_SESSION['user_name'], 0, 15)) ?></a></li>
                    <li><a href="logout.php">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="login.php">Connexion</a></li>
                    <li><a href="register.php">S'inscrire</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <section class="search">
            <form action="result.php" method="get">
                <input type="text" name="search" placeholder="Rechercher par titre ou auteur" required>
                <select name="search_type">
                    <option value="titre">Titre</option>
                    <option value="auteur">Auteur</option>
                    <option value="categorie">Catégorie</option>
                </select>
                <button type="submit">Rechercher</button>
            </form>
        </section>

        <section class="list-section">
            <h2>Liste Complète des Livres</h2>
            
            <?php if (count($livres) > 0): ?>
                <table class="books-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Catégorie</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($livres as $livre): ?>
                            <tr>
                                <td class="table-image">
                                    <img src="admin/get_image.php?id=<?php echo $livre['id']; ?>" alt="<?php echo htmlspecialchars($livre['titre']); ?>">
                                </td>
                                <td><strong><?php echo htmlspecialchars($livre['titre']); ?></strong></td>
                                <td><?php echo htmlspecialchars($livre['auteur']); ?></td>
                                <td><span class="category-tag"><?php echo htmlspecialchars($livre['maison_edition'] ?? 'N/A'); ?></span></td>
                                <td class="description-cell">
                                    <?php 
                                    $desc = htmlspecialchars($livre['description']);
                                    echo (strlen($desc) > 100) ? substr($desc, 0, 100) . '...' : $desc; 
                                    ?>
                                </td>
                                <td class="actions-cell">
                                    <a href="detail.php?id=<?php echo $livre['id']; ?>" class="btn-details">Voir</a>
                                    <button class="favorite-btn-small" onclick="toggleFavorite(this, <?php echo $livre['id']; ?>, '<?php echo htmlspecialchars($livre['titre']); ?>')">♡</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-results">Aucun livre disponible pour le moment.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <nav>
            <ul>
                <li><a href="faq.php">FAQ</a></li>
                <li><a href="conditions.php">Conditions d'utilisation</a></li>
                <li><a href="apropos.php">À propos</a></li>
            </ul>
        </nav>

        <h1>Bibliothèques De la Reussite</h1>

        <section class="sect2">
            <div><p>&copy; 2026 - Tous droits réservés</p></div>
        </section>
    </footer>
    <script src="https://kit.fontawesome.com/a2b4a29c24.js" crossorigin="anonymous"></script>
    <script>
        function toggleFavorite(btn, bookId, bookTitle) {
            let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            const index = favorites.findIndex(f => f.id == bookId);
            
            if (index === -1) {
                favorites.push({ id: bookId, title: bookTitle });
                btn.classList.add('active');
                btn.textContent = '♥';
            } else {
                favorites.splice(index, 1);
                btn.classList.remove('active');
                btn.textContent = '♡';
            }
            
            localStorage.setItem('favorites', JSON.stringify(favorites));
        }

        // Initialize on page load
        window.addEventListener('DOMContentLoaded', function() {
            const favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            document.querySelectorAll('.favorite-btn-small').forEach(btn => {
                const bookId = btn.getAttribute('onclick').match(/\d+/)[0];
                const isFavorite = favorites.some(f => f.id == bookId);
                if (isFavorite) {
                    btn.classList.add('active');
                    btn.textContent = '♥';
                }
            });
        });
    </script>
</body>
</html>