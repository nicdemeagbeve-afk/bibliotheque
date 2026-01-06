<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include __DIR__ . '/connexion.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validations
    if (empty($nom) || empty($email) || empty($password) || empty($password_confirm)) {
        $error = '❌ Tous les champs sont obligatoires.';
    } elseif (strlen($nom) < 3) {
        $error = '❌ Le nom doit contenir au moins 3 caractères.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = '❌ Email invalide.';
    } elseif (strlen($password) < 6) {
        $error = '❌ Le mot de passe doit contenir au moins 6 caractères.';
    } elseif ($password !== $password_confirm) {
        $error = '❌ Les mots de passe ne correspondent pas.';
    } else {
        // Vérifier si email existe déjà
        try {
            $stmt = $pdo->prepare("SELECT id_lecteur FROM lecteurs WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'Cet email est déjà utilisé.';
            } else {
                // Insérer nouvel utilisateur
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO lecteurs (nom_lecteur, email, mot_de_passe, role) VALUES (?, ?, ?, 'user')");
                
                if ($stmt->execute([$nom, $email, $hashed_password])) {
                    $success = 'Inscription réussie! Vous pouvez maintenant vous connecter.';
                    $_POST = []; // Clear form
                } else {
                    $error = 'Erreur lors de l\'inscription.';
                }
            }
        } catch (PDOException $e) {
            $error = 'Erreur de base de données : ' . htmlspecialchars($e->getMessage());
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
    <title>Inscription - Bibliothèques De la Reussite</title>
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
        .success {
            color: #51cf66;
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
                <li><a href="login.php">Connexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="auth-container">
            <h2>S'inscrire</h2>
            
            <?php if ($error): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success"><?= $success ?></div>
                <p style="text-align: center; margin-top: 15px;">
                    <a href="../login.php" style="color: white; text-decoration: none; border-bottom: 2px solid white;">Aller à la connexion</a>
                </p>
            <?php else: ?>
                <form method="POST">
                    <div class="form-group">
                        <label for="nom">Nom complet:</label>
                        <input type="text" name="nom" id="nom" placeholder="Votre nom" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" placeholder="votre@email.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe:</label>
                        <input type="password" name="password" id="password" placeholder="Au moins 6 caractères" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirm">Confirmer le mot de passe:</label>
                        <input type="password" name="password_confirm" id="password_confirm" placeholder="Confirmer le mot de passe" required>
                    </div>

                    <button type="submit">S'inscrire</button>
                </form>

                <div class="link-auth">
                    Déjà inscrit? <a href="login.php">Se connecter ici</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <nav>
            <ul>
                <li><a href="../faq.php">FAQ</a></li>
                <li><a href="../conditions.php">Conditions d'utilisation</a></li>
                <li><a href="../apropos.php">À propos</a></li>
            </ul>
        </nav>
        <h1>Bibliothèques De la Reussite</h1>
        <section class="sect2">
            <div><p>&copy; 2026 - Tous droits réservés</p></div>
        </section>
    </footer>
</body>
</html>
