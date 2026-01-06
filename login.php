<?php
session_start();
include __DIR__ . '/connexion.php';
include __DIR__ . '/config.php'; // Include config for SITE_URL

// Rediriger si déjà connecté
if (isset($_SESSION['user_id'])) {
    header("Location: " . SITE_URL . "/index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = '❌ Email et mot de passe obligatoires.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id_lecteur, nom_lecteur, mot_de_passe, role FROM lecteurs WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if (password_verify($password, $user['mot_de_passe'])) {
                    // Connexion réussie
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['id_lecteur'];
                    $_SESSION['user_name'] = $user['nom_lecteur'];
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_role'] = $user['role'];

                    // Mettre à jour dernier_acces
                    $stmt = $pdo->prepare("UPDATE lecteurs SET dernier_acces = NOW() WHERE id_lecteur = ?");
                    $stmt->execute([$user['id_lecteur']]);

                    header("Location: " . SITE_URL . "/index.php");
                    exit;
                } else {
                    $error = '❌ Mot de passe incorrect.';
                }
            } else {
                $error = '❌ Email non trouvé.';
            }
        } catch (PDOException $e) {
            $error = '❌ Erreur de base de données : ' . htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="stylesheet" href="css/style.css">
   <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <title>Connexion - Bibliothèques De la Reussite</title>
    <style>
        .auth-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .auth-container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: white;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: white;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            box-sizing: border-box;
        }
        .form-group input:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
        }
        .error {
            color: #ff6b6b;
            padding: 10px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #fff;
            color: #667eea;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        button:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        .link-auth {
            text-align: center;
            margin-top: 20px;
            color: white;
        }
        .link-auth a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            border-bottom: 2px solid white;
        }
        .link-auth a:hover {
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
                <li><a href="register.php">S'inscrire</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="auth-container">
            <h2>Se connecter</h2>
            
            <?php if ($error): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" placeholder="votre@email.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe:</label>
                    <input type="password" name="password" id="password" placeholder="Votre mot de passe" required>
                </div>

                <button type="submit">Se connecter</button>
            </form>

            <div class="link-auth">
                Pas encore inscrit? <a href="register.php">S'inscrire ici</a>
            </div>
        </div>
    </main>

    <footer>
        <nav>
            <ul>
                <li><a href="<?php echo SITE_URL; ?>/faq.php">FAQ</a></li>
                <li><a href="<?php echo SITE_URL; ?>/conditions.php">Conditions d'utilisation</a></li>
                <li><a href="<?php echo SITE_URL; ?>/apropos.php">À propos</a></li>
            </ul>
        </nav>
        <h1>Bibliothèques De la Reussite</h1>
        <section class="sect2">
            <div><p>&copy; 2026 - Tous droits réservés</p></div>
        </section>
    </footer>
</body>
</html>
