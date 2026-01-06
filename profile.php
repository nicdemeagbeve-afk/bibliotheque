<?php
session_start();
include __DIR__ . '/connexion.php';
include __DIR__ . '/config.php'; // Include config for SITE_URL

// Rediriger si non connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: " . SITE_URL . "/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user = null; // Initialize $user to null
$favoris_count = 0;
$lecture_count = 0;

// Récupérer infos de l'utilisateur
try {
    $stmt = $pdo->prepare("SELECT id_lecteur, nom_lecteur, email, role, date_inscription, dernier_acces FROM lecteurs WHERE id_lecteur = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Compter favoris
        $user_id_str = (string)$user_id;
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM favoris WHERE id_lecteur = ?");
        $stmt->execute([$user_id_str]);
        $favoris_count = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        // Compter liste de lecture
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM liste_lecture WHERE id_lecteur = ?");
        $stmt->execute([$user_id_str]);
        $lecture_count = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    } else {
        // If user is not found in DB, destroy session and redirect
        session_destroy();
        header("Location: " . SITE_URL . "/login.php");
        exit;
    }

} catch (PDOException $e) {
    error_log("Profile data fetch failed: " . $e->getMessage());
    // Optionally redirect to an error page or login with a message
    session_destroy();
    header("Location: " . SITE_URL . "/login.php?error=db_error");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="stylesheet" href="css/style.css">
   <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <title>Profil - Bibliothèques De la Reussite</title>
    <style>
        .profile-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: linear-gradient(135deg, #f1c039 0%, #f59e0b 100%);
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .profile-container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: white;
        }
        .profile-info {
            background: rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .profile-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        .profile-row:last-child {
            border-bottom: none;
        }
        .profile-label {
            font-weight: bold;
        }
        .profile-value {
            color: #f0f0f0;
        }
        .role-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
        }
        .role-admin {
            background: #ff6b6b;
            color: white;
        }
        .role-user {
            background: #51cf66;
            color: white;
        }
        .stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-box {
            background: rgba(0, 0, 0, 0.1);
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        .stat-box .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #ffd700;
        }
        .stat-box .stat-label {
            color: #f0f0f0;
            margin-top: 5px;
        }
        .button-group {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #fff;
            color: #667eea;
        }
        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        .btn-danger {
            background: #ff6b6b;
            color: white;
        }
        .btn-danger:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        .admin-link {
            text-align: center;
            margin-top: 20px;
        }
        .admin-link a {
            color: #ffd700;
            text-decoration: none;
            font-weight: bold;
            border-bottom: 2px solid #ffd700;
        }
        .admin-link a:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <header>
        <h1>Bibliothèques De la Reussite</h1>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="liste.php">Parcourir</a></li>
                <li><a href="profile.php">Profil</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="profile-container">
            <h2>Mon Profil</h2>

            <div class="profile-info">
                <div class="profile-row">
                    <span class="profile-label">Nom:</span>
                    <span class="profile-value"><?= htmlspecialchars($user['nom_lecteur'] ?? 'N/A') ?></span>
                </div>
                <div class="profile-row">
                    <span class="profile-label">Email:</span>
                    <span class="profile-value"><?= htmlspecialchars($user['email'] ?? 'N/A') ?></span>
                </div>
                <div class="profile-row">
                    <span class="profile-label">Rôle:</span>
                    <span class="profile-value">
                        <?php if (($user['role'] ?? '') === 'admin'): ?>
                            <span class="role-badge role-admin">ADMINISTRATEUR</span>
                        <?php else: ?>
                            <span class="role-badge role-user">Utilisateur</span>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="profile-row">
                    <span class="profile-label">Inscrit le:</span>
                    <span class="profile-value"><?= date('d/m/Y à H:i', strtotime($user['date_inscription'] ?? 'now')) ?></span>
                </div>
                <div class="profile-row">
                    <span class="profile-label">Dernier accès:</span>
                    <span class="profile-value">
                        <?= ($user['dernier_acces'] ? date('d/m/Y à H:i', strtotime($user['dernier_acces'])) : 'Première connexion') ?>
                    </span>
                </div>
            </div>

            <div class="stats">
                <div class="stat-box">
                    <div class="stat-number"><?= $favoris_count ?></div>
                    <div class="stat-label">Favoris</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number"><?= $lecture_count ?></div>
                    <div class="stat-label">À lire</div>
                </div>
            </div>

            <div class="button-group">
                <a href="index.php#favoris" class="btn btn-primary">Mes favoris</a>
                <a href="logout.php" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr?')">Déconnexion</a>
            </div>

            <?php if ($user['role'] === 'admin'): ?>
                <div class="admin-link">
                    <p><a href="admin/create.php">Ajouter un livre (Admin)</a></p>
                </div>
            <?php endif; ?>
        </div>
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
