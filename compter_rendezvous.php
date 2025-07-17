<?php
session_start();
require_once 'db_connect.php';

// Forcer le bon type MIME
header('Content-Type: application/json');

// Vérifie que le patient est connecté
if (!isset($_SESSION['id']) || $_SESSION['type'] !== 'patient') {
    echo json_encode(["error" => "Non autorisé"]);
    exit();
}

$patient_id = $_SESSION['id'];

// Requête SQL avec jointure propre
$sql = "SELECT TRIM(LOWER(statut)) AS statut, COUNT(*) AS total 
        FROM `rendez-vous`
        WHERE patient_id = ?
        GROUP BY statut";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["error" => "Erreur préparation : " . $conn->error]);
    exit();
}
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

// Initialiser les compteurs
$data = ["confirmes" => 0, "en_attente" => 0];

// Lire les résultats
while ($row = $result->fetch_assoc()) {
    if ($row["statut"] === "confirmé") {
        $data["confirmes"] = (int)$row["total"];
    } elseif ($row["statut"] === "en_attente") {
        $data["en_attente"] = (int)$row["total"];
    }
}

// ✅ Afficher du vrai JSON
echo json_encode($data);
exit();
