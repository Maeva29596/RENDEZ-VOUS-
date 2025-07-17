<?php
// supprimer_hopital.php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['hospital_id']) && !empty($_POST['hospital_id'])) {
        $hospital_id = intval($_POST['hospital_id']);

        // Supprimer les services liés à cet hôpital (si besoin)
        $sql_delete_services = "DELETE FROM services WHERE hospital_id = ?";
        $stmt_services = $conn->prepare($sql_delete_services);
        $stmt_services->bind_param("i", $hospital_id);
        $stmt_services->execute();
        $stmt_services->close();

        // Supprimer l'hôpital
        $sql_delete_hopital = "DELETE FROM hopitaux WHERE hospital_id = ?";
        $stmt_hopital = $conn->prepare($sql_delete_hopital);
        $stmt_hopital->bind_param("i", $hospital_id);
        $stmt_hopital->execute();

        if ($stmt_hopital->affected_rows > 0) {
            $stmt_hopital->close();
            $conn->close();
            header("Location: hopitaux.php?msg=success");
            exit();
        } else {
            $stmt_hopital->close();
            $conn->close();
            header("Location: hopitaux.php?msg=notfound");
            exit();
        }
    } else {
        header("Location: hopitaux.php?msg=error");
        exit();
    }
} else {
    header("Location: hopitaux.php");
    exit();
}
