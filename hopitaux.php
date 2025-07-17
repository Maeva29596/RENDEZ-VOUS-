<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Médical - Liste des Hôpitaux</title>
    <link rel="stylesheet" href="style16.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .status-column {
            position: relative;
        }
        .status-button {
            background-color: #e0e7ff;
            color: #3b82f6;
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 120px;
        }
        .status-button::after {
           
            margin-left: 8px;
            font-size: 0.8rem;
        }
        .status-dropdown {
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            background-color: white;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 10;
            width: 100%;
            display: none;
        }
        .status-dropdown.open {
            display: block;
        }
        .status-dropdown ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .status-dropdown li button {
            display: block;
            width: 100%;
            padding: 10px;
            text-align: left;
            border: none;
            background: none;
            color: #64748b;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .status-dropdown li button:hover {
            background-color: #f3f4f6;
        }
        .status-en-attente {
            background-color: #fef08a;
            color: #a16207;
            border-color: #fde047;
        }
        .status-valide {
            background-color: #ccfbf1;
            color: #0f766e;
            border-color: #99f6e4;
        }
        .status-rejete {
            background-color: #fee2e2;
            color: #991b1b;
            border-color: #fecaca;
        }
    </style>
</head>
<body>

<?php
require_once 'db_connect.php';
$sql_h = "SELECT h.hospital_id, h.nom, h.adresse, h.ville, h.telephone, h.email, h.nom_du_chef_d_hopital, h.statut, s.nom AS service_nom
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
                'hospital_id' => $hop_id,
                'nom' => $row['nom'],
                'adresse' => $row['adresse'],
                'ville' => $row['ville'],
                'telephone' => $row['telephone'],
                'email' => $row['email'],
                'chef' => $row['nom_du_chef_d_hopital'],
                'statut' => $row['statut'],
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
            <div class="logo"></div>
            <h1>Dashboard Médical</h1>
        </div>
        <nav class="sidebar-nav">
            <p class="nav-title">Navigation</p>
            <ul>
                <li><a href="admin_connexion.php"><span>Accueil</span></a></li>
                <li><a href="patients.php"><span>Patients</span></a></li>
                <li class="active"><a href="#"><span>Hôpitaux</span></a></li>
                <li><a href="liste.php"><span>Rendez-vous</span></a></li>
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
                        <th>Statut</th>
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
                            <td class="status-column">
                                <div class="status-dropdown-container">
                                    <?php
                                        $statut_class = '';
                                        if ($hopital['statut'] == 'Validé') $statut_class = 'status-valide';
                                        elseif ($hopital['statut'] == 'Rejeté') $statut_class = 'status-rejete';
                                        else $statut_class = 'status-en-attente';
                                    ?>
                                    <button class="status-button <?= $statut_class ?>" onclick="toggleDropdown(this)" data-id="<?= $hopital['hospital_id'] ?>">
                                        <?= htmlspecialchars($hopital['statut']) ?>
                                    </button>
                                    <div class="status-dropdown">
                                        <ul>
                                            <li><button onclick="setStatus(this, 'En attente', 'status-en-attente')">En attente</button></li>
                                            <li><button onclick="setStatus(this, 'Validé', 'status-valide')">Validé</button></li>
                                            <li><button onclick="setStatus(this, 'Rejeté', 'status-rejete')">Rejeté</button></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <form method="post" action="supprimer_hopital.php" onsubmit="return confirm('Confirmer la suppression ?');">
                                    <input type="hidden" name="hospital_id" value="<?= $hopital['hospital_id'] ?>">
                                    <button class="delete-btn">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="9">Aucun hôpital trouvé.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>

<script>
function toggleDropdown(button) {
    const dropdown = button.nextElementSibling;
    dropdown.classList.toggle('open');
}

function setStatus(item, statusText, statusClass) {
    const button = item.closest('.status-dropdown-container').querySelector('.status-button');
    const hospitalId = button.dataset.id;

    button.textContent = statusText;
    button.className = 'status-button ' + statusClass;
    toggleDropdown(button);

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "update_statut.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("hospital_id=" + hospitalId + "&statut=" + encodeURIComponent(statusText));

    xhr.onload = function () {
        if (xhr.status !== 200 || xhr.responseText.trim() !== "success") {
            alert("Erreur lors de la mise à jour du statut.");
        }
    };
}

window.addEventListener('click', function(event) {
    if (!event.target.matches('.status-button')) {
        const dropdowns = document.querySelectorAll('.status-dropdown');
        dropdowns.forEach(dropdown => {
            dropdown.classList.remove('open');
        });
    }
});
</script>

</body>
</html>
