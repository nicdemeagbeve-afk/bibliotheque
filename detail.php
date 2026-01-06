<?php
session_start();
include "connexion.php";

$livre = null;

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']); 
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM livres WHERE id = ?");
        $stmt->execute([$id]);
        $livre = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Query failed: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo $livre ? htmlspecialchars($livre['titre']) : 'Livre'; ?></title>
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
        <?php if ($livre): ?>
            <div class="detail-container">
                <div class="detail-content">
                    <h1><?php echo htmlspecialchars($livre['titre']); ?></h1>
                    <p class="auteur"><strong>Auteur:</strong> <?php echo htmlspecialchars($livre['auteur']); ?></p>
                    <p class="maison"><strong>Maison d'édition:</strong> <?php echo htmlspecialchars($livre['maison_edition'] ?? 'Non spécifiée'); ?></p>
                    <p class="exemplaire"><strong>Exemplaires:</strong> <?php echo intval($livre['nombre_exemplaire'] ?? 0); ?></p>
                    <button class="favorite-btn" id="favBtn" onclick="toggleFavorite(<?php echo $livre['id']; ?>, '<?php echo htmlspecialchars($livre['titre']); ?>')">♡ Ajouter aux favoris</button>
                    <div class="description">
                        <h3>Description</h3>
                        <p><?php echo htmlspecialchars($livre['description']); ?></p>
                    </div>
                    
                    <?php if (!empty($livre['pdf_data'])): ?>
                    <div class="pdf-section">
                        <h3>Lecture</h3>
                        <p>Un PDF est disponible pour ce livre. Cliquez ci-dessous pour le lire en entier.</p>
                        <a href="#" onclick="openPdfReader(<?php echo $livre['id']; ?>); return false;" class="btn-read">Lire le livre</a>
                    </div>
                    <?php endif; ?>
                    
                    <div class="detail-actions">
                        <a href="liste.php" class="btn-back">← Retour à la liste</a>
                        <a href="edit.php?id=<?php echo $livre['id']; ?>" class="btn-edit">Modifier</a>
                        <a href="delete.php?id=<?php echo $livre['id']; ?>" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?');">Supprimer</a>
                    </div>
                </div>
                <div class="detail-image">
                    <img src="get_image.php?id=<?php echo $livre['id']; ?>" alt="<?php echo htmlspecialchars($livre['titre']); ?>">
                </div>
            </div>
        <?php else: ?>
            <p class="no-results">Livre non trouvé</p>
            <a href="index.php" class="btn-back">Retour à l'accueil</a>
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
    <!-- PDFjs library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        function openPdfReader(bookId) {
            // Créer une modale avec le lecteur PDF
            const modal = document.createElement('div');
            modal.id = 'pdf-modal';
            modal.className = 'pdf-modal';
            modal.innerHTML = `
                <div class="pdf-modal-content">
                    <div class="pdf-header">
                        <h2>Lecteur PDF</h2>
                        <div class="pdf-controls">
                            <button onclick="zoomPdf(-0.1)">🔍−</button>
                            <span id="zoom-level">100%</span>
                            <button onclick="zoomPdf(0.1)">🔍+</button>
                            <button onclick="closePdfReader()">✕</button>
                        </div>
                    </div>
                    <div class="pdf-viewer" id="pdf-viewer">
                        <canvas id="pdf-canvas"></canvas>
                    </div>
                    <div class="pdf-footer">
                        <button onclick="prevPage()">← Précédent</button>
                        <input type="number" id="page-num" min="1" value="1" onchange="goToPage()">
                        <span>/ <span id="total-pages">1</span></span>
                        <button onclick="nextPage()">Suivant →</button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            
            // Initialiser le lecteur PDF
            loadPdfReader(bookId);
        }

        let pdfDoc = null;
        let pageNum = 1;
        let zoomLevel = 1;

        async function loadPdfReader(bookId) {
            const pdfjsLib = window.pdfjsLib;
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
            
            try {
                pdfDoc = await pdfjsLib.getDocument('get_pdf.php?id=' + bookId).promise;
                document.getElementById('total-pages').textContent = pdfDoc.numPages;
                renderPage(1);
            } catch (e) {
                alert('Erreur lors du chargement du PDF: ' + e.message);
                closePdfReader();
            }
        }

        async function renderPage(num) {
            if (!pdfDoc || num < 1 || num > pdfDoc.numPages) return;
            
            pageNum = num;
            document.getElementById('page-num').value = num;
            
            const page = await pdfDoc.getPage(num);
            const canvas = document.getElementById('pdf-canvas');
            const ctx = canvas.getContext('2d');
            
            const viewport = page.getViewport({ scale: zoomLevel });
            canvas.width = viewport.width;
            canvas.height = viewport.height;
            
            await page.render({
                canvasContext: ctx,
                viewport: viewport
            }).promise;
        }

        function nextPage() {
            if (pdfDoc && pageNum < pdfDoc.numPages) {
                renderPage(pageNum + 1);
            }
        }

        function prevPage() {
            if (pageNum > 1) {
                renderPage(pageNum - 1);
            }
        }

        function goToPage() {
            const pageNum = parseInt(document.getElementById('page-num').value);
            renderPage(pageNum);
        }

        function zoomPdf(delta) {
            zoomLevel += delta;
            zoomLevel = Math.max(0.5, Math.min(zoomLevel, 3));
            document.getElementById('zoom-level').textContent = Math.round(zoomLevel * 100) + '%';
            renderPage(pageNum);
        }

        function closePdfReader() {
            const modal = document.getElementById('pdf-modal');
            if (modal) {
                modal.remove();
            }
            pdfDoc = null;
            pageNum = 1;
            zoomLevel = 1;
        }
    </script>
    <script>
        function toggleFavorite(bookId, bookTitle) {
            let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            const index = favorites.findIndex(f => f.id == bookId);
            const btn = document.getElementById('favBtn');
            const isAdding = index === -1;
            
            if (isAdding) {
                favorites.push({ id: bookId, title: bookTitle });
                btn.textContent = '♥ Retirer des favoris';
                btn.classList.add('active');
            } else {
                favorites.splice(index, 1);
                btn.textContent = '♡ Ajouter aux favoris';
                btn.classList.remove('active');
            }
            
            localStorage.setItem('favorites', JSON.stringify(favorites));

            // Notify server (reading list)
            fetch('liste_lecture.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: isAdding ? 'add' : 'remove', bookId: bookId })
            }).catch(() => {});
        }

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
        }

        // Initialize on page load
        window.addEventListener('DOMContentLoaded', function() {
            const bookId = <?php echo $livre['id']; ?>;
            const bookTitle = '<?php echo htmlspecialchars($livre['titre']); ?>';
            
            // Add to history (local)
            addToHistory(bookId, bookTitle);
            // Notify server about this view (optional)
            fetch('history.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ bookId: bookId })
            }).catch(() => {});
            
            // Check if in favorites
            const favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            const btn = document.getElementById('favBtn');
            const isFavorite = favorites.some(f => f.id == bookId);
            if (isFavorite) {
                btn.textContent = '♥ Retirer des favoris';
                btn.classList.add('active');
            }
        });
    </script>