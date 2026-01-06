<?php
session_start();
include "connexion.php";

$livres = [];
$query = "SELECT * FROM livres";
try {
    $stmt = $pdo->query($query);
    $livres = $stmt->fetchAll();
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
    <title>Bibliothèque Numérique</title>
</head>
<body>
    <header>
        <h1>Bibliothèques De la Reussite</h1>
        <nav>
            <ul>
                <li> <a href="index.php">Accueil</a></li>
                <li> <a href="liste.php">Parcourir</a></li>
                <li> <a href="#favoris">Favoris</a></li>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <li> <a href="admin/create.php">Ajouter</a></li>
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
        <section class="hero">
            <div class="text_hero">
                <h2>Bienvenue à la Bibliothèque du Savoir</h2>
                <p>Ici vous apprendrez à connaître, aussi bien le jargon populaire que la langue véritable de Molière</p>
            </div>
            <div class="button">
                <button onclick="window.location.href='#catalogue'">Accédez aux livres</button>
            </div>
        </section>

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

        <!-- Carrousel -->
        <?php if (count($livres) > 0): ?>
        <section class="carousel-section">
            <h2>📖 À la Une</h2>
            <div class="carousel-container">
                <div class="carousel" id="mainCarousel">
                    <?php foreach ($livres as $livre): ?>
                    <div class="carousel-item">
                        <div class="book-image-container">
                            <img src="admin/get_image.php?id=<?php echo $livre['id']; ?>" alt="<?php echo htmlspecialchars($livre['titre']); ?>" class="book-image">
                            <button class="favorite-btn" onclick="toggleFavorite(this, <?php echo $livre['id']; ?>, '<?php echo htmlspecialchars($livre['titre']); ?>')">♡</button>
                        </div>
                        <div class="carousel-item-info">
                            <h3><?php echo htmlspecialchars($livre['titre']); ?></h3>
                            <p><?php echo htmlspecialchars($livre['auteur']); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-btn carousel-prev" onclick="scrollCarousel(-1)">❮</button>
                <button class="carousel-btn carousel-next" onclick="scrollCarousel(1)">❯</button>
            </div>
        </section>
        <?php endif; ?>

        <!-- Catalogue -->
        <section id="catalogue">
            <h2>📚 Notre Catalogue Complet</h2>
            <div class="results-grid">
                <?php if (count($livres) > 0): ?>
                    <?php foreach ($livres as $livre): ?>
                        <div class="book-card">
                            <div class="book-image">
                                <img src="admin/get_image.php?id=<?php echo $livre['id']; ?>" alt="<?php echo htmlspecialchars($livre['titre']); ?>">
                                <button class="favorite-btn" onclick="toggleFavorite(this, <?php echo $livre['id']; ?>, '<?php echo htmlspecialchars($livre['titre']); ?>')">♡</button>
                            </div>
                            <div class="book-info">
                                <h3><?php echo htmlspecialchars($livre['titre']); ?></h3>
                                <p class="auteur"><strong>Auteur:</strong> <?php echo htmlspecialchars($livre['auteur']); ?></p>
                                <p class="maison"><strong>Édition:</strong> <?php echo htmlspecialchars($livre['maison_edition'] ?? 'Non spécifiée'); ?></p>
                                <a href="detail.php?id=<?php echo $livre['id']; ?>" class="btn-details">Voir plus</a>
                                <a href="admin/edit.php?id=<?php echo $livre['id']; ?>" class="btn-edit">Modifier</a>
                                <a href="admin/delete.php?id=<?php echo $livre['id']; ?>" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?');">Supprimer</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-results">Aucun livre disponible pour le moment.</p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Historique et Favoris -->
        <div class="history-favorites-container">
            <!-- Historique -->
            <section class="history-section">
                <h2>📋 Historique de Consultation</h2>
                <ul class="history-list" id="historyList">
                    <li class="no-results">Aucun livre consulté</li>
                </ul>
            </section>

            <!-- Favoris -->
            <section class="favorites-section" id="favoris">
                <h2>❤️ Mes Favoris</h2>
                <ul class="favorites-list" id="favoritesList">
                    <li class="no-results">Aucun favori pour le moment</li>
                </ul>
            </section>
        </div>
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
        // Carousel functionality
        let currentSlide = 0;
        const carousel = document.getElementById('mainCarousel');
        const items = carousel ? carousel.querySelectorAll('.carousel-item') : [];

        function scrollCarousel(direction) {
            if (items.length === 0) return;
            currentSlide += direction;
            if (currentSlide >= items.length) currentSlide = 0;
            if (currentSlide < 0) currentSlide = items.length - 1;
            carousel.style.transform = `translateX(-${currentSlide * 100}%)`;
        }

        // Auto scroll carousel every 5 seconds
        setInterval(() => {
            if (items.length > 0) scrollCarousel(1);
        }, 5000);

        // Favorites management
        function toggleFavorite(btn, bookId, bookTitle) {
            let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            const index = favorites.findIndex(f => f.id == bookId);
            const isAdding = index === -1;

            if (isAdding) {
                favorites.push({ id: bookId, title: bookTitle });
                btn.classList.add('active');
                btn.textContent = '♥';
            } else {
                favorites.splice(index, 1);
                btn.classList.remove('active');
                btn.textContent = '♡';
            }

            localStorage.setItem('favorites', JSON.stringify(favorites));
            updateFavoritesList();

            // Notify server (reading list)
            fetch('liste_lecture.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: isAdding ? 'add' : 'remove', bookId: bookId })
            }).catch(() => {});
        }

        function updateFavoritesList() {
            const favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            const favoritesList = document.getElementById('favoritesList');
            
            if (favorites.length === 0) {
                favoritesList.innerHTML = '<li class="no-results">Aucun favori pour le moment</li>';
            } else {
                favoritesList.innerHTML = favorites.map(fav => 
                    `<li><a href="detail.php?id=${fav.id}">${fav.title}</a></li>`
                ).join('');
            }
        }

        // History management
        function addToHistory(bookId, bookTitle) {
            let history = JSON.parse(localStorage.getItem('history')) || [];
            const index = history.findIndex(h => h.id == bookId);
            
            if (index !== -1) {
                history.splice(index, 1);
            }
            history.unshift({ id: bookId, title: bookTitle, date: new Date().toLocaleString('fr-FR') });
            
            if (history.length > 10) {
                history.pop();
            }
            
            localStorage.setItem('history', JSON.stringify(history));
            updateHistoryList();
        }

        function updateHistoryList() {
            const history = JSON.parse(localStorage.getItem('history')) || [];
            const historyList = document.getElementById('historyList');
            
            if (history.length === 0) {
                historyList.innerHTML = '<li class="no-results">Aucun livre consulté</li>';
            } else {
                historyList.innerHTML = history.map(item => 
                    `<li><a href="detail.php?id=${item.id}">${item.title}</a><br><small>${item.date}</small></li>`
                ).join('');
            }
        }

        // Initialize on page load
        window.addEventListener('DOMContentLoaded', function() {
            updateFavoritesList();
            updateHistoryList();
            
            // Check favorites status on page load
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
</body>
</html>