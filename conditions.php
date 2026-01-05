<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Conditions d'Utilisation - Bibliothèque Numérique</title>
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
            <h2 style="color: #333; margin-bottom: 30px; border-bottom: 3px solid #f59e0b; padding-bottom: 10px;">⚖️ Conditions d'Utilisation</h2>

            <div style="margin-bottom: 25px;">
                <h3 style="color: #f59e0b; margin-bottom: 10px;">1. Objet</h3>
                <p style="color: #555; line-height: 1.6;">
                    Les présentes conditions d'utilisation régissent l'accès et l'utilisation de la plateforme 
                    "Bibliothèque de la Réussite". En accédant à ce site, vous acceptez d'être lié par ces conditions.
                </p>
            </div>

            <div style="margin-bottom: 25px;">
                <h3 style="color: #f59e0b; margin-bottom: 10px;">2. Licence d'Utilisation</h3>
                <p style="color: #555; line-height: 1.6;">
                    Vous êtes autorisé à utiliser ce site à des fins personnelles et non commerciales uniquement. 
                    Vous ne pouvez pas reproduire, vendre, revendre ou exploiter les contenus sans permission écrite.
                </p>
            </div>

            <div style="margin-bottom: 25px;">
                <h3 style="color: #f59e0b; margin-bottom: 10px;">3. Propriété Intellectuelle</h3>
                <p style="color: #555; line-height: 1.6;">
                    Tous les contenus (texte, images, PDFs) sont la propriété de leurs auteurs respectifs ou de la plateforme. 
                    Toute reproduction sans consentement est interdite.
                </p>
            </div>

            <div style="margin-bottom: 25px;">
                <h3 style="color: #f59e0b; margin-bottom: 10px;">4. Responsabilité des Utilisateurs</h3>
                <p style="color: #555; line-height: 1.6;">
                    Vous êtes responsable de l'utilisation que vous faites de la plateforme. 
                    Vous acceptez de respecter toutes les lois applicables et de ne pas utiliser le site à des fins illégales.
                </p>
            </div>

            <div style="margin-bottom: 25px;">
                <h3 style="color: #f59e0b; margin-bottom: 10px;">5. Limitation de Responsabilité</h3>
                <p style="color: #555; line-height: 1.6;">
                    La plateforme est fournie "telle quelle" sans garantie. Nous ne sommes pas responsables des dommages 
                    causés par l'utilisation ou l'incapacité à utiliser le site.
                </p>
            </div>

            <div style="margin-bottom: 25px;">
                <h3 style="color: #f59e0b; margin-bottom: 10px;">6. Confidentialité</h3>
                <p style="color: #555; line-height: 1.6;">
                    Vos données personnelles sont traitées conformément à notre politique de confidentialité. 
                    Nous nous engageons à protéger votre vie privée.
                </p>
            </div>

            <div style="margin-bottom: 25px;">
                <h3 style="color: #f59e0b; margin-bottom: 10px;">7. Modifications des Conditions</h3>
                <p style="color: #555; line-height: 1.6;">
                    Nous nous réservons le droit de modifier ces conditions d'utilisation à tout moment. 
                    Les modifications entrent en vigueur immédiatement après leur publication.
                </p>
            </div>

            <div style="margin-bottom: 25px;">
                <h3 style="color: #f59e0b; margin-bottom: 10px;">8. Résiliation</h3>
                <p style="color: #555; line-height: 1.6;">
                    Nous pouvons résilier ou suspendre votre accès au site sans préavis si vous violez ces conditions.
                </p>
            </div>

            <div style="margin-bottom: 25px;">
                <h3 style="color: #f59e0b; margin-bottom: 10px;">9. Liens Externes</h3>
                <p style="color: #555; line-height: 1.6;">
                    La plateforme peut contenir des liens vers des sites externes. Nous ne sommes pas responsables 
                    du contenu de ces sites.
                </p>
            </div>

            <div style="margin-bottom: 25px;">
                <h3 style="color: #f59e0b; margin-bottom: 10px;">10. Contact et Recours</h3>
                <p style="color: #555; line-height: 1.6;">
                    Pour toute question concernant ces conditions, veuillez nous contacter à : 
                    contact@bibliotheque-reussite.com
                </p>
            </div>

            <div style="background: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #f59e0b; color: #856404;">
                <p style="margin: 0; font-size: 14px;">
                    <strong>Dernière mise à jour:</strong> 3 Janvier 2026
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
