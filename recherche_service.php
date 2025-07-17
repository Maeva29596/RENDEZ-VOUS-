<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'db_connect.php';

if (isset($_POST['query'])) {
    $search = trim($_POST['query']);

    echo "<p>Service recherché : <strong>" . htmlspecialchars($search) . "</strong></p>";

    $sql = "
        SELECT h.hospital_id, h.nom, h.adresse, h.ville, h.telephone, h.la_description, h.nom_du_chef_d_hopital, h.google_maps_link
        FROM hopitaux h
        JOIN services s ON h.hospital_id = s.hospital_id
        WHERE s.nom LIKE ?
    ";

    $stmt = $conn->prepare($sql);
    $like = '%' . $search . '%';
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "
                <div class='result-card'>
                    <div class='result-header'>
                        <h3>" . htmlspecialchars($row['nom']) . "</h3>
                        <p class='hospital-chief'><i class='bx bxs-user'></i> Chef : " . htmlspecialchars($row['nom_du_chef_d_hopital']) . "</p>
                    </div>
                    <div class='result-body'>
                        <p><i class='bx bxs-map'></i> " . htmlspecialchars($row['adresse']) . ", " . htmlspecialchars($row['ville']) . "</p>";

            // Afficher le lien Google Maps s’il existe
            if (!empty($row['google_maps_link'])) {
                echo "<p><i class='bx bxs-location-plus'></i> <a href='" . htmlspecialchars($row['google_maps_link']) . "' target='_blank' style='color:#4c41d4;text-decoration:underline;'>Voir sur Google Maps</a></p>";
               //echo "<iframe src=". htmlspecialchars($row['google_maps_link']) ."></iframe>";
            }

           


            echo "
                        <p><i class='bx bxs-phone'></i> " . htmlspecialchars($row['telephone']) . "</p>
                        <p><i class='bx bxs-info-circle'></i> " . htmlspecialchars($row['la_description']) . "</p>
                    </div>
                    <div class='result-footer'>
                        <a href='formulaire.php?hospital_id=" . urlencode($row['hospital_id']) . "' class='btn-rdv'>Prendre un rendez-vous</a>
                       <a href='avis.php?hospital_id=" .urlencode($row['hospital_id']) ."' class='btn-avis'>🗣 Voir les avis</a>


                    </div>
                </div>
            ";
        }
    } else {
        echo "<p>Aucun hôpital trouvé pour ce service.</p>";
    }

    $stmt->close();
}
?>
