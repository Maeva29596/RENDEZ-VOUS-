<?php
// db_connect.php

$serveur = "localhost";   // Ou l'adresse de votre serveur de base de données
$utilisateur = "root";    // Votre nom d'utilisateur pour la base de données
$motdepasse = "";       // Votre mot de passe pour la base de données
$base_de_donnees = "espacehopital"; // Le nom de votre base de données

// Créer la connexion
$conn = new mysqli($serveur, $utilisateur, $motdepasse, $base_de_donnees);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
    
}

// Optionnel : S'assurer que les données sont encodées en UTF-8
$conn->set_charset("utf8");