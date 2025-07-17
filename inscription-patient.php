<?php
// TOUT LE CODE PHP DE TRAITEMENT DOIT ÊTRE AVANT LE CODE HTML

// On vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // 1. Inclure le fichier de connexion à la base de données
  require_once 'db_connect.php';
  if (isset($_POST['submit'])) {
    # code...

    // 2. Récupérer les données du formulaire en les nettoyant
    $prenom = trim($_POST['prenom']);
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $mot_de_passe_clair = trim($_POST['password']); // Mot de passe en clair
    $telephone = trim($_POST['telephone']);
    $ville = trim($_POST['ville']);
    $sexe = trim($_POST['sexe']);
    $adresse = trim($_POST['adresse']);

    // 3. Hacher le mot de passe pour la sécurité
    $mot_de_passe_hache = password_hash($mot_de_passe_clair, PASSWORD_DEFAULT);

    // 4. Préparer la requête d'insertion pour éviter les injections SQL
    $sql = "INSERT INTO patients (prenom, nom, email, mot_de_passe, telephone, ville, sexe, adresse) VALUES ('$prenom', '$nom','$email', '$mot_de_passe_hache', $telephone, '$ville', '$sexe', '$adresse')";
    
    $sqlquerry=mysqli_query($conn,$sql);
      
   
  }
  /*}*/
   session_start();
    require_once 'db_connect.php'; // Fichier de connexion à ta base de données

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = trim($_POST["email"]);
        $password = $_POST["password"];
        $error = "";

        if (!empty($email) && !empty($password)) {
            // Vérifie d'abord dans la table hopitaux
            $stmt = $conn->prepare("SELECT * FROM patients WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $patientsResult = $stmt->get_result();

            if ($patientsResult->num_rows === 1) {
                $patient = $patientsResult->fetch_assoc();
                if (password_verify($password, $patient['mot_de_passe'])) {
                    $_SESSION['id'] = $patient['id'];
                    $_SESSION['type'] = 'patients';
                    $_SESSION['nom'] = $patient['prenom'];
                    header("Location: dashboard-patient.php");
                    exit();
                } else {
                    $error = "Mot de passe incorrect.";
                }
            }/*else {
                // Vérifie dans la table patients
                $stmt = $conn->prepare("SELECT * FROM patients WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $patientsResult = $stmt->get_result();

                if ($patientsResult->num_rows === 1) {
                    $patient = $patientsResult->fetch_assoc();
                    if (password_verify($password, $patient['mot_de_passe'])) {
                        $_SESSION['id'] = $patient['id'];
                        $_SESSION['type'] = 'patient';
                        $_SESSION['nom'] = $patient['prenom'];
                        header("Location: dashboard-patient.php");
                        exit();
                    } else {
                        $error = "Mot de passe incorrect.";
                    }
                } else {
                    $error = "Aucun compte trouvé avec cet email.";
                }
            }*/
        } else {
            $error = "Tous les champs sont requis.";
        }
    }
}
?>





<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription Patient</title>
  <link rel="stylesheet" href="style4.css">
</head>
<body>
  <div class="container">
    <form class="form"  method="POST" >
      <h2>Inscription Patient</h2>
      <p>Créez votre compte personnel</p>


  

      <div class="row">
        <input type="text" placeholder="Prénom" name="prenom" required>
        <input type="text" placeholder="Nom" name="nom" required>
      </div>

      <input type="email" placeholder="Email" name="email" required>
      <input type="password" placeholder="Mot de passe" name="password" required>
      <input type="tel" placeholder="Téléphone" name="telephone" required>

      <div class="row">
        <input type="text" placeholder="Ville" name="ville" required>
        <select name="sexe" required>
          <option value="">Genre</option>
     
          <option value="Homme">Homme</option>
          <option value="Femme">Femme</option>
        </select>
      </div>

      <input type="text" placeholder="Adresse" name="adresse" required>

      

      

      <button type="submit" name="submit">S'inscrire</button>

      <p class="footer">Déjà un compte ? <a href="connexion.php">Se connecter</a><br>
      <a href="inscription.html">Retour au choix de profil</a></p>
    </form>
  </div>

  
</body>
</html>
