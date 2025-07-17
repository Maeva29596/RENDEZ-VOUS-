<?php
session_start();
require_once 'db_connect.php';

// Vérification de connexion adaptée à votre système
if (!isset($_SESSION['user_id']) || $_SESSION['type'] !== 'patient') {
    header('Location: connexion.php?redirect=avis&hospital_id='.(int)$_GET['hospital_id']);
    exit();
}

$hospital_id = isset($_GET['hospital_id']) ? (int)$_GET['hospital_id'] : 0;
$patient_id = (int)$_SESSION['user_id']; // Correspond à votre connexion.php
$message = '';

// Vérification que l'hôpital existe
if ($hospital_id > 0) {
    $check_hospital = $conn->prepare("SELECT hospital_id FROM hopitaux WHERE hospital_id = ? AND statut = 'valide'");
    $check_hospital->bind_param("i", $hospital_id);
    $check_hospital->execute();
    
    if (!$check_hospital->get_result()->num_rows) {
        $message = "<p class='error'>❌ Hôpital invalide ou introuvable.</p>";
        $hospital_id = 0;
    }
    $check_hospital->close();
} else {
    $message = "<p class='error'>❌ ID hôpital manquant dans l'URL.</p>";
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST" && $hospital_id > 0) {
    $comment = isset($_POST['comment']) ? trim(htmlspecialchars($_POST['comment'])) : '';
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;

    if ($rating < 1 || $rating > 5) {
        $message = "<p class='error'>❌ Veuillez choisir une note entre 1 et 5.</p>";
    } elseif (empty($comment)) {
        $message = "<p class='error'>❌ Le commentaire ne peut pas être vide.</p>";
    } else {
        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("INSERT INTO avis (patient_id, hopital_id, commentaire, note) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iisi", $patient_id, $hospital_id, $comment, $rating);
            
            if ($stmt->execute()) {
                $conn->commit();
                $message = "<p class='success'>✅ Votre avis a bien été enregistré.</p>";
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            $conn->rollback();
            $message = "<p class='error'>❌ Erreur lors de l'enregistrement : " . htmlspecialchars($e->getMessage()) . "</p>";
        } finally {
            if (isset($stmt)) $stmt->close();
        }
    }
}

// Récupération des avis
$avis = [];
$total_notes = 0;
$nb_avis = 0;

if ($hospital_id > 0) {
    $stmt = $conn->prepare("SELECT a.note, a.commentaire, a.date_avis, p.prenom, p.nom 
                          FROM avis a
                          JOIN patients p ON a.patient_id = p.patient_id
                          WHERE a.hopital_id = ?
                          ORDER BY a.date_avis DESC");
    $stmt->bind_param("i", $hospital_id);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $avis[] = $row;
            $total_notes += $row['note'];
            $nb_avis++;
        }
    }
    $stmt->close();
}

$moyenne = $nb_avis > 0 ? round($total_notes / $nb_avis, 1) : 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Avis Clients</title>
    <link rel="stylesheet" href="style20.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>

<header class="reviews-banner">
    <a href="dashboard-patient.php" class="back-to-home-btn">
        <i class="fa-solid fa-house"></i>
        <span>Retour à l'accueil</span>
    </a>
    <div class="icon"><i class="fa-solid fa-comments"></i></div>
    <h1>Avis Clients</h1>
    <p>Partagez votre expérience et découvrez ce que les gens pensent de cet hôpital</p>
</header>

<main class="reviews-container">

    <section class="review-form-column">
        <div class="card">
            <h2 class="card-title">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                Donnez votre avis
            </h2>

            <?php echo $message; ?>

            <?php if ($hospital_id > 0) : ?>
            <form method="POST" action="">
                <label for="rating">Note</label>
                <select name="rating" id="rating" required>
                    <option value="" disabled selected>Choisissez une note...</option>
                    <?php for ($i = 5; $i >= 1; $i--) : ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?> - <?php 
                            switch($i) {
                                case 5: echo 'Excellent'; break;
                                case 4: echo 'Très bon'; break;
                                case 3: echo 'Moyen'; break;
                                case 2: echo 'Médiocre'; break;
                                case 1: echo 'Mauvais'; break;
                            }
                        ?></option>
                    <?php endfor; ?>
                </select>

                <label for="comment">Votre commentaire</label>
                <textarea id="comment" name="comment" rows="5" required></textarea>

                <button type="submit" class="submit-btn">
                    <i class="fa-solid fa-paper-plane"></i> Publier mon avis
                </button>
            </form>
            <?php endif; ?>
        </div>
    </section>

    <section class="reviews-list-column">
        <h2 class="card-title">
            <i class="fa-solid fa-user-pen"></i>
            Avis des patients
        </h2>

        <div class="card rating-summary">
            <div class="rating-display">
                <span class="average-score"><?php echo $moyenne; ?></span>
                <div class="stars">
                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                        <?php if ($i <= floor($moyenne)) { ?>
                            <i class="fa-solid fa-star"></i>
                        <?php } elseif ($i - 0.5 <= $moyenne) { ?>
                            <i class="fa-solid fa-star-half-stroke"></i>
                        <?php } else { ?>
                            <i class="fa-regular fa-star"></i>
                        <?php } ?>
                    <?php } ?>
                </div>
                <span class="review-count"><?php echo $nb_avis; ?> avis</span>
            </div>
        </div>

        <?php if ($nb_avis > 0) { ?>
            <?php foreach ($avis as $a) { ?>
                    <article class="card review-item">
                    <span class="reviewer-name"><?php echo htmlspecialchars($a['prenom']) . ' ' . htmlspecialchars($a['nom']); ?></span>
                    <span class="review-date"><?php echo date('d/m/Y à H:i', strtotime($a['date_avis'])); ?></span>
                </div>
                <div class="stars">
                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                        <i class="<?php echo $i <= $a['note'] ? 'fa-solid' : 'fa-regular'; ?> fa-star"></i>
                    <?php } ?>
                </div>
                <p class="review-comment"><?php echo nl2br(htmlspecialchars($a['commentaire'])); ?></p>
            </article>
            <?php } ?>
        <?php } else { ?>
            <div class="card no-reviews">
                <i class="fa-regular fa-comment-dots"></i>
                <p>Soyez le premier à donner votre avis sur cet hôpital</p>
            </div>
        <?php } ?>
    </section>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ratingSelect = document.getElementById('rating');
    if (ratingSelect) {
        ratingSelect.addEventListener('change', function() {
            this.style.setProperty('--rating', this.value);
        });
    }
});
</script>

</body>
</html>