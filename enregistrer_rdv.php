<?php
session_start();
require_once 'db_connect.php';

// Vérifie que le patient est connecté
if (!isset($_SESSION['id'])) {
    die("Erreur : patient non connecté.");
}

$patient_id = $_SESSION['id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validation des champs obligatoires
    $required_fields = ['nom', 'prenom', 'email', 'telephone', 'service', 'motif', 'hospital_id'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            die("Erreur : le champ '$field' est obligatoire.");
        }
    }

    // Sécurisation et nettoyage des entrées
    $nom         = htmlspecialchars(trim($_POST["nom"]));
    $prenom      = htmlspecialchars(trim($_POST["prenom"]));
    $email       = htmlspecialchars(trim($_POST["email"]));
    $telephone   = htmlspecialchars(trim($_POST["telephone"]));
    $service_nom = htmlspecialchars(trim($_POST["service"]));
    $motif       = htmlspecialchars(trim($_POST["motif"]));
    $hospital_id = intval($_POST["hospital_id"]);

    if ($hospital_id <= 0) {
        die("Erreur : hôpital invalide.");
    }

    // Récupération de l'ID du service (lié à l'hôpital)
    $stmt_service = $conn->prepare("SELECT service_id FROM services WHERE nom = ? AND hospital_id = ?");
    if (!$stmt_service) {
        die("Erreur préparation service : " . $conn->error);
    }
    $stmt_service->bind_param("si", $service_nom, $hospital_id);
    $stmt_service->execute();
    $result_service = $stmt_service->get_result();

    if ($result_service->num_rows === 0) {
        die("Erreur : service introuvable dans cet hôpital.");
    }

    $service = $result_service->fetch_assoc();
    $service_id = $service['service_id'];

    $stmt_service->close();

    // Insertion dans la table rendez-vous
    $stmt_insert = $conn->prepare("
        INSERT INTO `rendez-vous` 
        (patient_id, hospital_id, service_id, motif, statut) 
        VALUES (?, ?, ?, ?, 'en_attente')
    ");

    if (!$stmt_insert) {
        die("Erreur préparation insertion : " . $conn->error);
    }

    $stmt_insert->bind_param("iiis", $patient_id, $hospital_id, $service_id, $motif);

    if ($stmt_insert->execute()) {
        $stmt_insert->close();
        header("Location: dashboard-patient.php");
        exit();
    } else {
        echo "Erreur lors de l'enregistrement du rendez-vous : " . $stmt_insert->error;
    }
}
?>
