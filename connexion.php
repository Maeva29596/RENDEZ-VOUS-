<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password']; // Ne pas trim un mot de passe

    if (!empty($email) && !empty($password)) {
        // Vérifie dans la table hopitaux
        $stmt = $conn->prepare("SELECT * FROM hopitaux WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $hopitauxResult = $stmt->get_result();

        if ($hopitauxResult->num_rows === 1) {
            $hopital = $hopitauxResult->fetch_assoc();

            if (password_verify($password, $hopital['mot_de_passe'])) {
                // Vérifie le statut du compte
                if ($hopital['statut'] === 'valide') {
                    $_SESSION['hospital_id'] = $hopital['hospital_id'];
                    $_SESSION['type'] = 'hopital';
                    $_SESSION['nom'] = $hopital['nom'];

                    header("Location: dashboard-hopital.php");
                    exit();
                } elseif ($hopital['statut'] === 'en_attente') {
                    echo "⚠️ Votre compte est en attente de validation par l’administrateur.";
                } elseif ($hopital['statut'] === 'rejete') {
                    echo "❌ Votre demande d'inscription a été rejetée.";
                } else {
                    echo "❌ Erreur : statut de compte inconnu.";
                }
            } else {
                echo "❌ Mot de passe incorrect.";
            }

        } else {
            // Si aucun hôpital trouvé, on teste maintenant chez les patients
            $stmt = $conn->prepare("SELECT * FROM patients WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $patientsResult = $stmt->get_result();

            if ($patientsResult->num_rows === 1) {
                $patient = $patientsResult->fetch_assoc();

                if (password_verify($password, $patient['mot_de_passe'])) {
                    $_SESSION['id'] = $patient['patient_id'];
                    $_SESSION['user_id'] = $patient['patient_id'];
                    $_SESSION['type'] = 'patient';
                    $_SESSION['nom'] = $patient['prenom'];

                    header("Location: dashboard-patient.php");
                    exit();
                } else {
                    echo "❌ Mot de passe incorrect.";
                }
            } else {
                echo "❌ Aucun compte trouvé avec cet email.";
            }
        }

    } else {
        echo "❗ Tous les champs sont requis.";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Connexion - MediConnect</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="login-container">
    <form class="login-box" method="POST" action="">
      
      <h2>Connexion</h2>
      <p>Accédez à votre compte MediConnect</p>

      <?php if (!empty($error)) : ?>
        <p style="color: red; text-align: center;"><?= $error ?></p>
      <?php endif; ?>

      <label for="email">Email</label>
      <input type="email" id="email" name="email" placeholder="votre@email.com" required />

      <div class="password-row">
        <label for="password">Mot de passe</label>
          <a href="#">Mot de passe oublié?</a>
      </div>
      <div class="password-toggle">
        <input type="password" id="password" name="password" placeholder="********" required  />
         
      </div>


      <div class="checkbox-row">
        <input type="checkbox" id="remember" />
        <label for="remember">Se souvenir de moi</label>
      </div>

      <button type="submit">Se connecter</button>

      <p class="signup-text">Pas encore de compte? <a href="inscription.html">S’inscrire</a></p>
    </form>
  </div>

  <script>
   function togglePassword() {
  const passwordInput = document.getElementById("password");
  const icon = document.querySelector(".toggle-password");
  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    icon.textContent = "🙈"; // changer l'icône si tu veux <span class="toggle-password" onclick="togglePassword()">👁️</span>
  } else {
    passwordInput.type = "password";
    icon.textContent = "👁️";
  }
}
</script>

</body>
</html>



