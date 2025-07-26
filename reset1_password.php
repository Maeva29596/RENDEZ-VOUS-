<?php
require 'db_connect.php';

$token = isset($_GET['token']) ? $_GET['token'] : '';
$role = isset($_GET['role']) ? $_GET['role'] : '';
$utilisateur = null;

// Vérifie si le rôle est valide
if ($role !== 'patient' && $role !== 'hopital') {
    echo "Rôle non valide.";
    exit();
}

// Préparer la requête en fonction du rôle
if ($role === 'patient') {
    $stmt = $conn->prepare("SELECT * FROM patients WHERE reset_token = ? AND reset_expires > NOW()");
} else {
    $stmt = $conn->prepare("SELECT * FROM hopitaux WHERE reset_token = ? AND reset_expires > NOW()");
}

if (!$stmt) {
    echo "Erreur préparation de la requête : " . $conn->error;
    exit();
}

$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Liaison des résultats à un tableau associatif
    $meta = $stmt->result_metadata();
    $fields = [];
    $row = [];
    while ($field = $meta->fetch_field()) {
        $fields[$field->name] = null;
        $row[] = &$fields[$field->name];
    }
    call_user_func_array([$stmt, 'bind_result'], $row);
    $stmt->fetch();
    $utilisateur = $fields;
} else {
    echo "Lien invalide ou expiré.";
    exit();
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialiser le mot de passe</title>
    <link rel="stylesheet" href="style22.css">
</head>
<body>
    <div class="card-container">
        <div class="card">
            <h2>Réinitialiser votre mot de passe</h2>
            <form action="update_password.php" method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <input type="hidden" name="role" value="<?php echo htmlspecialchars($role); ?>">
                <div class="form-group">
                    <label for="new_password">Nouveau mot de passe :</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirmer le mot de passe :</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn-send-link">Réinitialiser</button>
            </form>
        </div>
    </div>
</body>
</html>
