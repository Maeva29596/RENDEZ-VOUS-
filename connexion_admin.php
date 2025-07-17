<?php
session_start();
require_once 'db_connect.php'; // Ton fichier de connexion à la base

$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM administrateurs WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result && $result->num_rows === 1) {
            $admin = $result->fetch_assoc();
            if (password_verify($password, $admin['mot_de_passe'])) {
                // Connexion réussie
                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['admin_nom'] = $admin['nom'];
                header("Location: admin_dashboard.php");
                exit();
            } else {
                $message = "Mot de passe incorrect.";
            }
        } else {
            $message = "Aucun compte administrateur trouvé avec cet email.";
        }
    } else {
        $message = "Erreur de requête SQL.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
    <link rel="stylesheet" href="style14.css">
</head>
<body>
    <div class="container">
        <h1>Connectez-vous en tant qu'admin</h1>
        <div class="login-box">
            <?php if (!empty($message)) : ?>
                <p style="color: red;"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>
            <form method="POST" action="admin_connexion.php">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <button type="submit">Se connecter</button>
            </form>
        </div>
    </div>
</body>
</html>
