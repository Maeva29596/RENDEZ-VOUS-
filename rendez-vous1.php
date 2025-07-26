<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db_connect.php';

// PHPMailer
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Vérifier que l'hôpital est connecté
if (!isset($_SESSION['hospital_id']) || $_SESSION['type'] !== 'hopital') {
    die("Accès refusé : vous devez être connecté en tant qu'hôpital.");
}

$hospital_id = $_SESSION['hospital_id'];

// Traitement mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $rendez_vous_id = intval($_POST['rendez_vous_id']);
    $date_rdv = $_POST['date_rendez_vous'];
    $heure_rdv = $_POST['heure_rendez_vous'];
    $statut = $_POST['statut'];

    // Mise à jour du rendez-vous
    $stmt = $conn->prepare("UPDATE `rendez-vous` SET date_rendez_vous = ?, heure_rendez_vous = ?, statut = ? WHERE rendez_vous_id = ? AND hospital_id = ?");
    $stmt->bind_param("sssii", $date_rdv, $heure_rdv, $statut, $rendez_vous_id, $hospital_id);
    $stmt->execute();
    $stmt->close();

    // Récupérer email du patient
    $stmt = $conn->prepare("SELECT p.email, p.nom, p.prenom FROM `rendez-vous` rdv JOIN patients p ON rdv.patient_id = p.patient_id WHERE rdv.rendez_vous_id = ?");
    $stmt->bind_param("i", $rendez_vous_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient = $result->fetch_assoc();
    $stmt->close();

    if ($patient) {
        $email = $patient['email'];
        $nom = $patient['nom'];
        $prenom = $patient['prenom'];

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;

            // === À MODIFIER DES DONNEES
            $mail->Username = 'maevaandela@gmail.com';
            $mail->Password = 'kxyxidsbzymelsjb';
            $mail->setFrom('maevaandela@gmail.com', 'MediCare');
            // ==================================================================

            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->addAddress($email, "$prenom $nom");

            $mail->isHTML(true);
            $mail->Subject = 'Mise à jour de votre rendez-vous - MediCare';
            $mail->Body    = "
                <h2>Bonjour $prenom $nom,</h2>
                <p>Votre rendez-vous a été mis à jour :</p>
                <ul>
                    <li><strong>Date :</strong> $date_rdv</li>
                    <li><strong>Heure :</strong> $heure_rdv</li>
                    <li><strong>Statut :</strong> $statut</li>
                </ul>
                <p>Merci d’utiliser <strong>MediCare</strong>.</p>
            ";
            $mail->AltBody = "Bonjour $prenom $nom, votre rendez-vous a été mis à jour. Date : $date_rdv, Heure : $heure_rdv, Statut : $statut.";

            // Optionnel: décommenter pour debug
            // $mail->SMTPDebug = 2;

            $mail->send();
            echo "Email envoyé avec succès à $email.<br>";
        } catch (Exception $e) {
            echo "Erreur lors de l'envoi de l'e-mail : " . $mail->ErrorInfo . "<br>";
        }
    }

    // Redirection vers la même page (pour éviter le resoumission POST)
    header("Location: rendez-vous1.php");
    exit();
}

// Suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $rendez_vous_id = intval($_POST['rendez_vous_id']);

    $stmt = $conn->prepare("DELETE FROM `rendez-vous` WHERE rendez_vous_id = ? AND hospital_id = ?");
    $stmt->bind_param("ii", $rendez_vous_id, $hospital_id);
    $stmt->execute();
    $stmt->close();

    header("Location: rendez-vous1.php");
    exit();
}

// Liste des rendez-vous
$sql = "SELECT rdv.rendez_vous_id, p.nom AS patient_nom, p.prenom AS patient_prenom, s.nom AS service_nom, rdv.motif, rdv.date_rendez_vous, rdv.heure_rendez_vous, rdv.statut
        FROM `rendez-vous` rdv
        JOIN patients p ON rdv.patient_id = p.patient_id
        JOIN services s ON rdv.service_id = s.service_id
        WHERE rdv.hospital_id = ?
        ORDER BY rdv.date_creation DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Gestion des Rendez-vous</title>
    <link rel="stylesheet" href="style12.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
</head>
<body>
<div class="container">
    <header class="header">
        <h1>GESTION DES RENDEZ-VOUS</h1>
    </header>

    <main class="main-content">
        <a href="dashboard-hopital.php" class="btn-dashboard"><i class="fa-solid fa-arrow-left"></i> Retour au tableau de bord</a>

        <?php if ($result->num_rows === 0): ?>
            <p>Aucun rendez-vous trouvé pour cet hôpital.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Service</th>
                        <th>Motif</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($rdv = $result->fetch_assoc()) : ?>
                    <form method="POST">
                        <tr>
                            <td><?= htmlspecialchars($rdv['patient_nom']) ?></td>
                            <td><?= htmlspecialchars($rdv['patient_prenom']) ?></td>
                            <td><?= htmlspecialchars($rdv['service_nom']) ?></td>
                            <td><?= htmlspecialchars($rdv['motif']) ?></td>
                            <td>
                                <input type="date" name="date_rendez_vous" value="<?= htmlspecialchars($rdv['date_rendez_vous']) ?>" class="form-input-table" required>
                            </td>
                            <td>
                                <input type="time" name="heure_rendez_vous" value="<?= htmlspecialchars($rdv['heure_rendez_vous']) ?>" class="form-input-table" required>
                            </td>
                            <td>
                                <?php $status_class = 'status-' . str_replace('_', '-', $rdv['statut']); ?>
                                <select name="statut" class="form-select-table <?= $status_class ?>" required>
                                    <option value="en_attente" <?= $rdv['statut'] === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                    <option value="confirmé" <?= $rdv['statut'] === 'confirmé' ? 'selected' : '' ?>>Confirmé</option>
                                    <option value="rejeté" <?= $rdv['statut'] === 'rejeté' ? 'selected' : '' ?>>Rejeté</option>
                                </select>
                            </td>
                            <td>
                                <div class="action-buttons-container">
                                    <input type="hidden" name="rendez_vous_id" value="<?= $rdv['rendez_vous_id'] ?>">
                                    <button type="submit" name="action" value="update" class="btn-action-update" title="Mettre à jour">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                    <button type="submit" name="action" value="delete" class="btn-action-delete" title="Supprimer" onclick="return confirm('Confirmer la suppression de ce rendez-vous ?');">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </form>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
