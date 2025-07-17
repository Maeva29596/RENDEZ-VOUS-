<?php
session_start();
require_once 'db_connect.php';

// Vérifie que le patient est connecté
if (!isset($_SESSION['id']) || $_SESSION['type'] !== 'patient') {
    header("Location: connexion.php");
    exit();
}

$patient_id = $_SESSION['id'];

// Requête corrigée avec table `rendez-vous` entourée de backticks
$sql = "SELECT r.*, h.nom AS nom_hopital, h.ville, h.adresse, s.nom AS nom_service
        FROM `rendez-vous` r
        JOIN hopitaux h ON r.hospital_id = h.hospital_id
        JOIN services s ON r.service_id = s.service_id
        WHERE r.patient_id = ?
        ORDER BY r.date_creation DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Erreur de préparation SQL : " . $conn->error);
}

$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Rendez-vous - MediCare</title>
    <link rel="stylesheet" href="style8.css">
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
                <li class="active"><a href="#"><i class='bx bxs-calendar'></i> Rendez-vous</a></li>
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
            <h2>Mes Rendez-vous</h2>
            
        </header>

        <section class="appointments-section">
            <div class="table-container">
                <table>
                    <thead>
                    <tr>
                        <th>Hôpital</th>
                        <th>Service</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Statut</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td>
                                <div class="hospital-name"><?php echo htmlspecialchars($row['nom_hopital']); ?></div>
                                <div class="hospital-location"><?php echo htmlspecialchars($row['ville']); ?></div>
                            </td>
                            <td><?php echo htmlspecialchars($row['nom_service']); ?></td>
                            <td>
                                <?php
                                if (!empty($row['date_rendez_vous']) && $row['date_rendez_vous'] != "0000-00-00") {
                                    echo htmlspecialchars($row['date_rendez_vous']);
                                } else {
                                    echo '<span class="status status-pending">En attente</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if (!empty($row['heure_rendez_vous']) && $row['heure_rendez_vous'] != "00:00:00") {
                                    echo htmlspecialchars($row['heure_rendez_vous']);
                                } else {
                                    echo '<span class="status status-pending">En attente</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $statut = !empty($row['statut']) ? $row['statut'] : 'en_attente';
                                if ($statut === 'confirmé') {
                                    $class = 'status-confirmed';
                                } elseif ($statut === 'rejeté') {
                                    $class = 'status-rejected';
                                } elseif ($statut === 'terminé') {
                                    $class = 'status-completed';
                                } else {
                                    $class = 'status-pending';
                                }
                                ?>
                                <span class="status <?php echo $class; ?>"><?php echo ucfirst($statut); ?></span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <footer class="pagination-footer">
            <div class="pagination-info">
                Affichage de vos rendez-vous.
            </div>
        </footer>
    </main>
</div>

<script>
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
