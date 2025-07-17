<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hospital_id = intval($_POST['hospital_id']);
    $new_statut = trim($_POST['statut']);

    $stmt = $conn->prepare("UPDATE hopitaux SET statut = ? WHERE hospital_id = ?");
    $stmt->bind_param("si", $new_statut, $hospital_id);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
}
?>
