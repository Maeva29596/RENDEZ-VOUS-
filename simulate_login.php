<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

// Simuler la connexion du patient avec l'ID 1 (à adapter si besoin)
$_SESSION['patient_id'] = 1;

echo "Patient connecté avec patient_id = 1.<br>";
echo '<a href="avis.php?hospital_id=2">Aller à la page des avis pour l\'hôpital 2</a>';

