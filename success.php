<?php
require_once 'config.php';

$etablissement_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($etablissement_id > 0) {
    $db = Database::getInstance()->getConnection();
    $sql = "SELECT * FROM etablissements WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute(['id' => $etablissement_id]);
    $etablissement = $stmt->fetch();
} else {
    header('Location: index.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enquête Soumise avec Succès</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0C9F57 0%, #0A7A43 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .success-container {
            background: white;
            border-radius: 30px;
            padding: 4rem 3rem;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
            background: linear-gradient(135deg, #27AE60, #229954);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: scaleIn 0.5s ease 0.3s both;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }

        .success-icon i {
            font-size: 4rem;
            color: white;
        }

        h1 {
            color: #27AE60;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .success-message {
            color: #7F8C8D;
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 2rem;
        }

        .info-box {
            background: #E8F5E9;
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem 0;
            text-align: left;
        }

        .info-box h3 {
            color: #0C9F57;
            font-size: 1.2rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid #C8E6C9;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #7F8C8D;
            font-weight: 500;
        }

        .info-value {
            color: #2C3E50;
            font-weight: 600;
        }

        .code-display {
            background: linear-gradient(135deg, #0C9F57, #0A7A43);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 2px;
            margin: 2rem 0;
            font-family: 'Courier New', monospace;
        }

        .actions {
            display: flex;
            gap: 1rem;
            margin-top: 2.5rem;
            flex-wrap: wrap;
        }

        .btn {
            flex: 1;
            padding: 1rem 2rem;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            min-width: 200px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0C9F57, #0A7A43);
            color: white;
            box-shadow: 0 4px 15px rgba(12, 159, 87, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(12, 159, 87, 0.4);
        }

        .btn-secondary {
            background: white;
            color: #0C9F57;
            border: 2px solid #0C9F57;
        }

        .btn-secondary:hover {
            background: #E8F5E9;
        }

        .print-btn {
            background: #E25822;
            color: white;
            box-shadow: 0 4px 15px rgba(226, 88, 34, 0.3);
        }

        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(226, 88, 34, 0.4);
        }

        @media print {
            body {
                background: white;
            }
            .actions {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .success-container {
                padding: 3rem 2rem;
            }

            h1 {
                font-size: 2rem;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }

        .confetti {
            position: fixed;
            width: 10px;
            height: 10px;
            background: #0C9F57;
            position: absolute;
            animation: confetti-fall 3s linear infinite;
        }

        @keyframes confetti-fall {
            to {
                transform: translateY(100vh) rotate(360deg);
                opacity: 0;
            }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>

        <h1>Enquête Soumise avec Succès !</h1>

        <p class="success-message">
            Votre enquête rapide pour la rentrée scolaire 2025-2026 a été enregistrée avec succès 
            dans notre système. Un reçu de confirmation vous a été attribué.
        </p>

        <?php if ($etablissement): ?>
        <div class="code-display">
            <i class="fas fa-barcode"></i> <?php echo htmlspecialchars($etablissement['code_etablissement']); ?>
        </div>

        <div class="info-box">
            <h3><i class="fas fa-info-circle"></i> Informations de l'établissement</h3>
            
            <div class="info-item">
                <span class="info-label">Nom :</span>
                <span class="info-value"><?php echo htmlspecialchars($etablissement['nom_etablissement']); ?></span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Cycle :</span>
                <span class="info-value"><?php echo ucfirst($etablissement['cycle']); ?></span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Région :</span>
                <span class="info-value"><?php echo htmlspecialchars($etablissement['region']); ?></span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Département :</span>
                <span class="info-value"><?php echo htmlspecialchars($etablissement['departement']); ?></span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Date de soumission :</span>
                <span class="info-value"><?php echo date('d/m/Y à H:i', strtotime($etablissement['created_at'])); ?></span>
            </div>
        </div>
        <?php endif; ?>

        <div style="background: #FFF3E0; padding: 1.5rem; border-radius: 12px; margin: 2rem 0;">
            <p style="color: #E25822; font-weight: 600; margin-bottom: 0.5rem;">
                <i class="fas fa-exclamation-triangle"></i> Important
            </p>
            <p style="color: #7F8C8D; font-size: 0.9rem; line-height: 1.6;">
                Conservez ce code de confirmation. Il vous sera nécessaire pour toute demande de 
                modification ou de consultation ultérieure de votre enquête.
            </p>
        </div>

        <div class="actions">
            <button onclick="window.print()" class="btn print-btn">
                <i class="fas fa-print"></i> Imprimer le reçu
            </button>
            
            <a href="dashboard.php" class="btn btn-primary">
                <i class="fas fa-chart-line"></i> Voir le tableau de bord
            </a>
            
            <a href="index.html" class="btn btn-secondary">
                <i class="fas fa-home"></i> Retour à l'accueil
            </a>
        </div>
    </div>

    <script>
        // Confetti animation
        function createConfetti() {
            const colors = ['#0C9F57', '#E25822', '#2196F3', '#FFC107'];
            for (let i = 0; i < 50; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + '%';
                confetti.style.animationDelay = Math.random() * 3 + 's';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                document.body.appendChild(confetti);
                
                setTimeout(() => confetti.remove(), 3000);
            }
        }

        // Lancer les confetti au chargement
        window.addEventListener('DOMContentLoaded', createConfetti);
    </script>
</body>
</html>
