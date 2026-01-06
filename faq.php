<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>FAQ - Bibliothèque Numérique</title>
</head>
<body>
    <header>
        <h1>Bibliothèques De la Reussite</h1>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="liste.php">Parcourir</a></li>
                <li><a href="index.php#favoris">Favoris</a></li>
                <?php session_start(); if (!empty($_SESSION['is_admin'])): ?>
                    <li><a href="create.php">Ajouter</a></li>
                    <li><a href="admin/">Admin</a></li>
                    <li><a href="admin_toggle.php?action=logout">Quitter admin</a></li>
                <?php else: ?>
                    <li><a href="admin_toggle.php?action=login">Mode admin</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main style="max-width: 900px; margin: 40px auto; padding: 20px;">
        <section style="background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
            <h2 style="color: #333; margin-bottom: 30px; border-bottom: 3px solid #f59e0b; padding-bottom: 10px;">Questions Fréquemment Posées</h2>

            <div style="margin-bottom: 25px;">
                <h3 style="color: #f59e0b; margin-bottom: 10px;">Comment ajouter un livre à la bibliothèque ?</h3>
                <p style="color: #555; line-height: 1.6;">Pour ajouter un livre, vous devez d'abord accéder au mode admin en cliquant sur "Mode admin". Une fois connecté, utilisez le bouton "Ajouter" pour créer un nouveau livre. Remplissez le formulaire avec les informations du livre et l'image correspondante.</p>
            </div>

            <div style="margin-bottom: 25px;">
                <h3 style="color: #f59e0b; margin-bottom: 10px;">Comment ajouter un livre à mes favoris ?</h3>
                <p style="color: #555; line-height: 1.6;">Cliquez sur le cœur (♡) sur une carte de livre. Le cœur deviendra rouge (♥) et le livre sera ajouté à votre section "Mes Favoris" en bas de la page d'accueil. Vos favoris sont stockés localement dans votre navigateur.</p>
            </div>

            <div style="margin-bottom: 25px;">
                <h3 style="color: #f59e0b; margin-bottom: 10px;">Comment lire un PDF ?</h3>
                <p style="color: #555; line-height: 1.6;">Si un livre a un PDF, vous verrez un bouton "Lire le livre" sur la page de détail. Cliquez dessus pour ouvrir le lecteur PDF intégré. Vous pouvez zoomer, naviguer entre les pages et chercher du texte.</p>
            </div>

            <div style="margin-bottom: 25px;">
                <h3 style="color: #f59e0b; margin-bottom: 10px;">Comment chercher un livre ?</h3>
                <p style="color: #555; line-height: 1.6;">Utilisez la barre de recherche sur la page d'accueil. Vous pouvez chercher par titre ou auteur. Les résultats s'afficheront sous la barre de recherche.</p>
            </div>

            <div style="margin-bottom: 25px;">
                <h3 style="color: #f59e0b; margin-bottom: 10px;">Comment modifier un livre ?</h3>
                <p style="color: #555; line-height: 1.6;">En mode admin, vous verrez un bouton "Modifier" sur chaque page de détail. Cliquez-le pour éditer les informations du livre, l'image ou le PDF.</p>
            </div>

            <div style="margin-bottom: 25px;">
                <h3 style="color: #f59e0b; margin-bottom: 10px;">Comment supprimer un livre ?</h3>
                <p style="color: #555; line-height: 1.6;">En mode admin, vous verrez un bouton "Supprimer" sur la page de détail. Cliquez-le et confirmez votre action pour supprimer le livre définitivement.</p>
            </div>

            <div style="margin-bottom: 25px;">
                <h3 style="color: #f59e0b; margin-bottom: 10px;">💾 Mes données sont-elles sauvegardées ?</h3>
                <p style="color: #555; line-height: 1.6;">Les informations des livres, images et PDFs sont sauvegardés dans la base de données du serveur. Vos favoris et historique de lecture sont stockés dans votre navigateur.</p>
            </div>

            <div style="margin-bottom: 25px;">
                <h3 style="color: #f59e0b; margin-bottom: 10px;">Le site fonctionne-t-il sur mobile ?</h3>
                <p style="color: #555; line-height: 1.6;">Oui ! La bibliothèque est entièrement responsive et fonctionne parfaitement sur tous les appareils (téléphones, tablettes, ordinateurs).</p>
            </div>

            <div style="margin-bottom: 25px;">
                <h3 style="color: #f59e0b; margin-bottom: 10px;">Je rencontre un problème, que faire ?</h3>
                <p style="color: #555; line-height: 1.6;">Essayez de rafraîchir la page (F5). Si le problème persiste, videz le cache de votre navigateur ou contactez l'administrateur du site.</p>
                <h3 style="color: #f59e0b; margin-bottom: 10px;">Je rencontre un problème, que faire ?</h3>
                <p style="color: #555; line-height: 1.6;">Essayez de rafraîchir la page (F5). Si le problème persiste, videz le cache de votre navigateur ou contactez l'administrateur du site.</p>
            </div>
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
</body>
</html>
