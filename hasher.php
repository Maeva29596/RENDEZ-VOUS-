<?php
$password = "1602"; // Remplace par le mot de passe que tu veux
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Voici le mot de passe hashé : <br><textarea cols='100' rows='2'>" . $hash . "</textarea>";
?>
