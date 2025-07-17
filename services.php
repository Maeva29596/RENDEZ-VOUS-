<?php
session_start();
require_once 'db_connect.php';

$successMessage = "";
$errorMessage = "";
$services = [];

if (!isset($_SESSION['hospital_id'])) {
    $errorMessage = "Vous devez être connecté en tant qu'hôpital pour gérer les services.";
} else {
    $hospital_id = $_SESSION['hospital_id'];

    // Suppression d'un service
    if (isset($_POST['delete_id'])) {
        $delete_id = intval($_POST['delete_id']);
        $stmt = $conn->prepare("DELETE FROM services WHERE service_id = ? AND hospital_id = ?");
        $stmt->bind_param("ii", $delete_id, $hospital_id);
        if ($stmt->execute()) {
            $successMessage = "Service supprimé avec succès.";
        } else {
            $errorMessage = "Erreur lors de la suppression : " . $stmt->error;
        }
        $stmt->close();
    }

    // Ajout d'un service
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nom'], $_POST['description']) && empty($_POST['delete_id'])) {
        $nom = trim($_POST['nom']);
        $description = trim($_POST['description']);

        $stmt = $conn->prepare("INSERT INTO services (nom, description, hospital_id) VALUES (?, ?, ?)");
        if ($stmt === false) {
            $errorMessage = "Erreur de préparation : " . $conn->error;
        } else {
            $stmt->bind_param("ssi", $nom, $description, $hospital_id);
            if ($stmt->execute()) {
                $successMessage = "Service ajouté avec succès !";
            } else {
                $errorMessage = "Erreur lors de l'ajout : " . $stmt->error;
            }
            $stmt->close();
        }
    }

    // Récupération des services liés à l’hôpital connecté
    $stmt = $conn->prepare("SELECT service_id, nom, description FROM services WHERE hospital_id = ?");
    $stmt->bind_param("i", $hospital_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestion des Services</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="style10.css" />
</head>
<body>
    <header class="main-header">
        <h1>GESTION DES SERVICES</h1>
        <div style="margin-top: 10px;">
         <a href="dashboard-hopital.php" class="btn-retour-dashboard">← Retour au tableau de bord</a>
        </div>

    </header>
      
    <main class="container">
        <section class="card">
            <h2 class="card-title">Ajouter un service</h2>

            <?php if ($successMessage): ?>
                <p style="color: green; font-weight: bold;"><?= htmlspecialchars($successMessage) ?></p>
            <?php elseif ($errorMessage): ?>
                <p style="color: red; font-weight: bold;"><?= htmlspecialchars($errorMessage) ?></p>
            <?php endif; ?>

            <form id="add-service-form" class="add-form" method="post" action="">
                <div class="form-group">
                    <label for="service-name">Nom du service</label>
                    <input type="text" id="service-name" name="nom" placeholder="Ex: Cardiologie" required />
                </div>
                <div class="form-group flex-grow">
                    <label for="service-description">Description</label>
                    <input type="text" id="service-description" name="description" placeholder="Ex: Traitement des maladies cardiaques" required />
                </div>
                <button type="submit" class="btn btn-primary">
                    <span class="material-symbols-outlined">add</span> Ajouter
                </button>
            </form>
        </section>
         
        <section class="card">
            <h2 class="card-title">Liste des services</h2>
            <div class="service-list">
                <table class="service-table">
                    <thead>
                        <tr class="table-header-row">
                            <th>Nom du service</th>
                            <th>Description</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="service-table-body">
                        <?php if (!empty($services)): ?>
                            <?php foreach ($services as $service): ?>
                                <tr>
                                    <td><?= htmlspecialchars($service['nom']) ?></td>
                                    <td><?= htmlspecialchars($service['description']) ?></td>
                                    <td class="text-right">
                                        <form method="post" onsubmit="return confirm('Confirmer la suppression ?');" style="display:inline;">
                                            <input type="hidden" name="delete_id" value="<?= $service['service_id'] ?>">
                                            <button type="submit" class="btn-icon btn-delete" aria-label="Supprimer">
                                                <span class="material-symbols-outlined">delete</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" style="text-align:center;">Aucun service enregistré.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script src="script.js"></script>
</body>
</html>
