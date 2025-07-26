<?php
// Si un message est passé en GET (confirmation ou erreur), on l'affiche
$message = "";
if (isset($_GET['msg'])) {
    $message = htmlspecialchars($_GET['msg']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récupération de mot de passe</title>
    <link rel="stylesheet" href="style22.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="card-container">
        <div class="card">
            <div class="lock-icon">
                <i class="fas fa-lock"></i>
            </div>
            <h2>Mot de passe oublié ?</h2>
            <p>Entrez votre adresse email pour recevoir un lien de récupération</p>

            <?php if (!empty($message)) : ?>
                <p style="color: green; font-weight: bold;"><?= $message ?></p>
            <?php endif; ?>

            <form action="traitement_mot_de_passe_oublie.php" method="POST">
                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <div class="input-wrapper">
                        <span class="at-icon">@</span>
                        <input type="email" id="email" name="email" placeholder="votre@email.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="role">Vous êtes :</label>
                    <select name="role" id="role" required>
                        <option value="">-- Sélectionnez votre rôle --</option>
                        <option value="patient">Patient</option>
                        <option value="hopital">Hôpital</option>
                    </select>
                </div>

                <button type="submit" class="btn-send-link">Réinitialiser le mot de passe</button>
            </form>

            <a href="connexion.php" class="back-to-login">← Retour à la connexion</a>
        </div>
    </div>
</body>
</html>
