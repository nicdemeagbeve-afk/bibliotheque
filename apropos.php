<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>À Propos - Bibliothèque Numérique</title>
</head>
<body>
    <header>
        <h1>Bibliothèques De la Reussite</h1>
        <nav>
            <ul>
                <li><a href="index.php">Acceuil</a></li>
                <li><a href="liste.php">📚 Parcourir</a></li>
                <li><a href="index.php#favoris">❤️ Favoris</a></li>
                <?php session_start(); if (!empty($_SESSION['is_admin'])): ?>
                    <li><a href="create.php">➕ Ajouter</a></li>
                    <li><a href="admin/">Admin</a></li>
                    <li><a href="admin_toggle.php?action=logout">🔒 Quitter admin</a></li>
                <?php else: ?>
                    <li><a href="admin_toggle.php?action=login">🔑 Mode admin</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main style="max-width: 900px; margin: 40px auto; padding: 20px;">
        <section style="background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
            <h2 style="color: #333; margin-bottom: 30px; border-bottom: 3px solid #f59e0b; padding-bottom: 10px;">ℹ️ À Propos de Nous</h2>

            <div style="margin-bottom: 30px;">
                <h3 style="color: #f59e0b; margin-bottom: 15px; font-size: 22px;">📖 Notre Mission</h3>
                <p style="color: #555; line-height: 1.8; font-size: 16px;">
                    Bienvenue à la <strong>Bibliothèque de la Réussite</strong>, votre plateforme numérique dédiée à l'apprentissage et à la découverte. 
                    Notre mission est de rendre la connaissance accessible à tous en proposant une vaste collection de livres numériques, 
                    accompagnés de ressources pédagogiques de qualité.
                </p>
            </div>

            <div style="margin-bottom: 30px;">
                <h3 style="color: #f59e0b; margin-bottom: 15px; font-size: 22px;">🎯 Nos Valeurs</h3>
                <ul style="color: #555; line-height: 1.8; font-size: 16px; margin-left: 20px;">
                    <li><strong>Accessibilité:</strong> Rendre la littérature accessible à tous, indépendamment de la localisation.</li>
                    <li><strong>Qualité:</strong> Proposer des contenus vérifiés et de haute qualité.</li>
                    <li><strong>Innovation:</strong> Utiliser les dernières technologies pour améliorer l'expérience utilisateur.</li>
                    <li><strong>Partage:</strong> Encourager l'échange de connaissances et les communautés d'apprenants.</li>
                </ul>
            </div>

            <div style="margin-bottom: 30px;">
                <h3 style="color: #f59e0b; margin-bottom: 15px; font-size: 22px;">💻 Nos Services</h3>
                <ul style="color: #555; line-height: 1.8; font-size: 16px; margin-left: 20px;">
                    <li>📚 Catalogue de livres numériques diversifiés</li>
                    <li>🔍 Recherche avancée et catégorisation</li>
                    <li>❤️ Système de favoris et de liste de lecture</li>
                    <li>📖 Lecteur PDF intégré avec zoom et navigation</li>
                    <li>📱 Interface responsive pour tous les appareils</li>
                    <li>📊 Historique de consultation personnalisé</li>
                </ul>
            </div>

            <div style="margin-bottom: 30px;">
                <h3 style="color: #f59e0b; margin-bottom: 15px; font-size: 22px;">🤝 Partenariats</h3>
                <p style="color: #555; line-height: 1.8; font-size: 16px;">
                    Nous travaillons en collaboration avec des éditeurs, des auteurs et des institutions éducatives 
                    pour enrichir continuellement notre collection et offrir une meilleure expérience à nos utilisateurs.
                </p>
            </div>

            <div style="margin-bottom: 30px;">
                <h3 style="color: #f59e0b; margin-bottom: 15px; font-size: 22px;">📞 Nous Contacter</h3>
                <p style="color: #555; line-height: 1.8; font-size: 16px;">
                    Avez-vous des questions ou des suggestions ? N'hésitez pas à nous contacter à travers le formulaire de contact disponible sur le site.<br><br>
                    <strong>Email:</strong> contact@bibliotheque-reussite.com<br>
                    <strong>Adresse:</strong> Bibliothèque de la Réussite, France<br>
                    <strong>Téléphone:</strong> +33 1 XX XX XX XX
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #f1c039 0%, #f59e0b 100%); padding: 20px; border-radius: 8px; color: #fff; text-align: center;">
                <p style="margin: 0; font-size: 16px; font-weight: bold;">
                    Merci de faire partie de la communauté de la Bibliothèque de la Réussite! 🙏
                </p>
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
