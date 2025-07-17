<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['hospital_id'])) {
    header("Location: connexion.php");
    exit();
}

$hospital_id = $_SESSION['hospital_id'];
$services = [];

// Récupérer le nom de l'hôpital connecté
$stmt = $conn->prepare("SELECT nom FROM hopitaux WHERE hospital_id = ?");
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $hospital_name = $row['nom'];
} else {
    $hospital_name = "Hôpital inconnu";
}
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);

    if (!empty($nom) && !empty($description)) {
        $stmt = $conn->prepare("INSERT INTO services (nom, description, hospital_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $nom, $description, $hospital_id);
        $stmt->execute();
        $stmt->close();
    }
}

$stmt = $conn->prepare("SELECT nom, description FROM services WHERE hospital_id = ? ORDER BY service_id DESC");
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $services[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tableau de bord - <?= htmlspecialchars($hospital_name) ?></title>
    <link rel="stylesheet" href="style9.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
</head>
<body>

<div class="dashboard-container">
    <aside class="sidebar">
        <div class="sidebar-header">
            <i class='bx bxs-building-house logo-icon'></i>
            <h1 class="logo-text"><?= htmlspecialchars($hospital_name) ?></h1>
            <!-- Optionnel : tu peux garder ou enlever ce message d'accueil -->
            <!--p class="hospital-name">Bienvenue sur votre tableau de bord</p-->
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li class="active"><a href="#"><i class='bx bxs-home'></i> Accueil</a></li>
                <li><a href="rendez-vous1.php"><i class='bx bxs-calendar'></i> Rendez-vous</a></li>
                <li><a href="services.php"><i class='bx bxs-briefcase'></i> Services</a></li>
                <li><a href="parametres1.php"><i class='bx bxs-cog'></i> Paramètres</a></li>
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
            <h2 class="page-title">Tableau de bord</h2>
        </header>

        <section class="overview-cards-v2">
            <div class="card overview-card-v2 patient-card">
                <div class="card-icon-wrapper">
                    <i class='bx bxs-group'></i>
                </div>
               <div class="card-content">
                  <h3>Patients</h3>
                   <p class="metric" id="count-patients">0</p>
               </div>
            </div>
            <div class="card overview-card-v2 pending-appointments-card">
                <div class="card-icon-wrapper">
                    <i class='bx bx-time-five'></i>
                </div>
                <div class="card-content">
                    <h3>Rendez-vous en attente</h3>
                    <p class="metric" id="count-en-attente">0</p>
                </div>
            </div>
            <div class="card overview-card-v2 today-appointments-card">
                <div class="card-icon-wrapper">
                    <i class='bx bxs-calendar-check'></i>
                </div>
                <div class="card-content">
                    <h3>Rendez-vous aujourd'hui</h3>
                    <p class="metric" id="count-aujourdhui">0</p>
                </div>
            </div>
        </section>

        <section class="add-service-section">
            <h3>Ajouter un nouveau service</h3>
            <form class="add-service-form" method="POST">
                <div class="input-group">
                    <label for="service-name">Nom du service</label>
                    <input type="text" id="service-name" name="nom" placeholder="Ex: Cardiologie" required />
                </div>
                <div class="input-group">
                    <label for="service-description">Description</label>
                    <input type="text" id="service-description" name="description" placeholder="Description du service..." required />
                </div>
                <button type="submit" class="add-service-btn"><i class='bx bx-plus'></i> Ajouter le service</button>
            </form>
        </section>

        <section class="hospital-services-list">
            <div class="section-header-list">
                <h3>Services de l'hôpital</h3>
            </div>
            <div class="services-grid-list">
                <?php foreach ($services as $index => $service): ?>
                    <div class="service-card-list <?php
                        $colors = ['primary-blue-border', 'primary-green-border', 'primary-purple-border', 'primary-orange-border', 'primary-red-border', 'primary-teal-border'];
                        echo $colors[$index % count($colors)];
                    ?>">
                        <h4 class="service-title"><?= htmlspecialchars($service['nom']) ?></h4>
                        <p class="service-description"><?= htmlspecialchars($service['description']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</div>

<script>
window.addEventListener('DOMContentLoaded', () => {
    fetch('compter_rendezvous_hopital.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Erreur:", data.error);
                return;
            }
            document.getElementById('count-patients').textContent = data.patients;
            document.getElementById('count-en-attente').textContent = data.en_attente;
            document.getElementById('count-aujourdhui').textContent = data.aujourdhui;
        })
        .catch(err => console.error("Erreur AJAX:", err));
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
