<?php
// Activation des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// CODE DE TRAITEMENT PHP
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'db_connect.php';

    if (isset($_POST['submit'])) {
        // Récupération des champs
        $nom_hopital = trim($_POST['nom_hopital']);
        $adresse = trim($_POST['adresse']);
        $ville = trim($_POST['ville']);
        $telephone = trim($_POST['telephone']);
        $email = trim($_POST['email']);
        $mot_de_passe_clair = trim($_POST['password']);
        $description = trim($_POST['description']);
        $nom_chef = trim($_POST['chef_hopital']);
        $google_maps_link = !empty($_POST['google_maps_link']) ? trim($_POST['google_maps_link']) : null;

        // Hachage du mot de passe
        $mot_de_passe_hache = password_hash($mot_de_passe_clair, PASSWORD_DEFAULT);

        // Préparer et exécuter la requête
        $stmt = $conn->prepare("INSERT INTO hopitaux (nom, adresse, ville, telephone, email, mot_de_passe, la_description, nom_du_chef_d_hopital, google_maps_link) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $nom_hopital, $adresse, $ville, $telephone, $email, $mot_de_passe_hache, $description, $nom_chef, $google_maps_link);
        $stmt->execute();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Inscription Hôpital</title>
 <link rel="stylesheet" href="style5.css">
</head>
<body>
 <div class="container">
  <form class="form" method="POST">
    <h2>Inscription Hôpital</h2>
    <p>Enregistrez votre établissement médical</p>

    <input type="text" placeholder="Nom de l'hôpital" name="nom_hopital" required>
    <input type="text" placeholder="Adresse" name="adresse" required>
    <input type="text" placeholder="Ville" name="ville" required>
    <input type="email" placeholder="Email" name="email" required>
    <input type="tel" placeholder="Téléphone" name="telephone" required>
    <input type="password" placeholder="Mot de passe" name="password" required>
    <textarea placeholder="Description de l'hôpital" name="description" rows="3" required></textarea>
    <input type="text" placeholder="Nom du chef de l'hôpital" name="chef_hopital" required>
    
    <!-- Champ facultatif -->
    <input type="url" placeholder="Lien Google Maps (facultatif)" name="google_maps_link">

    <div class="policy-agreement">
      <input type="checkbox" name="politique" id="politique" required>
      <label for="politique">J'accepte d'être responsable des services médicaux que je propose et du cadre de mon entreprise</label>
    </div>

    <button type="submit" name="submit">S'inscrire</button>
    <p class="footer">Déjà un compte ? <a href="connexion.php">Se connecter</a></p>
  </form>
 </div>
</body>
</html>


<script>
// Gestion du formulaire
const form = document.getElementById('hospitalRegistrationForm');
 form.addEventListener('submit', function(e) {
 e.preventDefault();

// Exemple d'extraction des données
const data = new FormData(form);
 const nomHopital = data.get('nom_hopital');
 const email = data.get('email');

alert(`Bienvenue ${nomHopital} ! Votre inscription est bien enregistrée.`);

// Ici, tu peux ajouter un envoi des données vers un serveur (back-end) pour le traitement.
});
</script>
</body>
</html>