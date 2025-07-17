<?php
session_start();
require_once 'db_connect.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$patient_id = $_SESSION['user_id'];
$message = "";

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $prenom     = $_POST['prenom'];
    $nom        = $_POST['nom'];
    $email      = $_POST['email'];
    $telephone  = $_POST['telephone'];
    $ville      = $_POST['ville'];
    $sexe       = $_POST['sexe'];
    $adresse    = $_POST['adresse'];

    // Mise à jour des infos personnelles
    $sql = "UPDATE patients SET prenom=?, nom=?, email=?, telephone=?, ville=?, sexe=?, adresse=? WHERE patient_id=?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erreur SQL UPDATE : " . $conn->error);
    }
    $stmt->bind_param("sssssssi", $prenom, $nom, $email, $telephone, $ville, $sexe, $adresse, $patient_id);

    $stmt->execute();

    // Gestion du mot de passe
    $current_password = $_POST['current-password'];
    $new_password     = $_POST['new-password'];
    $confirm_password = $_POST['confirm-password'];

    if (!empty($current_password) && !empty($new_password) && $new_password === $confirm_password) {
        $check = $conn->prepare("SELECT mot_de_passe FROM patients WHERE patient_id=?");
        if (!$check) {
            die("Erreur SQL SELECT mot de passe : " . $conn->error);
        }
        $check->bind_param("i", $patient_id);
        $check->execute();
        $result = $check->get_result()->fetch_assoc();

        if ($result && password_verify($current_password, $result['mot_de_passe'])) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_pass = $conn->prepare("UPDATE patients SET mot_de_passe=? WHERE patient_id=?");
            if (!$update_pass) {
                die("Erreur SQL UPDATE mot de passe : " . $conn->error);
            }
            $update_pass->bind_param("si", $hashed_password, $patient_id);
            $update_pass->execute();
            $message = "Mot de passe mis à jour avec succès. ";
        } else {
            $message = "Mot de passe actuel incorrect. ";
        }
    }

    $message .= "Informations mises à jour.";
}

// Récupérer les infos du patient
$sql = "SELECT * FROM patients WHERE patient_id=?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Erreur SQL SELECT infos patient : " . $conn->error);
}
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paramètres - MediCare</title>
    <link rel="stylesheet" href="style7.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
<div class="dashboard-container">
    <aside class="sidebar">
        <div class="sidebar-header">
            <h1 class="logo">MediCare</h1>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="dashboard-patient.php"><i class='bx bxs-home'></i> Accueil</a></li>
                <li><a href="rendez-vous.php"><i class='bx bxs-calendar'></i> Rendez-vous</a></li>
                <li class="active"><a href="parametres.php"><i class='bx bxs-cog'></i> Paramètres</a></li>
            </ul>
        </nav>
        <div class="sidebar-footer">
             <a href="logout.php" class="logout-link" id="logoutLink">
           <i class='bx bx-log-out'></i> Déconnexion
         </a>
        </div>
    </aside>

    <main class="main-content">
        <div class="settings-container">
            <header class="settings-header">
                <h2>Paramètres du compte</h2>
                <p>Gérez vos informations personnelles</p>
                <?php if (!empty($message)) : ?>
                    <p style="color:green"><?= htmlspecialchars($message) ?></p>
                <?php endif; ?>
            </header>

            <form method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="telephone">Téléphone</label>
                        <input type="tel" name="telephone" value="<?= htmlspecialchars($user['telephone']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="ville">Ville</label>
                        <input type="text" name="ville" value="<?= htmlspecialchars($user['ville']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="sexe">Sexe</label>
                        <select name="sexe">
                            <option value="femme" <?= $user['sexe'] === 'femme' ? 'selected' : '' ?>>Femme</option>
                            <option value="homme" <?= $user['sexe'] === 'homme' ? 'selected' : '' ?>>Homme</option>
                            <option value="autre" <?= $user['sexe'] === 'autre' ? 'selected' : '' ?>>Autre</option>
                        </select>
                    </div>
                    <div class="form-group full-width">
                        <label for="adresse">Adresse</label>
                        <input type="text" name="adresse" value="<?= htmlspecialchars($user['adresse']) ?>">
                    </div>
                </div>

                <h3>Changer le mot de passe</h3>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="current-password">Mot de passe actuel</label>
                        <input type="password" name="current-password">
                    </div>
                    <div class="form-group">
                        <label for="new-password">Nouveau mot de passe</label>
                        <input type="password" name="new-password">
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Confirmer</label>
                        <input type="password" name="confirm-password">
                    </div>
                </div>

                <div class="button-container">
                    <button type="reset" class="btn-annuler">Annuler</button>
                    <button type="submit" class="btn-enregistrer">Enregistrer</button>
                </div>
            </form>
        </div>
    </main>
</div>
<script>
     // Confirmation de déconnexion
    document.getElementById("logoutLink").addEventListener("click", function(event) {
        event.preventDefault(); // Empêche la redirection automatique
        const confirmLogout = confirm("Êtes-vous sûr de vouloir vous déconnecter ?");
        if (confirmLogout) {
            window.location.href = this.href; // Redirige vers logout.php si confirmé
        }
    });
</script>
</body>
</html>
