<?php
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = isset($_POST['token']) ? $_POST['token'] : '';
    $role = isset($_POST['role']) ? $_POST['role'] : '';
    $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    // Validation basique
    if (empty($token) || empty($role) || empty($new_password) || empty($confirm_password)) {
        header('Location: reset_password.php?token=' . urlencode($token) . '&role=' . urlencode($role) . '&error=Tous les champs sont requis.');
        exit();
    }

    if ($new_password !== $confirm_password) {
        header('Location: reset_password.php?token=' . urlencode($token) . '&role=' . urlencode($role) . '&error=Les mots de passe ne correspondent pas.');
        exit();
    }

    // Définir la table et la colonne ID en fonction du rôle
    if ($role === 'patient') {
        $table = 'patients';
        $id_col = 'patient_id';
    } elseif ($role === 'hopital') {
        $table = 'hopitaux';
        $id_col = 'hospital_id';
    } else {
        echo "Rôle invalide.";
        exit();
    }

    // Vérifier que le token est valide et non expiré
    $stmt = $conn->prepare("SELECT $id_col FROM $table WHERE reset_token = ? AND reset_expires > NOW()");
    if (!$stmt) {
        die("Erreur préparation requête: " . $conn->error);
    }

    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Lien invalide ou expiré.";
        exit();
    }

    $row = $result->fetch_assoc();
    $userId = $row[$id_col];

    $stmt->close();

    // Hasher le nouveau mot de passe
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Mettre à jour mot de passe et supprimer token
    $stmt = $conn->prepare("UPDATE $table SET mot_de_passe = ?, reset_token = NULL, reset_expires = NULL WHERE $id_col = ?");
    if (!$stmt) {
        die("Erreur préparation requête update: " . $conn->error);
    }

    $stmt->bind_param("si", $hashed_password, $userId);
    if ($stmt->execute()) {
        // Succès, rediriger vers la page de connexion avec message
        header("Location: connexion.php?msg=Mot de passe réinitialisé avec succès, veuillez vous connecter.");
        exit();
    } else {
        echo "Erreur lors de la mise à jour du mot de passe.";
    }
} else {
    // Si accès direct sans POST, rediriger vers la page mot de passe oublié
    header("Location: mot_de_passe_oublie.php");
    exit();
}
?>
