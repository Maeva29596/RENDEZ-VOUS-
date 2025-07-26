<?php
require_once 'db_connect.php'; // connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    if (empty($email)) {
        echo "⚠️ Veuillez entrer une adresse email.";
        exit;
    }

    $user = null;
    $role = '';

    // Chercher dans hopitaux
    $stmt = $conn->prepare("SELECT * FROM hopitaux WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $role = 'hopital';
    } else {
        // Sinon chercher dans patients
        $stmt = $conn->prepare("SELECT * FROM patients WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $role = 'patient';
        }
    }

    if ($user) {
        // Générer token sécurisé (compatible PHP < 7)
        $token = bin2hex(openssl_random_pseudo_bytes(32));
        $expire = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Mettre à jour la table correspondante avec token et expiration
        if ($role === 'hopital') {
            $update = $conn->prepare("UPDATE hopitaux SET reset_token = ?, reset_expires = ? WHERE email = ?");
        } else {
            $update = $conn->prepare("UPDATE patients SET reset_token = ?, reset_expires = ? WHERE email = ?");
        }
        $update->bind_param("sss", $token, $expire, $email);
        $update->execute();
        $update->close();

        // Redirection directe vers page de reset mot de passe
        $resetLink = "http://localhost/MYPHP/reset1_password.php?token=$token&role=$role";
        header("Location: $resetLink");
        exit();
    } else {
        echo "❌ Aucun compte trouvé avec cet email.";
        exit();
    }

} else {
    echo "❌ Méthode invalide.";
}
