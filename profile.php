<?php
session_start();
include __DIR__ . '/connexion.php';

// Rediriger si non connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: /revisionphp/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];
$user_role = $_SESSION['user_role'];

// Récupérer infos de l'utilisateur
$stmt = $con->prepare("SELECT id_lecteur, nom_lecteur, email, role, date_inscription, dernier_acces FROM lecteurs WHERE id_lecteur = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Compter favoris
$user_id_str = (string)$user_id;
$stmt = $con->prepare("SELECT COUNT(*) as total FROM favoris WHERE id_lecteur = ?");
$stmt->bind_param('s', $user_id_str);
$stmt->execute();
$favoris_count = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

// Compter liste de lecture
$stmt = $con->prepare("SELECT COUNT(*) as total FROM liste_lecture WHERE id_lecteur = ?");
$stmt->bind_param('s', $user_id_str);
$stmt->execute();
$lecture_count = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/revisionphp/css/style.css">
    <title>Profil - Bibliothèques De la Reussite</title>
    <style>
        .profile-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
                <li><a href="/revisionphp/index.php">Accueil</a></li>
                <li><a href="/revisionphp/liste.php">📚 Parcourir</a></li>
                <li><a href="/revisionphp/profile.php">👤 Profil</a></li>
                <li><a href="/revisionphp/logout.php">🚪 Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="profile-container">
            <h2>👤 Mon Profil</h2>

            <div class="profile-info">
                <div class="profile-row">
                    <span class="profile-label">Nom:</span>
                    <span class="profile-value"><?= htmlspecialchars($user['nom_lecteur']) ?></span>
                </div>
                <div class="profile-row">
                    <span class="profile-label">Email:</span>
                    <span class="profile-value"><?= htmlspecialchars($user['email']) ?></span>
                </div>
                <div class="profile-row">
                    <span class="profile-label">Rôle:</span>
                    <span class="profile-value">
                        <?php if ($user['role'] === 'admin'): ?>
                            <span class="role-badge role-admin">👑 ADMINISTRATEUR</span>
                        <?php else: ?>
                            <span class="role-badge role-user">📖 Utilisateur</span>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="profile-row">
                    <span class="profile-label">Inscrit le:</span>
                    <span class="profile-value"><?= date('d/m/Y à H:i', strtotime($user['date_inscription'])) ?></span>
                </div>
                <div class="profile-row">
                    <span class="profile-label">Dernier accès:</span>
                    <span class="profile-value">
                        <?= $user['dernier_acces'] ? date('d/m/Y à H:i', strtotime($user['dernier_acces'])) : 'Première connexion' ?>
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
                <a href="/revisionphp/index.php#favoris" class="btn btn-primary">❤️ Mes favoris</a>
                <a href="/revisionphp/logout.php" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr?')">🚪 Déconnexion</a>
            </div>

            <?php if ($user['role'] === 'admin'): ?>
                <div class="admin-link">
                    <p><a href="/revisionphp/admin/create.php">➕ Ajouter un livre (Admin)</a></p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <nav>
            <ul>
                <li><a href="/revisionphp/faq.php">FAQ</a></li>
                <li><a href="/revisionphp/conditions.php">Conditions d'utilisation</a></li>
                <li><a href="/revisionphp/apropos.php">À propos</a></li>
            </ul>
        </nav>
        <h1>Bibliothèques De la Reussite</h1>
        <section class="sect2">
            <div><p>&copy; 2026 - Tous droits réservés</p></div>
        </section>
    </footer>
</body>
</html>
