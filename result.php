<?php
session_start();
include "connexion.php";

$livres = [];

if (isset($_GET["search"]) && !empty($_GET['search'])) {
    $search = $con->real_escape_string($_GET['search']);
    $search_type = $_GET['search_type'] ?? 'titre';

    //Requête SQL pour la recherche de livre
    $query = "SELECT * FROM livres WHERE $search_type LIKE '%$search%'";
    $result = $con->query($query);

    if ($result) {
        $livres = $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Resultat de la recherche</title>
</head>
<body>
    <header>
        <h1>Bibliothèques De la Reussite</h1>
        <nav>
            <ul>
                <li><a href="index.php">Acceuil</a></li>
                <li><a href="liste.php">📚 Parcourir</a></li>
                <li><a href="index.php#favoris">❤️ Favoris</a></li>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <li><a href="admin/create.php">➕ Ajouter</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="profile.php">👤 <?= htmlspecialchars(substr($_SESSION['user_name'], 0, 15)) ?></a></li>
                    <li><a href="logout.php">🚪 Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="login.php">🔐 Connexion</a></li>
                    <li><a href="register.php">📝 S'inscrire</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main class="resultat">
        <?php if (isset($_GET['search']) && count($livres) === 0): ?>
            <p class="no-results">Oups ce livre ne se trouve pas dans notre Base de données</p> 
        <?php elseif (count($livres) > 0): ?>
            <h2>Résultats trouvés</h2>
            <div class="results-grid">
                <?php foreach ($livres as $livre): ?>
                    <div class="book-card">
                        <div class="book-image">
                            <img src="admin/get_image.php?id=<?php echo $livre['id']; ?>" alt="<?php echo htmlspecialchars($livre['titre']); ?>">
                            <button class="favorite-btn" onclick="toggleFavorite(this, <?php echo $livre['id']; ?>, '<?php echo htmlspecialchars($livre['titre']); ?>')">♡</button>
                        </div>
                        <div class="book-info">
                            <h3><?php echo htmlspecialchars($livre['titre']); ?></h3>
                            <p class="auteur"><strong>Auteur:</strong> <?php echo htmlspecialchars($livre['auteur']); ?></p>
                            <p class="maison"><strong>Édition:</strong> <?php echo htmlspecialchars($livre['maison_edition'] ?? 'N/A'); ?></p>
                            <a href="detail.php?id=<?php echo $livre['id']; ?>" class="btn-details">Voir plus</a>
                            <a href="admin/edit.php?id=<?php echo $livre['id']; ?>" class="btn-edit">Modifier</a>
                            <a href="admin/delete.php?id=<?php echo $livre['id']; ?>" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?');">Supprimer</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <footer> 
        <nav>
            <ul>
                <li> <a href="faq.php">FAQ</a></li>
                <li> <a href="conditions.php">Conditions d'utilisation</a></li>
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
            document.querySelectorAll('.favorite-btn').forEach(btn => {
                const bookId = btn.getAttribute('onclick').match(/\d+/)[0];
                const isFavorite = favorites.some(f => f.id == bookId);
                if (isFavorite) {
                    btn.classList.add('active');
                    btn.textContent = '♥';
                }
            });
        });
    </script>