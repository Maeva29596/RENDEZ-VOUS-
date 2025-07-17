<?php
require_once 'db_connect.php';

// Total patients
$total_patients = 0;
$total_hopitaux = 0;
$total_rdv = 0;

$result = $conn->query("SELECT COUNT(*) FROM patients");
if ($result) {
    $total_patients = $result->fetch_row()[0];
}

$result = $conn->query("SELECT COUNT(*) FROM hopitaux");
if ($result) {
    $total_hopitaux = $result->fetch_row()[0];
}

$result = $conn->query("SELECT COUNT(*) FROM `rendez-vous`");
if ($result) {
    $total_rdv = $result->fetch_row()[0];
}

// Données mensuelles
$patients_par_mois = array_fill(1, 12, 0);
$hopitaux_par_mois = array_fill(1, 12, 0);

$res_p = $conn->query("SELECT MONTH(date_inscription) AS mois, COUNT(*) AS total FROM patients GROUP BY mois");
if ($res_p) {
    while ($row = $res_p->fetch_assoc()) {
        $patients_par_mois[(int)$row['mois']] = (int)$row['total'];
    }
}

$res_h = $conn->query("SELECT MONTH(date_inscription) AS mois, COUNT(*) AS total FROM hopitaux GROUP BY mois");
if ($res_h) {
    while ($row = $res_h->fetch_assoc()) {
        $hopitaux_par_mois[(int)$row['mois']] = (int)$row['total'];
    }
}

function coords($data) {
    $points = [];
    foreach ($data as $i => $val) {
        $x = 65 + ($i - 1) * 50;
        $y = 210 - ($val * 200 / 250);
        $points[] = "$x,$y";
    }
    return implode(' ', $points);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Médical</title>
    <link rel="stylesheet" href="style15.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
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
                    <li class="active">
                        <a href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z" /><path d="M12 5.432l8.159 8.159c.026.026.05.054.07.084v6.101A2.25 2.25 0 0117.75 22H6.25a2.25 2.25 0 01-2.25-2.25V13.675a.75.75 0 01.071-.084l8.158-8.159z" /></svg>
                            <span>Accueil</span>
                        </a>
                    </li>
                    <li>
                        <a href="patients.php">
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


            <section class="kpi-cards">
                <div class="card">
                    <div class="card-icon green">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4.5 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM14.25 8.625a3.375 3.375 0 116.75 0 3.375 3.375 0 01-6.75 0zM1.5 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63A13.067 13.067 0 0113.5 21.75H12a9 9 0 00-9-9v-.604c0-1.192.238-2.32.664-3.392a.75.75 0 01.417-1.116.75.75 0 011.116.417 5.625 5.625 0 00-1.152 4.105v.228a7.5 7.5 0 01-1.5 4.545v.004zM18.75 19.125a.75.75 0 00-1.5 0v.003l.001.12a.75.75 0 00.364.631 13.067 13.067 0 001.891 1.115h.75a9 9 0 019-9v-.604c0-1.192-.238-2.32-.664-3.392a.75.75 0 00-1.533-.417 5.625 5.625 0 011.152 4.105v.228a7.5 7.5 0 001.5 4.545v.004z" /></svg>
                    </div>
                    <div class="card-content">
                        <p>Patients Inscrits</p>
                        <span><?= $total_patients ?></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-icon blue">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M4.5 2.25a.75.75 0 000 1.5v16.5h-.75a.75.75 0 000 1.5h16.5a.75.75 0 000-1.5h-.75V3.75a.75.75 0 000-1.5h-15zM9 6a.75.75 0 000 1.5h.75a.75.75 0 000-1.5H9zm.75 3a.75.75 0 01.75-.75h3a.75.75 0 010 1.5h-3a.75.75 0 01-.75-.75zM9 12a.75.75 0 000 1.5h.75a.75.75 0 000-1.5H9zm.75 3a.75.75 0 01.75-.75h3a.75.75 0 010 1.5h-3a.75.75 0 01-.75-.75zM6 6a.75.75 0 00-.75.75v.75a.75.75 0 001.5 0v-.75A.75.75 0 006 6zm-1.5 6a.75.75 0 01.75-.75h.75a.75.75 0 010 1.5H5.25a.75.75 0 01-.75-.75zm.75 3.75a.75.75 0 000-1.5h-.75a.75.75 0 000 1.5h.75z" clip-rule="evenodd" /></svg>
                    </div>
                    <div class="card-content">
                        <p>Hôpitaux Inscrits</p>
                        <span><?= $total_hopitaux ?></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-icon purple">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M5.25 2.25a.75.75 0 00-.75.75v1.5a.75.75 0 001.5 0V3h12V2.25a.75.75 0 00-.75-.75H5.25zM19.5 4.5h-15a3 3 0 00-3 3V19.5a3 3 0 003 3h15a3 3 0 003-3V7.5a3 3 0 00-3-3zM8.25 10.5a.75.75 0 01.75.75v.01a.75.75 0 01-1.5 0v-.01a.75.75 0 01.75-.75zm1.5 0a.75.75 0 01.75.75v.01a.75.75 0 01-1.5 0v-.01a.75.75 0 01.75-.75zm3.375-1.5a.75.75 0 01.75.75v3a.75.75 0 01-1.5 0v-3a.75.75 0 01.75-.75zm-1.875.75a.75.75 0 01.75.75v.01a.75.75 0 01-1.5 0v-.01a.75.75 0 01.75-.75z" /></svg>
                    </div>
                    <div class="card-content">
                        <p>Rendez-vous</p>
                        <span><?= $total_rdv ?></span>
                    </div>
                </div>
            </section>

            <section class="chart-container">
                <div class="chart-header">
                    <h2>Évolution des Inscriptions</h2>
                    <div class="legend">
                        <div class="legend-item">
                            <span class="legend-color" style="background-color: #3b82f6;"></span>
                            Patients
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background-color: #22c55e;"></span>
                            Hôpitaux
                        </div>
                    </div>
                </div>
                <div class="chart-area">
                    <svg width="100%" height="100%" viewBox="0 0 700 250" preserveAspectRatio="xMidYMid meet">
                        <g class="grid" stroke="#e5e7eb" stroke-width="1">
                            <line x1="50" y1="210" x2="680" y2="210"></line> <line x1="50" y1="10" x2="50" y2="210"></line> <line x1="50" y1="10" x2="680" y2="10"></line>
                            <line x1="50" y1="60" x2="680" y2="60"></line>
                            <line x1="50" y1="110" x2="680" y2="110"></line>
                            <line x1="50" y1="160" x2="680" y2="160"></line>
                        </g>

                        <g class="labels y-labels" fill="#6b7280" font-size="12" font-family="Poppins, sans-serif">
                            <text x="40" y="215" text-anchor="end">0</text>
                            <text x="40" y="165" text-anchor="end">50</text>
                            <text x="40" y="115" text-anchor="end">100</text>
                            <text x="40" y="65" text-anchor="end">150</text>
                            <text x="40" y="15" text-anchor="end">250</text>
                        </g>

                        <g class="labels x-labels" fill="#6b7280" font-size="12" font-family="Poppins, sans-serif">
                             <text x="65" y="230">Jan</text>
                             <text x="115" y="230">Fév</text>
                             <text x="170" y="230">Mar</text>
                             <text x="225" y="230">Avr</text>
                             <text x="280" y="230">Mai</text>
                             <text x="330" y="230">Jun</text>
                             <text x="385" y="230">Jul</text>
                             <text x="440" y="230">Aoû</text>
                             <text x="495" y="230">Sep</text>
                             <text x="550" y="230">Oct</text>
                             <text x="605" y="230">Nov</text>
                             <text x="655" y="230">Déc</text>
                        </g>

                        <polyline fill="rgba(34, 197, 94, 0.1)" stroke="#22c55e" stroke-width="2" points="<?= coords($hopitaux_par_mois) ?>"/>
                        <polyline fill="none" stroke="#3b82f6" stroke-width="2" points="<?= coords($patients_par_mois) ?>"/>

                    </svg>
                </div>
            </section>
        </main>
    </div>
</body>
</html>