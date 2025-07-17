<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Médical - Liste des Hôpitaux</title>
    <link rel="stylesheet" href="style16.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php
require_once 'db_connect.php';
$sql_h = "SELECT h.hospital_id, h.nom, h.adresse, h.ville, h.telephone, h.email, h.nom_du_chef_d_hopital, s.nom AS service_nom 
          FROM hopitaux h 
          LEFT JOIN services s ON h.hospital_id = s.hospital_id 
          ORDER BY h.nom, s.nom";
$res_h = $conn->query($sql_h);

$hopitaux = [];
if ($res_h->num_rows > 0) {
    while($row = $res_h->fetch_assoc()) {
        $hop_id = $row['hospital_id'];
        if (!isset($hopitaux[$hop_id])) {
            $hopitaux[$hop_id] = [
                'hospital_id' => $hop_id,  // Ajouté pour éviter l'erreur undefined index
                'nom' => $row['nom'],
                'adresse' => $row['adresse'],
                'ville' => $row['ville'],
                'telephone' => $row['telephone'],
                'email' => $row['email'],
                'chef' => $row['nom_du_chef_d_hopital'],
                'services' => []
            ];
        }
        if (!empty($row['service_nom'])) {
            $hopitaux[$hop_id]['services'][] = $row['service_nom'];
        }
    }
}
?>

<div class="dashboard-container">
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z" clip-rule="evenodd" />
                </svg>
            </div>
            <h1>Dashboard Médical</h1>
        </div>
        <nav class="sidebar-nav">
            <p class="nav-title">Navigation</p>
            <ul>
                <li>
                    <a href="admin_connexion.php">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z" /><path d="M12 5.432l8.159 8.159c.026.026.05.054.07.084v6.101A2.25 2.25 0 0117.75 22H6.25a2.25 2.25 0 01-2.25-2.25V13.675a.75.75 0 01.071-.084l8.158-8.159z"/></svg>
                        <span>Accueil</span>
                    </a>
                </li>
                <li>
                    <a href="patients.php">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4.5 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM14.25 8.625a3.375 3.375 0 116.75 0 3.375 3.375 0 01-6.75 0zM1.5 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63A13.067 13.067 0 0113.5 21.75H12a9 9 0 00-9-9v-.604c0-1.192.238-2.32.664-3.392a.75.75 0 01.417-1.116.75.75 0 011.116.417 5.625 5.625 0 00-1.152 4.105v.228a7.5 7.5 0 01-1.5 4.545v.004z"/></svg>
                        <span>Patients</span>
                    </a>
                </li>
                <li class="active">
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M4.5 2.25a.75.75 0 000 1.5v16.5h-.75a.75.75 0 000 1.5h16.5a.75.75 0 000-1.5h-.75V3.75a.75.75 0 000-1.5h-15zM9 6a.75.75 0 000 1.5h.75a.75.75 0 000-1.5H9zm.75 3a.75.75 0 01.75-.75h3a.75.75 0 010 1.5h-3a.75.75 0 01-.75-.75zM9 12a.75.75 0 000 1.5h.75a.75.75 0 000-1.5H9zm.75 3a.75.75 0 01.75-.75h3a.75.75 0 010 1.5h-3a.75.75 0 01-.75-.75zM6 6a.75.75 0 00-.75.75v.75a.75.75 0 001.5 0v-.75A.75.75 0 006 6zm-1.5 6a.75.75 0 01.75-.75h.75a.75.75 0 010 1.5H5.25a.75.75 0 01-.75-.75zm.75 3.75a.75.75 0 000-1.5h-.75a.75.75 0 000 1.5h.75z" clip-rule="evenodd"/></svg>
                        <span>Hôpitaux</span>
                    </a>
                </li>
                <li>
                    <a href="liste.php">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M5.25 2.25a.75.75 0 00-.75.75v1.5a.75.75 0 001.5 0V3h12V2.25a.75.75 0 00-.75-.75H5.25zM19.5 4.5h-15a3 3 0 00-3 3V19.5a3 3 0 003 3h15a3 3 0 003-3V7.5a3 3 0 00-3-3z"/></svg>
                        <span>Rendez-vous</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
          <header class="main-header">
                <form action="logout1.php" method="post" style="display:inline;">
                    <button type="submit" class="logout-btn">Déconnexion</button>
                </form>
            </header>
        <section class="patient-list-container">
            <h2 class="patient-list-title">Liste des Hôpitaux</h2>
            <table class="patient-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Chef</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Adresse</th>
                        <th>Ville</th>
                        <th>Services</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($hopitaux)): ?>
                    <?php foreach ($hopitaux as $hopital): ?>
                        <tr>
                            <td><?= htmlspecialchars($hopital['nom']) ?></td>
                            <td><?= htmlspecialchars($hopital['chef']) ?></td>
                            <td><?= htmlspecialchars($hopital['email']) ?></td>
                            <td><?= htmlspecialchars($hopital['telephone']) ?></td>
                            <td><?= htmlspecialchars($hopital['adresse']) ?></td>
                            <td><?= htmlspecialchars($hopital['ville']) ?></td>
                            <td>
                                <ul>
                                    <?php foreach ($hopital['services'] as $service): ?>
                                        <li><?= htmlspecialchars($service) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                            <td class="action-buttons">
                                <form method="post" action="supprimer_hopital.php" onsubmit="return confirm('Confirmer la suppression de cet hôpital ?');">
                                    <input type="hidden" name="hospital_id" value="<?= $hopital['hospital_id'] ?>">
                                    <button class="delete-btn">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8">Aucun hôpital trouvé.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>

</body>
</html>
