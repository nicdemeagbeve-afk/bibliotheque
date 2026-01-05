<?php
/**
 * Composant de navigation avec authentification
 * Inclure ce fichier dans <header> pour avoir une nav dynamique
 * 
 * Usage: <?php include __DIR__ . '/includes/auth_header.php'; ?>
 */
if (!isset($_SESSION)) session_start();

$is_logged_in = isset($_SESSION['user_id']);
$is_admin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
$user_name = $_SESSION['user_name'] ?? '';
?>

<header>
    <h1>Bibliothèques De la Reussite</h1>
    <nav>
        <ul>
            <li><a href="/revisionphp/index.php">Acceuil</a></li>
            <li><a href="/revisionphp/liste.php">📚 Parcourir</a></li>
            <li><a href="/revisionphp/index.php#favoris">❤️ Favoris</a></li>
            
            <?php if ($is_admin): ?>
                <li><a href="/revisionphp/admin/create.php">➕ Ajouter</a></li>
            <?php endif; ?>

            <?php if ($is_logged_in): ?>
                <li><a href="/revisionphp/profile.php">👤 <?= htmlspecialchars(substr($user_name, 0, 15)) ?></a></li>
                <li><a href="/revisionphp/logout.php">🚪 Déconnexion</a></li>
            <?php else: ?>
                <li><a href="/revisionphp/login.php">🔐 Connexion</a></li>
                <li><a href="/revisionphp/register.php">📝 S'inscrire</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
