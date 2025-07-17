<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['rendez_vous_id'])) {
        $id = (int) $_POST['rendez_vous_id'];

        // Utiliser des backticks pour le nom de la table avec un tiret
        $stmt = $conn->prepare("DELETE FROM `rendez-vous` WHERE rendez_vous_id = ?");
        if (!$stmt) {
            die("Erreur de préparation : " . $conn->error);
        }

        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            header('Location: liste.php?message=Rendez-vous supprimé');
            exit;
        } else {
            die('Erreur lors de la suppression du rendez-vous : ' . $stmt->error);
        }

    } else {
        die('Données manquantes.');
    }
} else {
    header('Location: liste.php');
    exit;
}
