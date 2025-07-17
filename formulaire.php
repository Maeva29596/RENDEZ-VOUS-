<?php
session_start();
require_once 'db_connect.php';

$hospital_id = isset($_GET['hospital_id']) ? intval($_GET['hospital_id']) : 0;
if ($hospital_id <= 0) {
    die("Erreur : hôpital non spécifié.");
}

// Optionnel : récupérer le nom de l'hôpital pour affichage (facultatif)
$stmt = $conn->prepare("SELECT nom FROM hopitaux WHERE hospital_id = ?");
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$res = $stmt->get_result();
$hopital = $res->fetch_assoc();
$stmt->close();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Prise de Rendez-vous</title>
    <link rel="stylesheet" href="style13.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
</head>
<body>

<div class="form-container">
    <div class="logo-wrapper">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
    </div>

    <div class="header">
        <h1>Prise de Rendez-vous chez <?= htmlspecialchars($hopital['nom']) ?></h1>
        <p>Remplissez le formulaire ci-dessous pour réserver votre créneau</p>
    </div>

    <form action="enregistrer_rdv.php" method="POST">
        <!-- Champ caché pour hospital_id -->
        <input type="hidden" name="hospital_id" value="<?= $hospital_id ?>">

        <div class="form-row">
            <div class="form-group">
                <label for="nom">Nom <span class="required">*</span></label>
                <input type="text" id="nom" name="nom" placeholder="Votre nom" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prénom <span class="required">*</span></label>
                <input type="text" id="prenom" name="prenom" placeholder="Votre prénom" required>
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email <span class="required">*</span></label>
            <input type="email" id="email" name="email" placeholder="votre@email.com" required>
        </div>

        <div class="form-group">
            <label for="telephone">Téléphone <span class="required">*</span></label>
            <input type="tel" id="telephone" name="telephone" placeholder="06 12 34 56 78" required>
        </div>

        <div class="form-group">
            <label for="service">Service souhaité <span class="required">*</span></label>
            <select id="service" name="service" required>
                <option value="" disabled selected>Sélectionnez un service</option>
                <?php
                // On récupère les services de cet hôpital uniquement
                $stmt = $conn->prepare("SELECT nom FROM services WHERE hospital_id = ?");
                $stmt->bind_param("i", $hospital_id);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res && $res->num_rows > 0) {
                    while ($row = $res->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($row['nom']) . '">' . htmlspecialchars($row['nom']) . '</option>';
                    }
                } else {
                    echo '<option disabled>Aucun service disponible</option>';
                }
                $stmt->close();
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="motif">Motif de la consultation <span class="required">*</span></label>
            <textarea id="motif" name="motif" rows="4" placeholder="Décrivez brièvement le motif de votre consultation..." required></textarea>
        </div>

        <div class="form-actions">
            <a href="dashboard-patient.php" class="back-btn">Retour au Dashboard</a>
            <button type="submit" class="submit-btn">Réserver mon créneau</button>
        </div>
    </form>
</div>

</body>
</html>
