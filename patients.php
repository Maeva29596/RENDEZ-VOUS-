<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Médical - Liste des Patients</title>
    <link rel="stylesheet" href="style16.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php
require_once 'db_connect.php';
$sql = "SELECT * FROM patients ORDER BY date_inscription DESC";
$result = $conn->query($sql);
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
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z" /><path d="M12 5.432l8.159 8.159c.026.026.05.054.07.084v6.101A2.25 2.25 0 0117.75 22H6.25a2.25 2.25 0 01-2.25-2.25V13.675a.75.75 0 01.071-.084l8.158-8.159z" /></svg>
                            <span>Accueil</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4.5 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM14.25 8.625a3.375 3.375 0 116.75 0 3.375 3.375 0 01-6.75 0zM1.5 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63A13.067 13.067 0 0113.5 21.75H12a9 9 0 00-9-9v-.604c0-1.192.238-2.32.664-3.392a.75.75 0 01.417-1.116.75.75 0 011.116.417 5.625 5.625 0 00-1.152 4.105v.228a7.5 7.5 0 01-1.5 4.545v.004zM18.75 19.125a.75.75 0 00-1.5 0v.003l.001.12a.75.75 0 00.364.631 13.067 13.067 0 001.891 1.115h.75a9 9 0 019-9v-.604c0-1.192-.238-2.32-.664-3.392a.75.75 0 00-1.533-.417 5.625 5.625 0 011.152 4.105v.228a7.5 7.5 0 001.5 4.545v.004z" /></svg>
                            <span>Patients</span>
                        </a>
                    </li>
                    <li>
                        <a href="hopitaux.php">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M4.5 2.25a.75.75 0 000 1.5v16.5h-.75a.75.75 0 000 1.5h16.5a.75.75 0 000-1.5h-.75V3.75a.75.75 0 000-1.5h-15zM9 6a.75.75 0 000 1.5h.75a.75.75 0 000-1.5H9zm.75 3a.75.75 0 01.75-.75h3a.75.75 0 010 1.5h-3a.75.75 0 01-.75-.75zM9 12a.75.75 0 000 1.5h.75a.75.75 0 000-1.5H9zm.75 3a.75.75 0 01.75-.75h3a.75.75 0 010 1.5h-3a.75.75 0 01-.75-.75zM6 6a.75.75 0 00-.75.75v.75a.75.75 0 001.5 0v-.75A.75.75 0 006 6zm-1.5 6a.75.75 0 01.75-.75h.75a.75.75 0 010 1.5H5.25a.75.75 0 01-.75-.75zm.75 3.75a.75.75 0 000-1.5h-.75a.75.75 0 000 1.5h.75z" clip-rule="evenodd" /></svg>
                            <span>Hôpitaux</span>
                        </a>
                    </li>
                    <li>
                        <a href="liste.php">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M5.25 2.25a.75.75 0 00-.75.75v1.5a.75.75 0 001.5 0V3h12V2.25a.75.75 0 00-.75-.75H5.25zM19.5 4.5h-15a3 3 0 00-3 3V19.5a3 3 0 003 3h15a3 3 0 003-3V7.5a3 3 0 00-3-3zM8.25 10.5a.75.75 0 01.75.75v.01a.75.75 0 01-1.5 0v-.01a.75.75 0 01.75-.75zm1.5 0a.75.75 0 01.75.75v.01a.75.75 0 01-1.5 0v-.01a.75.75 0 01.75-.75zm3.375-1.5a.75.75 0 01.75.75v3a.75.75 0 01-1.5 0v-3a.75.75 0 01.75-.75zm-1.875.75a.75.75 0 01.75.75v.01a.75.75 0 01-1.5 0v-.01a.75.75 0 01.75-.75z" /></svg>
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
                <h2 class="patient-list-title">Liste des Patients</h2>
                <table class="patient-table">
                    <thead>
                        <tr>
                            <th>Prénom</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Adresse</th>
                            <th>Ville</th>
                            <th>Sexe</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['prenom']) ?></td>
                                <td><?= htmlspecialchars($row['nom']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['telephone']) ?></td>
                                <td><?= htmlspecialchars($row['adresse']) ?></td>
                                <td><?= htmlspecialchars($row['ville']) ?></td>
                                <td><?= htmlspecialchars($row['sexe']) ?></td>
                                <td class="action-buttons">
                                    <form method="post" action="supprimer_patient.php" onsubmit="return confirm('Confirmer la suppression ?');">
                                        <input type="hidden" name="patient_id" value="<?= $row['patient_id'] ?>">
                                        <button class="delete-btn">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="8">Aucun patient trouvé.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

</body>
</html>