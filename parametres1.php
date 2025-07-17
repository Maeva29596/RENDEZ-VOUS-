<?php
session_start();
require_once 'db_connect.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifie que l'hôpital est connecté
if (!isset($_SESSION['hospital_id'])) {
    header("Location: connexion.php");
    exit();
}

$hospital_id = $_SESSION['hospital_id'];
$message = "";

// Récupérer les infos de l'hôpital
$sql = "SELECT * FROM hopitaux WHERE hospital_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom       = isset($_POST['nom']) ? $_POST['nom'] : '';
    $chef      = isset($_POST['chef']) ? $_POST['chef'] : '';
    $email     = isset($_POST['email']) ? $_POST['email'] : '';
    $telephone = isset($_POST['telephone']) ? $_POST['telephone'] : '';
    $ville     = isset($_POST['ville']) ? $_POST['ville'] : '';
    $adresse   = isset($_POST['adresse']) ? $_POST['adresse'] : '';

    // Mise à jour des infos personnelles
    $sql = "UPDATE hopitaux SET nom=?, nom_du_chef_d_hopital=?, email=?, telephone=?, ville=?, adresse=? WHERE hospital_id=?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erreur SQL UPDATE : " . $conn->error);
    }
    $stmt->bind_param("ssssssi", $nom, $chef, $email, $telephone, $ville, $adresse, $hospital_id);
    $stmt->execute();

    // Mise à jour du mot de passe si applicable
    $current_password = isset($_POST['current-password']) ? $_POST['current-password'] : '';
    $new_password     = isset($_POST['new-password']) ? $_POST['new-password'] : '';
    $confirm_password = isset($_POST['confirm-password']) ? $_POST['confirm-password'] : '';

    if (!empty($current_password) && !empty($new_password) && $new_password === $confirm_password) {
        $check = $conn->prepare("SELECT mot_de_passe FROM hopitaux WHERE hospital_id=?");
        $check->bind_param("i", $hospital_id);
        $check->execute();
        $result = $check->get_result()->fetch_assoc();

        if ($result && password_verify($current_password, $result['mot_de_passe'])) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_pass = $conn->prepare("UPDATE hopitaux SET mot_de_passe=? WHERE hospital_id=?");
            $update_pass->bind_param("si", $hashed_password, $hospital_id);
            $update_pass->execute();
            $message = "Mot de passe mis à jour avec succès. ";
        } else {
            $message = "Mot de passe actuel incorrect. ";
        }
    }

    $message .= "Informations mises à jour.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres de l'Hôpital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="style11.css">
</head>
<body>
    <header class="main-header">
        <h1>PARAMÈTRES DE L'HÔPITAL</h1>

         <div style="text-align: center; margin-bottom: 20px;">
                 <a href="dashboard-hopital.php" class="btn btn-secondary" style="padding: 10px 20px; text-decoration: none; color: #333; border: 1px solid #ccc; border-radius: 5px; display: inline-block;">
                       ← Retour au tableau de bord
                 </a>
           </div>
    </header>

    <main class="container">
        <div class="card">
           

            <form id="settings-form" method="POST">
                <?php if (!empty($message)) : ?>
                    <p style="color: green; font-weight: bold; text-align: center;">
                        <?= htmlspecialchars($message) ?>
                    </p>
                <?php endif; ?>

                <section class="form-section">
                    <h2 class="section-title">Informations générales</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="hospital-name">Nom de l'hôpital</label>
                            <input type="text" id="hospital-name" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="hospital-chief">Nom du chef de l'hôpital</label>
                            <input type="text" id="hospital-chief" name="chef" value="<?= htmlspecialchars(isset($user['nom_du_chef_d_hopital']) ? $user['nom_du_chef_d_hopital'] : '') ?>">
                        </div>
                    </div>
                </section>

                <section class="form-section">
                    <h2 class="section-title">Coordonnées</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="address">Adresse</label>
                            <input type="text" id="address" name="adresse" value="<?= htmlspecialchars($user['adresse']) ?>">
                        </div>
                        <div class="form-group">
                            <label for="city">Ville</label>
                            <input type="text" id="city" name="ville" value="<?= htmlspecialchars($user['ville']) ?>">
                        </div>
                        <div class="form-group">
                            <label for="phone">Téléphone</label>
                            <input type="tel" id="phone" name="telephone" value="<?= htmlspecialchars($user['telephone']) ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">
                        </div>
                    </div>
                </section>

                <section class="form-section">
                    <h2 class="section-title">Sécurité</h2>
                    <div class="form-group">
                        <label for="current-password">Mot de passe actuel</label>
                        <input type="password" id="current-password" name="current-password">
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="new-password">Nouveau mot de passe</label>
                            <input type="password" id="new-password" name="new-password">
                        </div>
                        <div class="form-group">
                            <label for="confirm-password">Confirmer le nouveau mot de passe</label>
                            <input type="password" id="confirm-password" name="confirm-password">
                        </div>
                    </div>
                </section>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <span class="material-symbols-outlined">save</span>
                        Enregistrer
                    </button>
                    <button type="reset" class="btn btn-secondary">
                        <span class="material-symbols-outlined">restart_alt</span>
                        Réinitialiser
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
