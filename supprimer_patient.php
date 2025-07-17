<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['patient_id'])) {
    $patient_id = intval($_POST['patient_id']);

    // Préparation et exécution de la requête de suppression
    $stmt = $conn->prepare("DELETE FROM patients WHERE patient_id = ?");
    $stmt->bind_param("i", $patient_id);

    if ($stmt->execute()) {
        // Rediriger vers la page des patients après suppression
        header("Location:patients.php?success=1");
        exit();
    } else {
        echo "Erreur lors de la suppression : " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Requête invalide.";
}

$conn->close();
?>
