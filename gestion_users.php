<?php
/**
 * Page de gestion des utilisateurs
 * Enquête Rapide Rentrée Scolaire 2025-2026
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
    <title>Gestion des Utilisateurs | Enquête Rapide</title>
    
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

        .badge-admin {
            background: #e3f2fd;
            color: #1976d2;
        }

        .badge-saisie {
            background: var(--light-green);
            color: var(--primary-color);
        }

        .badge-consultation {
            background: #fff3e0;
            color: var(--orange);
        }

        .badge-actif {
            background: #e8f5e9;
            color: #4caf50;
        }

        .badge-inactif {
            background: #ffebee;
            color: #f44336;
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

        .btn-edit {
            background: #e3f2fd;
            color: #1976d2;
        }

        .btn-edit:hover {
            background: #1976d2;
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
        .form-group select {
            width: 100%;
            padding: 0.9rem;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
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
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <h1><i class="fas fa-users-cog"></i> Gestion des Utilisateurs</h1>
            <a href="index.html" class="btn-back">
                <i class="fas fa-arrow-left"></i> Retour à l'accueil
            </a>
        </div>
    </div>

    <!-- Container -->
    <div class="container">
        <!-- Navigation Tabs -->
        <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
            <a href="gestion_users.php" style="padding: 0.8rem 1.5rem; background: var(--primary-color); color: white; border-radius: 10px; text-decoration: none; font-weight: 600; box-shadow: var(--shadow);">
                <i class="fas fa-users-cog"></i> Utilisateurs
            </a>
            <a href="gestion_annees.php" style="padding: 0.8rem 1.5rem; background: white; color: var(--text-dark); border-radius: 10px; text-decoration: none; font-weight: 600; box-shadow: var(--shadow); transition: all 0.3s ease;">
                <i class="fas fa-calendar-alt"></i> Années Scolaires
            </a>
        </div>

        <!-- Alerts -->
        <div class="alert alert-success" id="successAlert"></div>
        <div class="alert alert-error" id="errorAlert"></div>

        <!-- Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-users"></i> Liste des Utilisateurs</h2>
                <button class="btn-primary" onclick="showAddModal()">
                    <i class="fas fa-plus"></i> Ajouter un utilisateur
                </button>
            </div>

            <div class="table-container">
                <div class="loading" id="loading">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p>Chargement des utilisateurs...</p>
                </div>
                <table id="usersTable" style="display: none;">
                    <thead>
                        <tr>
                            <th>Nom d'utilisateur</th>
                            <th>Nom complet</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Région</th>
                            <th>Statut</th>
                            <th>Dernière connexion</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                    </tbody>
                </table>
                <div class="no-data" id="noData" style="display: none;">
                    <i class="fas fa-inbox fa-3x"></i>
                    <p>Aucun utilisateur trouvé</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit User Modal -->
    <div class="modal" id="userModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle"><i class="fas fa-user-plus"></i> Ajouter un utilisateur</h2>
            </div>
            <form id="userForm" onsubmit="handleSubmit(event)">
                <input type="hidden" id="userId" name="id">
                
                <div class="form-group">
                    <label for="username">Nom d'utilisateur *</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe <span id="passwordOptional"></span></label>
                    <input type="password" id="password" name="password">
                </div>

                <div class="form-group">
                    <label for="nom_complet">Nom complet</label>
                    <input type="text" id="nom_complet" name="nom_complet">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="role">Rôle *</label>
                        <select id="role" name="role" required>
                            <option value="saisie">Saisie</option>
                            <option value="consultation">Consultation</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="actif">Statut *</label>
                        <select id="actif" name="actif" required>
                            <option value="1">Actif</option>
                            <option value="0">Inactif</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="region">Région</label>
                    <select id="region" name="region">
                        <option value="">Toutes les régions</option>
                        <?php foreach ($regions_niger as $region): ?>
                            <option value="<?php echo $region; ?>"><?php echo $region; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="departement">Département</label>
                    <input type="text" id="departement" name="departement">
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

        // Charger les utilisateurs
        async function loadUsers() {
            try {
                document.getElementById('loading').style.display = 'block';
                document.getElementById('usersTable').style.display = 'none';
                document.getElementById('noData').style.display = 'none';

                const response = await fetch('api/users.php');
                const result = await response.json();

                document.getElementById('loading').style.display = 'none';

                if (result.success && result.data.length > 0) {
                    displayUsers(result.data);
                    document.getElementById('usersTable').style.display = 'table';
                } else {
                    document.getElementById('noData').style.display = 'block';
                }
            } catch (error) {
                console.error('Erreur:', error);
                document.getElementById('loading').style.display = 'none';
                showError('Erreur lors du chargement des utilisateurs');
            }
        }

        // Afficher les utilisateurs dans le tableau
        function displayUsers(users) {
            const tbody = document.getElementById('usersTableBody');
            tbody.innerHTML = '';

            users.forEach(user => {
                const tr = document.createElement('tr');
                
                const roleClass = {
                    'admin': 'badge-admin',
                    'saisie': 'badge-saisie',
                    'consultation': 'badge-consultation'
                };

                const derniereCo = user.derniere_connexion 
                    ? new Date(user.derniere_connexion).toLocaleString('fr-FR')
                    : 'Jamais';

                tr.innerHTML = `
                    <td><strong>${user.username}</strong></td>
                    <td>${user.nom_complet || '-'}</td>
                    <td>${user.email || '-'}</td>
                    <td><span class="badge ${roleClass[user.role]}">${user.role.toUpperCase()}</span></td>
                    <td>${user.region || '-'}</td>
                    <td>
                        <span class="badge ${user.actif == 1 ? 'badge-actif' : 'badge-inactif'}">
                            ${user.actif == 1 ? 'Actif' : 'Inactif'}
                        </span>
                    </td>
                    <td>${derniereCo}</td>
                    <td>
                        <button class="btn-action btn-edit" onclick="editUser(${user.id})">
                            <i class="fas fa-edit"></i> Modifier
                        </button>
                        <button class="btn-action btn-delete" onclick="deleteUser(${user.id}, '${user.username}')">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        // Afficher le modal d'ajout
        function showAddModal() {
            isEditMode = false;
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-plus"></i> Ajouter un utilisateur';
            document.getElementById('userForm').reset();
            document.getElementById('userId').value = '';
            document.getElementById('password').required = true;
            document.getElementById('passwordOptional').textContent = '*';
            document.getElementById('userModal').classList.add('show');
        }

        // Éditer un utilisateur
        async function editUser(id) {
            try {
                const response = await fetch(`api/users.php?id=${id}`);
                const result = await response.json();

                if (result.success) {
                    isEditMode = true;
                    const user = result.data;
                    
                    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-edit"></i> Modifier l\'utilisateur';
                    document.getElementById('userId').value = user.id;
                    document.getElementById('username').value = user.username;
                    document.getElementById('password').value = '';
                    document.getElementById('password').required = false;
                    document.getElementById('passwordOptional').textContent = '(laisser vide pour ne pas changer)';
                    document.getElementById('nom_complet').value = user.nom_complet || '';
                    document.getElementById('email').value = user.email || '';
                    document.getElementById('role').value = user.role;
                    document.getElementById('actif').value = user.actif;
                    document.getElementById('region').value = user.region || '';
                    document.getElementById('departement').value = user.departement || '';

                    document.getElementById('userModal').classList.add('show');
                } else {
                    showError('Erreur lors du chargement de l\'utilisateur');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showError('Erreur lors du chargement de l\'utilisateur');
            }
        }

        // Supprimer un utilisateur
        async function deleteUser(id, username) {
            if (!confirm(`Êtes-vous sûr de vouloir supprimer l'utilisateur "${username}" ?`)) {
                return;
            }

            try {
                const response = await fetch('api/users.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                });

                const result = await response.json();

                if (result.success) {
                    showSuccess('Utilisateur supprimé avec succès');
                    loadUsers();
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
            const data = {};
            
            formData.forEach((value, key) => {
                if (value !== '') {
                    data[key] = value;
                }
            });

            try {
                const method = isEditMode ? 'PUT' : 'POST';
                const response = await fetch('api/users.php', {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    showSuccess(isEditMode ? 'Utilisateur modifié avec succès' : 'Utilisateur créé avec succès');
                    closeModal();
                    loadUsers();
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
            document.getElementById('userModal').classList.remove('show');
            document.getElementById('userForm').reset();
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

        // Charger les utilisateurs au chargement de la page
        window.addEventListener('DOMContentLoaded', () => {
            loadUsers();
        });
    </script>
    
    <!-- Script de gestion de l'année scolaire active -->
    <script src="js/annee_scolaire.js"></script>
</body>
</html>
