<?php
/**
 * Script de test pour initialiser la base de données avec des utilisateurs par défaut
 * À exécuter une seule fois après la création de la base de données
 */

require_once 'config.php';

echo "<h2>Test de connexion à la base de données et création des utilisateurs</h2>";

try {
    $db = Database::getInstance()->getConnection();
    echo "<p style='color: green;'>✓ Connexion à la base de données réussie</p>";
    
    // Vérifier si des utilisateurs existent déjà
    $stmt = $db->query("SELECT COUNT(*) as count FROM utilisateurs");
    $count = $stmt->fetch()['count'];
    
    echo "<p>Nombre d'utilisateurs actuels : <strong>$count</strong></p>";
    
    if ($count == 0) {
        echo "<h3>Création des utilisateurs par défaut...</h3>";
        
        // Créer l'utilisateur admin
        $passwordHash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $db->prepare("
            INSERT INTO utilisateurs (username, password_hash, nom_complet, email, role, actif)
            VALUES (:username, :password_hash, :nom_complet, :email, :role, 1)
        ");
        
        $users = [
            [
                'username' => 'admin',
                'password' => 'admin123',
                'nom_complet' => 'Administrateur Système',
                'email' => 'admin@education.ne',
                'role' => 'admin'
            ],
            [
                'username' => 'operateur_niamey',
                'password' => 'niamey2025',
                'nom_complet' => 'Opérateur Niamey',
                'email' => 'niamey@education.ne',
                'role' => 'saisie'
            ],
            [
                'username' => 'consultation',
                'password' => 'consult2025',
                'nom_complet' => 'Utilisateur Consultation',
                'email' => 'consultation@education.ne',
                'role' => 'consultation'
            ]
        ];
        
        foreach ($users as $user) {
            $stmt->execute([
                'username' => $user['username'],
                'password_hash' => password_hash($user['password'], PASSWORD_DEFAULT),
                'nom_complet' => $user['nom_complet'],
                'email' => $user['email'],
                'role' => $user['role']
            ]);
            
            echo "<p style='color: green;'>✓ Utilisateur créé : <strong>{$user['username']}</strong> (mot de passe: {$user['password']})</p>";
        }
        
        echo "<h3 style='color: green;'>Utilisateurs créés avec succès !</h3>";
    } else {
        echo "<h3>Des utilisateurs existent déjà dans la base de données</h3>";
        
        // Afficher les utilisateurs existants
        $stmt = $db->query("SELECT id, username, nom_complet, email, role, actif FROM utilisateurs");
        $users = $stmt->fetchAll();
        
        echo "<table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse; margin-top: 20px;'>";
        echo "<tr style='background-color: #0C9F57; color: white;'>
                <th>ID</th>
                <th>Nom d'utilisateur</th>
                <th>Nom complet</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Statut</th>
              </tr>";
        
        foreach ($users as $user) {
            $statut = $user['actif'] ? '<span style="color: green;">Actif</span>' : '<span style="color: red;">Inactif</span>';
            echo "<tr>
                    <td>{$user['id']}</td>
                    <td><strong>{$user['username']}</strong></td>
                    <td>{$user['nom_complet']}</td>
                    <td>{$user['email']}</td>
                    <td>{$user['role']}</td>
                    <td>$statut</td>
                  </tr>";
        }
        
        echo "</table>";
    }
    
    echo "<hr>";
    echo "<h3>Informations de connexion par défaut :</h3>";
    echo "<ul>";
    echo "<li><strong>Admin</strong> - Username: admin / Password: admin123</li>";
    echo "<li><strong>Opérateur</strong> - Username: operateur_niamey / Password: niamey2025</li>";
    echo "<li><strong>Consultation</strong> - Username: consultation / Password: consult2025</li>";
    echo "</ul>";
    
    echo "<p style='margin-top: 30px;'>";
    echo "<a href='index.html' style='background: #0C9F57; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Retour à l'accueil</a>";
    echo "</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Erreur : " . $e->getMessage() . "</p>";
}
?>
