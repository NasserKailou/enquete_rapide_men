<?php
/**
 * Page de gestion des années scolaires
 * Enquête Rapide Rentrée Scolaire
 */

require_once 'config.php';

// Vérifier que l'utilisateur est connecté et est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Années Scolaires | Enquête Rapide</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
            padding: 1.5rem 0;
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

        .header h1 {
            font-size: 1.8rem;
            font-weight: 700;
        }

        .btn-back {
            background: white;
            color: var(--primary-color);
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 255, 255, 0.3);
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        /* Navigation */
        .nav-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .nav-tab {
            padding: 0.8rem 1.5rem;
            background: white;
            border-radius: 10px;
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
        }

        .nav-tab:hover {
            transform: translateY(-2px);
            background: var(--light-green);
            color: var(--primary-color);
        }

        .nav-tab.active {
            background: var(--primary-color);
            color: white;
        }

        /* Card */
        .card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
        }

        .card-header h2 {
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--dark-green));
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(12, 159, 87, 0.4);
        }

        /* Table */
        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: var(--light-green);
        }

        th {
            padding: 1rem;
            text-align: left;
            color: var(--primary-color);
            font-weight: 600;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        .badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-active {
            background: #e8f5e9;
            color: #4caf50;
            border: 2px solid #4caf50;
        }

        .badge-inactive {
            background: #f5f5f5;
            color: #9e9e9e;
        }

        .badge-open {
            background: #e3f2fd;
            color: #1976d2;
        }

        .badge-closed {
            background: #ffebee;
            color: #f44336;
        }

        .badge-info {
            background: #fff3e0;
            color: var(--orange);
        }

        .btn-action {
            padding: 0.4rem 0.8rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            margin-right: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-activate {
            background: #e8f5e9;
            color: #4caf50;
        }

        .btn-activate:hover {
            background: #4caf50;
            color: white;
        }

        .btn-toggle {
            background: #e3f2fd;
            color: #1976d2;
        }

        .btn-toggle:hover {
            background: #1976d2;
            color: white;
        }

        .btn-edit {
            background: #fff3e0;
            color: var(--orange);
        }

        .btn-edit:hover {
            background: var(--orange);
            color: white;
        }

        .btn-delete {
            background: #ffebee;
            color: #f44336;
        }

        .btn-delete:hover {
            background: #f44336;
            color: white;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            margin-bottom: 2rem;
        }

        .modal-header h2 {
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.9rem;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(12, 159, 87, 0.1);
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-submit {
            flex: 1;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary-color), var(--dark-green));
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(12, 159, 87, 0.4);
        }

        .btn-cancel {
            flex: 1;
            padding: 1rem;
            background: transparent;
            color: var(--text-light);
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            border-color: var(--orange);
            color: var(--orange);
        }

        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            display: none;
        }

        .alert.show {
            display: block;
        }

        .alert-success {
            background: #e8f5e9;
            color: #4caf50;
            border-left: 4px solid #4caf50;
        }

        .alert-error {
            background: #ffebee;
            color: #f44336;
            border-left: 4px solid #f44336;
        }

        .loading {
            text-align: center;
            padding: 2rem;
            color: var(--text-light);
        }

        .no-data {
            text-align: center;
            padding: 3rem;
            color: var(--text-light);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin: 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--light-green);
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .stat-label {
            color: var(--text-light);
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <h1><i class="fas fa-calendar-alt"></i> Gestion des Années Scolaires</h1>
            <a href="index.html" class="btn-back">
                <i class="fas fa-arrow-left"></i> Retour à l'accueil
            </a>
        </div>
    </div>

    <!-- Container -->
    <div class="container">
        <!-- Navigation Tabs -->
        <div class="nav-tabs">
            <a href="gestion_annees.php" class="nav-tab active">
                <i class="fas fa-calendar-alt"></i> Années Scolaires
            </a>
            <a href="gestion_users.php" class="nav-tab">
                <i class="fas fa-users-cog"></i> Utilisateurs
            </a>
        </div>

        <!-- Alerts -->
        <div class="alert alert-success" id="successAlert"></div>
        <div class="alert alert-error" id="errorAlert"></div>

        <!-- Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-calendar"></i> Liste des Années Scolaires</h2>
                <button class="btn-primary" onclick="showAddModal()">
                    <i class="fas fa-plus"></i> Ajouter une année scolaire
                </button>
            </div>

            <div class="table-container">
                <div class="loading" id="loading">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p>Chargement des années scolaires...</p>
                </div>
                <table id="anneesTable" style="display: none;">
                    <thead>
                        <tr>
                            <th>Année Scolaire</th>
                            <th>Date Début</th>
                            <th>Date Fin</th>
                            <th>Statut</th>
                            <th>Collecte</th>
                            <th>Établissements</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="anneesTableBody">
                    </tbody>
                </table>
                <div class="no-data" id="noData" style="display: none;">
                    <i class="fas fa-inbox fa-3x"></i>
                    <p>Aucune année scolaire trouvée</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal" id="anneeModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle"><i class="fas fa-calendar-plus"></i> Ajouter une année scolaire</h2>
            </div>
            <form id="anneeForm" onsubmit="handleSubmit(event)">
                <input type="hidden" id="anneeId" name="id">
                
                <div class="form-group">
                    <label for="libelle">Libellé (ex: 2025-2026) *</label>
                    <input type="text" id="libelle" name="libelle" required placeholder="2025-2026">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="date_debut">Date de début *</label>
                        <input type="date" id="date_debut" name="date_debut" required>
                    </div>

                    <div class="form-group">
                        <label for="date_fin">Date de fin *</label>
                        <input type="date" id="date_fin" name="date_fin" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="3" placeholder="Description de l'année scolaire..."></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="active" name="active" value="1">
                            <label for="active">Année active</label>
                        </div>
                        <small style="color: var(--text-light);">Une seule année peut être active à la fois</small>
                    </div>

                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="collecte_ouverte" name="collecte_ouverte" value="1">
                            <label for="collecte_ouverte">Collecte ouverte</label>
                        </div>
                        <small style="color: var(--text-light);">Autoriser la saisie de données</small>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                    <button type="button" class="btn-cancel" onclick="closeModal()">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let isEditMode = false;

        // Charger les années scolaires
        async function loadAnnees() {
            try {
                document.getElementById('loading').style.display = 'block';
                document.getElementById('anneesTable').style.display = 'none';
                document.getElementById('noData').style.display = 'none';

                const response = await fetch('api/annees_scolaires.php');
                const result = await response.json();

                document.getElementById('loading').style.display = 'none';

                if (result.success && result.data.length > 0) {
                    displayAnnees(result.data);
                    document.getElementById('anneesTable').style.display = 'table';
                } else {
                    document.getElementById('noData').style.display = 'block';
                }
            } catch (error) {
                console.error('Erreur:', error);
                document.getElementById('loading').style.display = 'none';
                showError('Erreur lors du chargement des années scolaires');
            }
        }

        // Afficher les années dans le tableau
        function displayAnnees(annees) {
            const tbody = document.getElementById('anneesTableBody');
            tbody.innerHTML = '';

            annees.forEach(annee => {
                const tr = document.createElement('tr');
                
                const dateDebut = new Date(annee.date_debut).toLocaleDateString('fr-FR');
                const dateFin = new Date(annee.date_fin).toLocaleDateString('fr-FR');
                
                const statutBadge = annee.active == 1 
                    ? '<span class="badge badge-active"><i class="fas fa-check-circle"></i> ACTIVE</span>'
                    : '<span class="badge badge-inactive">Inactive</span>';
                
                const collecteBadge = annee.collecte_ouverte == 1
                    ? '<span class="badge badge-open"><i class="fas fa-unlock"></i> Ouverte</span>'
                    : '<span class="badge badge-closed"><i class="fas fa-lock"></i> Fermée</span>';

                const nbEtablissements = annee.nb_etablissements || 0;

                tr.innerHTML = `
                    <td><strong>${annee.libelle}</strong></td>
                    <td>${dateDebut}</td>
                    <td>${dateFin}</td>
                    <td>${statutBadge}</td>
                    <td>${collecteBadge}</td>
                    <td><span class="badge badge-info">${nbEtablissements} établissement(s)</span></td>
                    <td>${annee.description || '-'}</td>
                    <td>
                        ${annee.active == 0 ? `
                            <button class="btn-action btn-activate" onclick="activerAnnee(${annee.id}, '${annee.libelle}')" title="Activer cette année">
                                <i class="fas fa-power-off"></i> Activer
                            </button>
                        ` : ''}
                        <button class="btn-action btn-toggle" onclick="toggleCollecte(${annee.id}, ${annee.collecte_ouverte == 1 ? 0 : 1}, '${annee.libelle}')" title="${annee.collecte_ouverte == 1 ? 'Fermer' : 'Ouvrir'} la collecte">
                            <i class="fas fa-${annee.collecte_ouverte == 1 ? 'lock' : 'unlock'}"></i> ${annee.collecte_ouverte == 1 ? 'Fermer' : 'Ouvrir'}
                        </button>
                        <button class="btn-action btn-edit" onclick="editAnnee(${annee.id})" title="Modifier">
                            <i class="fas fa-edit"></i> Modifier
                        </button>
                        ${annee.active == 0 && nbEtablissements == 0 ? `
                            <button class="btn-action btn-delete" onclick="deleteAnnee(${annee.id}, '${annee.libelle}')" title="Supprimer">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        ` : ''}
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        // Afficher le modal d'ajout
        function showAddModal() {
            isEditMode = false;
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-calendar-plus"></i> Ajouter une année scolaire';
            document.getElementById('anneeForm').reset();
            document.getElementById('anneeId').value = '';
            document.getElementById('anneeModal').classList.add('show');
        }

        // Éditer une année scolaire
        async function editAnnee(id) {
            try {
                const response = await fetch(`api/annees_scolaires.php?id=${id}`);
                const result = await response.json();

                if (result.success) {
                    isEditMode = true;
                    const annee = result.data;
                    
                    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-calendar-edit"></i> Modifier l\'année scolaire';
                    document.getElementById('anneeId').value = annee.id;
                    document.getElementById('libelle').value = annee.libelle;
                    document.getElementById('date_debut').value = annee.date_debut;
                    document.getElementById('date_fin').value = annee.date_fin;
                    document.getElementById('description').value = annee.description || '';
                    document.getElementById('active').checked = annee.active == 1;
                    document.getElementById('collecte_ouverte').checked = annee.collecte_ouverte == 1;

                    document.getElementById('anneeModal').classList.add('show');
                } else {
                    showError('Erreur lors du chargement de l\'année scolaire');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showError('Erreur lors du chargement de l\'année scolaire');
            }
        }

        // Activer une année scolaire
        async function activerAnnee(id, libelle) {
            if (!confirm(`Voulez-vous activer l'année scolaire "${libelle}" ?\n\nCela désactivera toutes les autres années.`)) {
                return;
            }

            try {
                const response = await fetch('api/annees_scolaires.php', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ 
                        id: id,
                        action: 'activer'
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showSuccess(`Année scolaire "${libelle}" activée avec succès`);
                    loadAnnees();
                } else {
                    showError(result.error || 'Erreur lors de l\'activation');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showError('Erreur lors de l\'activation');
            }
        }

        // Ouvrir/Fermer la collecte
        async function toggleCollecte(id, nouvelEtat, libelle) {
            const action = nouvelEtat ? 'ouvrir' : 'fermer';
            
            if (!confirm(`Voulez-vous ${action} la collecte pour l'année "${libelle}" ?`)) {
                return;
            }

            try {
                const response = await fetch('api/annees_scolaires.php', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ 
                        id: id,
                        action: 'toggle_collecte',
                        collecte_ouverte: nouvelEtat
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showSuccess(`Collecte ${nouvelEtat ? 'ouverte' : 'fermée'} pour l'année "${libelle}"`);
                    loadAnnees();
                } else {
                    showError(result.error || 'Erreur lors de la modification');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showError('Erreur lors de la modification');
            }
        }

        // Supprimer une année scolaire
        async function deleteAnnee(id, libelle) {
            if (!confirm(`Êtes-vous sûr de vouloir supprimer l'année scolaire "${libelle}" ?\n\nCette action est irréversible.`)) {
                return;
            }

            try {
                const response = await fetch('api/annees_scolaires.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                });

                const result = await response.json();

                if (result.success) {
                    showSuccess('Année scolaire supprimée avec succès');
                    loadAnnees();
                } else {
                    showError(result.error || 'Erreur lors de la suppression');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showError('Erreur lors de la suppression');
            }
        }

        // Gérer la soumission du formulaire
        async function handleSubmit(event) {
            event.preventDefault();

            const formData = new FormData(event.target);
            const data = {
                libelle: formData.get('libelle'),
                date_debut: formData.get('date_debut'),
                date_fin: formData.get('date_fin'),
                description: formData.get('description'),
                active: formData.get('active') ? 1 : 0,
                collecte_ouverte: formData.get('collecte_ouverte') ? 1 : 0
            };

            if (isEditMode) {
                data.id = document.getElementById('anneeId').value;
            }

            try {
                const method = isEditMode ? 'PUT' : 'POST';
                const response = await fetch('api/annees_scolaires.php', {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    showSuccess(isEditMode ? 'Année scolaire modifiée avec succès' : 'Année scolaire créée avec succès');
                    closeModal();
                    loadAnnees();
                } else {
                    showError(result.error || 'Erreur lors de l\'enregistrement');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showError('Erreur lors de l\'enregistrement');
            }
        }

        // Fermer le modal
        function closeModal() {
            document.getElementById('anneeModal').classList.remove('show');
            document.getElementById('anneeForm').reset();
        }

        // Afficher un message de succès
        function showSuccess(message) {
            const alert = document.getElementById('successAlert');
            alert.textContent = message;
            alert.classList.add('show');
            setTimeout(() => {
                alert.classList.remove('show');
            }, 5000);
        }

        // Afficher un message d'erreur
        function showError(message) {
            const alert = document.getElementById('errorAlert');
            alert.textContent = message;
            alert.classList.add('show');
            setTimeout(() => {
                alert.classList.remove('show');
            }, 5000);
        }

        // Charger les années au chargement de la page
        window.addEventListener('DOMContentLoaded', () => {
            loadAnnees();
        });
    </script>
    
    <!-- Script de gestion de l'année scolaire active -->
    <script src="js/annee_scolaire.js"></script>
</body>
</html>
