<?php
require_once 'config.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

$cycle = isset($_GET['cycle']) ? securise($_GET['cycle']) : '';
$allowed_cycles = ['prescolaire', 'primaire', 'secondaire'];

if (!in_array($cycle, $allowed_cycles)) {
    header('Location: index.html');
    exit;
}

// Charger les données de configuration
$db = Database::getInstance()->getConnection();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire <?php echo ucfirst($cycle); ?> - Enquête Rapide 2025-2026</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/formulaire.css">
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <div class="back-button">
                <a href="index.html">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
            <div class="header-top">
                <div class="logo-niger">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="header-titles">
                    <h1>Formulaire d'Enquête - Cycle <?php echo ucfirst($cycle); ?></h1>
                    <p>Enquête Rapide sur la Rentrée Scolaire 2025-2026</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="progress-container">
        <div class="progress-bar">
            <div class="progress-fill" id="progressFill"></div>
        </div>
        <div class="progress-text">
            <span id="progressText">Section 1 / 5</span>
            <span id="progressPercent">0%</span>
        </div>
    </div>

    <!-- Form Container -->
    <div class="container">
        <div class="form-card">
            <form id="enqueteForm" method="POST" action="submit_form.php">
                <input type="hidden" name="cycle" value="<?php echo $cycle; ?>">
                
                <?php
                // Charger le template de formulaire approprié
                $template_file = "templates/form_{$cycle}.php";
                if (file_exists($template_file)) {
                    include $template_file;
                } else {
                    echo "<p class='error'>Template de formulaire non trouvé.</p>";
                }
                ?>

                <!-- Navigation Buttons -->
                <div class="form-navigation">
                    <button type="button" class="btn btn-secondary" id="prevBtn" onclick="changeSection(-1)">
                        <i class="fas fa-chevron-left"></i> Précédent
                    </button>
                    <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeSection(1)">
                        Suivant <i class="fas fa-chevron-right"></i>
                    </button>
                    <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                        <i class="fas fa-check"></i> Soumettre l'enquête
                    </button>
                </div>
            </form>
        </div>

        <!-- Help Card -->
        <div class="help-card">
            <h3><i class="fas fa-info-circle"></i> Aide</h3>
            <p>Les champs marqués d'un astérisque (<span style="color: #E25822;">*</span>) sont obligatoires.</p>
            <p>Vos données sont automatiquement sauvegardées pendant la saisie.</p>
            <div class="help-contact">
                <i class="fas fa-phone"></i> Support : +227 XX XX XX XX
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>© 2025 Ministère de l'Education Nationale du Niger - DS/PTICE</p>
    </div>

    <script src="js/formulaire.js"></script>
    <script>
        const cycle = '<?php echo $cycle; ?>';
    </script>
</body>
</html>
