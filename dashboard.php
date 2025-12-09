<?php
require_once 'config.php';

$db = Database::getInstance()->getConnection();

// Récupérer les statistiques globales
$stats = [];

// Total établissements par cycle
$sql_stats = "SELECT 
    cycle,
    COUNT(*) as total,
    SUM(CASE WHEN statut = 'Public' THEN 1 ELSE 0 END) as publics,
    SUM(CASE WHEN statut = 'Privé' THEN 1 ELSE 0 END) as prives,
    SUM(CASE WHEN zone = 'Rurale' THEN 1 ELSE 0 END) as rural,
    SUM(CASE WHEN zone = 'Urbaine' THEN 1 ELSE 0 END) as urbain
FROM etablissements
GROUP BY cycle";

$stats_cycles = $db->query($sql_stats)->fetchAll();

// Effectifs totaux
$sql_effectifs = "
    SELECT 
        'prescolaire' as cycle,
        SUM(garcons) as garcons,
        SUM(filles) as filles
    FROM effectifs_prescolaire
    UNION ALL
    SELECT 
        'primaire' as cycle,
        SUM(garcons) as garcons,
        SUM(filles) as filles
    FROM effectifs_primaire
    UNION ALL
    SELECT 
        'secondaire' as cycle,
        SUM(garcons) as garcons,
        SUM(filles) as filles
    FROM effectifs_secondaire
";

$effectifs_totaux = $db->query($sql_effectifs)->fetchAll();

// Personnel enseignant total
$sql_personnel = "SELECT cycle, SUM(total) as total_enseignants
                  FROM personnel_enseignant pe
                  JOIN etablissements e ON pe.etablissement_id = e.id
                  GROUP BY cycle";
$personnel_totaux = $db->query($sql_personnel)->fetchAll();

// Statistiques par région
$sql_regions = "SELECT 
    region,
    cycle,
    COUNT(*) as nb_etablissements
FROM etablissements
GROUP BY region, cycle
ORDER BY region, cycle";
$stats_regions = $db->query($sql_regions)->fetchAll();

// Dernières enquêtes soumises
$sql_recentes = "SELECT * FROM etablissements 
                 ORDER BY created_at DESC 
                 LIMIT 10";
$enquetes_recentes = $db->query($sql_recentes)->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Enquête Rapide 2025-2026</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #0C9F57;
            --secondary-color: #E25822;
            --dark-green: #0A7A43;
            --light-green: #E8F5E9;
            --orange: #E25822;
            --light-orange: #FFF3E0;
            --text-dark: #2C3E50;
            --text-light: #7F8C8D;
            --border-color: #E0E0E0;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            color: var(--text-dark);
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-green) 100%);
            color: white;
            padding: 2rem 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .logo-niger {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .logo-niger i {
            font-size: 2rem;
            color: var(--primary-color);
        }

        .header-titles h1 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
        }

        .header-titles p {
            font-size: 0.85rem;
            opacity: 0.95;
        }

        .header-actions a {
            background: white;
            color: var(--primary-color);
            padding: 0.7rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .header-actions a:hover {
            transform: translateY(-2px);
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: var(--primary-color);
        }

        .stat-card.orange::before {
            background: var(--orange);
        }

        .stat-card.blue::before {
            background: #2196F3;
        }

        .stat-card.purple::before {
            background: #9C27B0;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: var(--light-green);
            color: var(--primary-color);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

        .stat-card.orange .stat-icon {
            background: var(--light-orange);
            color: var(--orange);
        }

        .stat-card.blue .stat-icon {
            background: #E3F2FD;
            color: #2196F3;
        }

        .stat-card.purple .stat-icon {
            background: #F3E5F5;
            color: #9C27B0;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.3rem;
        }

        .stat-label {
            color: var(--text-light);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Charts Section */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .chart-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: var(--shadow);
        }

        .chart-card h2 {
            color: var(--primary-color);
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        /* Table */
        .table-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .table-card h2 {
            color: var(--primary-color);
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead {
            background: var(--light-green);
            color: var(--dark-green);
        }

        .data-table th,
        .data-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .data-table th {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .data-table tbody tr:hover {
            background: var(--light-green);
        }

        .badge {
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-prescolaire {
            background: var(--light-green);
            color: var(--dark-green);
        }

        .badge-primaire {
            background: var(--light-orange);
            color: var(--orange);
        }

        .badge-secondaire {
            background: #E3F2FD;
            color: #2196F3;
        }

        .btn-view {
            padding: 0.4rem 1rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: transform 0.3s ease;
        }

        .btn-view:hover {
            transform: translateY(-2px);
        }

        /* Filters */
        .filters {
            background: white;
            border-radius: 15px;
            padding: 1.5rem 2rem;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            display: flex;
            gap: 1.5rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .filter-group label {
            font-size: 0.85rem;
            color: var(--text-light);
            font-weight: 500;
        }

        .filter-group select {
            padding: 0.6rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .header-content {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <div class="logo-niger">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="header-titles">
                    <h1>Tableau de Bord - Enquête Rapide</h1>
                    <p>Année Scolaire 2025-2026</p>
                </div>
            </div>
            <div class="header-actions">
                <a href="index.html">
                    <i class="fas fa-home"></i> Accueil
                </a>
            </div>
        </div>
    </div>

    <!-- Container -->
    <div class="container">
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-school"></i>
                </div>
                <div class="stat-number">
                    <?php 
                    $total_etab = array_sum(array_column($stats_cycles, 'total'));
                    echo number_format($total_etab, 0, ',', ' ');
                    ?>
                </div>
                <div class="stat-label">Total Établissements</div>
            </div>

            <div class="stat-card orange">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number">
                    <?php 
                    $total_eleves = array_sum(array_map(function($item) {
                        return $item['garcons'] + $item['filles'];
                    }, $effectifs_totaux));
                    echo number_format($total_eleves, 0, ',', ' ');
                    ?>
                </div>
                <div class="stat-label">Total Élèves</div>
            </div>

            <div class="stat-card blue">
                <div class="stat-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-number">
                    <?php 
                    $total_enseignants = array_sum(array_column($personnel_totaux, 'total_enseignants'));
                    echo number_format($total_enseignants, 0, ',', ' ');
                    ?>
                </div>
                <div class="stat-label">Total Enseignants</div>
            </div>

            <div class="stat-card purple">
                <div class="stat-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <div class="stat-number">8</div>
                <div class="stat-label">Régions Couvertes</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters">
            <div class="filter-group">
                <label>Région</label>
                <select id="filter-region">
                    <option value="">Toutes les régions</option>
                    <?php foreach($regions_niger as $region): ?>
                        <option value="<?php echo $region; ?>"><?php echo $region; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label>Cycle</label>
                <select id="filter-cycle">
                    <option value="">Tous les cycles</option>
                    <option value="prescolaire">Préscolaire</option>
                    <option value="primaire">Primaire</option>
                    <option value="secondaire">Secondaire</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Statut</label>
                <select id="filter-statut">
                    <option value="">Tous les statuts</option>
                    <option value="Public">Public</option>
                    <option value="Privé">Privé</option>
                    <option value="Communautaire">Communautaire</option>
                </select>
            </div>
        </div>

        <!-- Charts -->
        <div class="dashboard-grid">
            <div class="chart-card">
                <h2><i class="fas fa-chart-pie"></i> Répartition par Cycle</h2>
                <div class="chart-container">
                    <canvas id="cycleChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <h2><i class="fas fa-chart-bar"></i> Répartition par Statut</h2>
                <div class="chart-container">
                    <canvas id="statutChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <h2><i class="fas fa-venus-mars"></i> Répartition Élèves par Genre</h2>
                <div class="chart-container">
                    <canvas id="genreChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <h2><i class="fas fa-map-marker-alt"></i> Établissements par Région</h2>
                <div class="chart-container">
                    <canvas id="regionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Submissions Table -->
        <div class="table-card">
            <h2><i class="fas fa-clock"></i> Dernières Enquêtes Soumises</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Nom Établissement</th>
                        <th>Cycle</th>
                        <th>Région</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($enquetes_recentes as $enquete): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($enquete['code_etablissement']); ?></strong></td>
                        <td><?php echo htmlspecialchars($enquete['nom_etablissement']); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $enquete['cycle']; ?>">
                                <?php echo ucfirst($enquete['cycle']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($enquete['region']); ?></td>
                        <td><?php echo htmlspecialchars($enquete['statut']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($enquete['created_at'])); ?></td>
                        <td>
                            <button class="btn-view" onclick="viewDetails(<?php echo $enquete['id']; ?>)">
                                <i class="fas fa-eye"></i> Voir
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Données pour les graphiques
        const cycleData = <?php echo json_encode($stats_cycles); ?>;
        const effectifsData = <?php echo json_encode($effectifs_totaux); ?>;
        const regionsData = <?php echo json_encode($stats_regions); ?>;

        // Graphique par cycle
        const ctxCycle = document.getElementById('cycleChart').getContext('2d');
        new Chart(ctxCycle, {
            type: 'doughnut',
            data: {
                labels: cycleData.map(d => d.cycle.charAt(0).toUpperCase() + d.cycle.slice(1)),
                datasets: [{
                    data: cycleData.map(d => d.total),
                    backgroundColor: ['#0C9F57', '#E25822', '#2196F3'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Graphique par statut
        const ctxStatut = document.getElementById('statutChart').getContext('2d');
        const publicsTotal = cycleData.reduce((sum, d) => sum + parseInt(d.publics), 0);
        const privesTotal = cycleData.reduce((sum, d) => sum + parseInt(d.prives), 0);

        new Chart(ctxStatut, {
            type: 'bar',
            data: {
                labels: ['Public', 'Privé'],
                datasets: [{
                    label: 'Nombre d\'établissements',
                    data: [publicsTotal, privesTotal],
                    backgroundColor: ['#0C9F57', '#E25822'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Graphique Genre
        const ctxGenre = document.getElementById('genreChart').getContext('2d');
        new Chart(ctxGenre, {
            type: 'bar',
            data: {
                labels: effectifsData.map(d => d.cycle.charAt(0).toUpperCase() + d.cycle.slice(1)),
                datasets: [
                    {
                        label: 'Garçons',
                        data: effectifsData.map(d => d.garcons),
                        backgroundColor: '#2196F3'
                    },
                    {
                        label: 'Filles',
                        data: effectifsData.map(d => d.filles),
                        backgroundColor: '#E91E63'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Graphique Régions
        const ctxRegion = document.getElementById('regionChart').getContext('2d');
        const regions = [...new Set(regionsData.map(d => d.region))];
        const regionTotals = regions.map(region => 
            regionsData.filter(d => d.region === region).reduce((sum, d) => sum + parseInt(d.nb_etablissements), 0)
        );

        new Chart(ctxRegion, {
            type: 'bar',
            data: {
                labels: regions,
                datasets: [{
                    label: 'Établissements',
                    data: regionTotals,
                    backgroundColor: '#0C9F57'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });

        function viewDetails(id) {
            window.location.href = 'view_details.php?id=' + id;
        }
    </script>
</body>
</html>
