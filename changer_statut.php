<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['rendez_vous_id'], $_POST['nouveau_statut'])) {
        $id = (int) $_POST['rendez_vous_id'];
        $statut = $_POST['nouveau_statut'];

        // Vérifier que le statut est valide
        $statuts_valides = ['en_attente', 'confirmé', 'rejeté'];
        if (!in_array($statut, $statuts_valides)) {
            die('Statut invalide.');
        }

        $stmt = $conn->prepare("UPDATE rendez_vous SET statut = ? WHERE rendez_vous_id = ?");
        $stmt->bind_param('si', $statut, $id);
        if ($stmt->execute()) {
            header('Location: liste.php?message=Statut mis à jour');
            exit;
        } else {
            die('Erreur lors de la mise à jour du statut.');
        }
    } else {
        die('Données manquantes.');
    }
} else {
    header('Location: liste.php');
    exit;
}
