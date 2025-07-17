<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['hospital_id'])) {
    echo json_encode(['error' => 'Non autorisé']);
    exit();
}

$hospital_id = $_SESSION['hospital_id'];

$data = [
    'patients' => 0,
    'en_attente' => 0,
    'aujourdhui' => 0
];

// Patients distincts
$sqlPatients = "SELECT COUNT(DISTINCT patient_id) AS total FROM `rendez-vous` WHERE hospital_id = ?";
$stmt = $conn->prepare($sqlPatients);
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $data['patients'] = (int)$row['total'];
}
$stmt->close();

// Rendez-vous en attente
$sqlPending = "SELECT COUNT(*) AS total FROM `rendez-vous` WHERE hospital_id = ? AND statut = 'en_attente'";
$stmt = $conn->prepare($sqlPending);
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $data['en_attente'] = (int)$row['total'];
}
$stmt->close();

// Rendez-vous aujourd'hui
$today = date('Y-m-d');
$sqlToday = "SELECT COUNT(*) AS total FROM `rendez-vous` WHERE hospital_id = ? AND date_rendez_vous = ?";
$stmt = $conn->prepare($sqlToday);
$stmt->bind_param("is", $hospital_id, $today);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $data['aujourdhui'] = (int)$row['total'];
}
$stmt->close();

echo json_encode($data);
?>
