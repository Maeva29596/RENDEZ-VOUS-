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
    <form class="login-box">
       <h2>Connexion</h2>
      <p>Accédez à votre compte MediConnect</p>

      <label for="email">Email</label>
      <input type="email" id="email" placeholder="votre@email.com" required />

      <div class="password-row">
        <label for="password">Mot de passe</label>
        <a href="#">Mot de passe oublié?</a>
      </div>
      <input type="password" id="password" placeholder="********" required />

      <div class="checkbox-row">
        <input type="checkbox" id="remember" />
        <label for="remember">Se souvenir de moi</label>
      </div>

      <button type="submit">Se connecter</button>

      <p class="signup-text">Pas encore de compte? <a href="inscription.html">S’inscrire</a></p>
    </form>
  </div>
</body>
</html>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres - MediCare</title>
    <link rel="stylesheet" href="style7.css"> 
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h1 class="logo">MediCare</h1>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="dashboard-patient.php"><i class='bx bxs-home'></i> Accueil</a></li>
                    <li><a href="rendez-vous.php"><i class='bx bxs-calendar'></i> Rendez-vous</a></li>
                    <li class="active"><a href="parametres.html"><i class='bx bxs-cog'></i> Paramètres</a></li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <a href="#" class="logout-link"><i class='bx bx-log-out'></i> Déconnexion</a>
            </div>
        </aside>

        <main class="main-content">
            <div class="settings-container">
                <header class="settings-header">
                    <h2>Paramètres du compte</h2>
                    <p>Gérez vos informations personnelles</p>
                </header>

                <form id="settings-form">
                    <div class="form-section">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="prenom">Prénom</label>
                                <input type="text" id="prenom" name="prenom" value="Sophie">
                            </div>
                            <div class="form-group">
                                <label for="nom">Nom</label>
                                <input type="text" id="nom" name="nom" value="Martin">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" value="sophie.martin@example.com">
                            </div>
                            <div class="form-group">
                                <label for="telephone">Téléphone</label>
                                <input type="tel" id="telephone" name="telephone" value="06 12 34 56 78">
                            </div>
                             <div class="form-group">
                                <label for="ville">Ville</label>
                                <input type="text" id="ville" name="ville" value="Paris">
                            </div>
                            <div class="form-group">
                                <label for="genre">Genre</label>
                                <select id="genre" name="genre">
                                    <option value="femme" selected>Femme</option>
                                    <option value="homme">Homme</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                            <div class="form-group full-width">
                                <label for="adresse">Adresse</label>
                                <input type="text" id="adresse" name="adresse" value="123 rue de Paris, 75001 Paris">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Changer le mot de passe</h3>
                        <div class="form-grid">
                           <div class="form-group full-width">
                                <label for="current-password">Mot de passe actuel</label>
                                <input type="password" id="current-password" name="current-password" placeholder="••••••••">
                            </div>
                            <div class="form-group">
                                <label for="new-password">Nouveau mot de passe</label>
                                <input type="password" id="new-password" name="new-password" placeholder="••••••••">
                            </div>
                            <div class="form-group">
                                <label for="confirm-password">Confirmer le mot de passe</label>
                                <input type="password" id="confirm-password" name="confirm-password" placeholder="••••••••">
                            </div>
                        </div>
                    </div>

                        <div class="button-container">
                                <button class="btn-annuler">Annuler</button>
                                <button class="btn-enregistrer">Enregistrer</button>
                        </div>  
                </form>
            </div>
        </main>
    </div>

    <script >
    /**
 * Ce script gère uniquement les interactions côté client qui n'interfèrent pas
 * avec la soumission du formulaire gérée par PHP.
 */
document.addEventListener('DOMContentLoaded', () => {

    const settingsForm = document.getElementById('settings-form');
    const cancelButton = document.getElementById('cancel-button');
    const logoutLink = document.querySelector('.logout-link');

    // --- Gestion du bouton "Annuler" ---
    // Cette partie reste utile pour l'utilisateur.
    if (cancelButton) {
        cancelButton.addEventListener('click', () => {
            // On demande à l'utilisateur s'il est sûr de vouloir tout effacer.
            if (confirm("Êtes-vous sûr de vouloir annuler ? Les modifications non enregistrées seront perdues.")) {
                // 'reset()' remet le formulaire à son état de chargement initial.
                settingsForm.reset();
            }
        });
    }


});
    </script>
</body>
</html>