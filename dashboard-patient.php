<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['id']) || $_SESSION['type'] !== 'patient') {
    header("Location: connexion.php");
    exit();
}

$patient_id = $_SESSION['id'];
$prenom = "";

// Récupération du prénom depuis la base
$stmt = $conn->prepare("SELECT prenom FROM patients WHERE patient_id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $prenom = $row['prenom'];
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Patient - MediCare</title>
    <link rel="stylesheet" href="style6.css">
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
                <li class="active"><a href="#"><i class='bx bxs-home'></i> Accueil</a></li>
                <li><a href="rendez-vous.php"><i class='bx bxs-calendar'></i> Rendez-vous</a></li>
                <li><a href="parametres.php"><i class='bx bxs-cog'></i> Paramètres</a></li>
            </ul>
        </nav>
        <div class="sidebar-footer">
         <a href="logout.php" class="logout-link" id="logoutLink">
           <i class='bx bx-log-out'></i> Déconnexion
         </a>

        </div>
    </aside>

    <main class="main-content">
        <header class="main-header">
            <div class="greeting">
                <h2>Bonjour <?= htmlspecialchars($prenom) ?>, comment allez-vous aujourd’hui ?</h2>
                <p>Bienvenue sur votre tableau de bord</p>
            </div>
            <div class="toolbar">
                <div class="search-wrapper">
                    <i class='bx bx-search search-icon'></i>
                    <input type="text" id="searchService" placeholder="Rechercher un service médical...">
                </div>
            </div>
        </header>

        <section class="appointments-overview">
            <div class="card card-confirmed">
                <div class="card-icon-wrapper confirmed-bg">
                    <i class='bx bx-check'></i>
                </div>
                <div class="card-content">
                    <h3 id="count-confirmes">0</h3>
                    <p>Rendez-vous confirmés</p>
                </div>
                <a href="rendez-vous.php">Voir tous les rendez-vous <i class='bx bx-chevron-right'></i></a>
            </div>

            <div class="card card-pending">
                <div class="card-icon-wrapper pending-bg">
                    <i class='bx bx-time-five'></i>
                </div>
                <div class="card-content">
                    <h3 id="count-attente">0</h3>
                    <p>Rendez-vous en attente</p>
                </div>
                <a href="rendez-vous.php">Voir tous les rendez-vous <i class='bx bx-chevron-right'></i></a>
            </div>
        </section>

        <section class="medical-services">
            <div class="section-header">
                <h3>Services médicaux les plus recherchés</h3>
                <a href="#">Voir tous les services <i class='bx bx-chevron-right'></i></a>
            </div>
            <div class="services-grid">
                <!-- services affichés ici -->
                <div class="service-card"><i class='bx bxs-user-circle service-icon' style="color: #4A90E2;"></i><h4>Médecine générale</h4><p>Consultations régulières</p></div>
                <div class="service-card"><i class='bx bxs-face service-icon' style="color: #A362D8;"></i><h4>Dermatologie</h4><p>Soins de la peau</p></div>
                <div class="service-card"><i class='bx bxs-heart service-icon' style="color: #50E3C2;"></i><h4>Cardiologie</h4><p>Santé cardiovasculaire</p></div>
                <div class="service-card"><i class='bx bxs-show service-icon' style="color: #E3506A;"></i><h4>Ophtalmologie</h4><p>Soins des yeux</p></div>
                <div class="service-card"><i class='bx bxs-baby-carriage service-icon' style="color: #F8E71C;"></i><h4>Pédiatrie</h4><p>Soins pour enfants</p></div>
                <div class="service-card"><i class='bx bxs-injection service-icon' style="color: #79B5F3;"></i><h4>Dentisterie</h4><p>Soins dentaires</p></div>
            </div>
        </section>

        <section class="hospital-results">
            <div class="section-header">
                <h3>Hôpitaux correspondant au service recherché</h3>
            </div>
            <div id="results" class="hospital-cards"></div>
        </section>
    </main>
</div>

<script>
// 🔍 Recherche dynamique
document.getElementById("searchService").addEventListener("keyup", function () {
    let searchQuery = this.value;
    if (searchQuery.length < 2) {
        document.getElementById("results").innerHTML = "";
        return;
    }

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "recherche_service.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (this.status === 200) {
            document.getElementById("results").innerHTML = this.responseText;
        } else {
            console.error("Erreur AJAX : statut " + this.status);
        }
    };
    xhr.send("query=" + encodeURIComponent(searchQuery));
});

// 🔢 Compteurs AJAX
window.addEventListener("DOMContentLoaded", () => {
    fetch("compter_rendezvous.php")
        .then(response => response.text())
        .then(text => {
            try {
                const data = JSON.parse(text);
                if (!data.error) {
                    document.getElementById("count-confirmes").textContent = data.confirmes ?? 0;
                    document.getElementById("count-attente").textContent = data.en_attente ?? 0;
                } else {
                    console.error("Erreur côté PHP :", data.error);
                }
            } catch (err) {
                console.error("Erreur de parsing JSON :", err);
                console.log("Réponse brute du PHP :", text);
            }
        })
        .catch(err => console.error("Erreur lors du fetch :", err));
});

// Confirmation de déconnexion
    document.getElementById("logoutLink").addEventListener("click", function(event) {
        event.preventDefault(); // Empêche la redirection automatique
        const confirmLogout = confirm("Êtes-vous sûr de vouloir vous déconnecter ?");
        if (confirmLogout) {
            window.location.href = this.href; // Redirige vers logout.php si confirmé
        }
    });

</script>



</body>
</html>
