<?php
/**
 * API de gestion des utilisateurs
 * Enquête Rapide Rentrée Scolaire 2025-2026
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config.php';

// Vérifier que l'utilisateur est connecté et est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'error' => 'Accès non autorisé. Seuls les administrateurs peuvent gérer les utilisateurs.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$db = Database::getInstance()->getConnection();

// GET - Récupérer tous les utilisateurs ou un utilisateur spécifique
if ($method === 'GET') {
    try {
        if (isset($_GET['id'])) {
            // Récupérer un utilisateur spécifique
            $stmt = $db->prepare("
                SELECT id, username, nom_complet, email, role, region, departement, actif, 
                       derniere_connexion, created_at
                FROM utilisateurs 
                WHERE id = :id
            ");
            $stmt->execute(['id' => $_GET['id']]);
            $user = $stmt->fetch();
            
            if (!$user) {
                throw new Exception('Utilisateur non trouvé');
            }
            
            echo json_encode([
                'success' => true,
                'data' => $user
            ], JSON_UNESCAPED_UNICODE);
        } else {
            // Récupérer tous les utilisateurs
            $stmt = $db->query("
                SELECT id, username, nom_complet, email, role, region, departement, actif, 
                       derniere_connexion, created_at
                FROM utilisateurs
                ORDER BY created_at DESC
            ");
            $users = $stmt->fetchAll();
            
            echo json_encode([
                'success' => true,
                'data' => $users,
                'count' => count($users)
            ], JSON_UNESCAPED_UNICODE);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
}

// POST - Créer un nouvel utilisateur
elseif ($method === 'POST') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validation
        if (empty($data['username']) || empty($data['password'])) {
            throw new Exception('Nom d\'utilisateur et mot de passe requis');
        }
        
        // Vérifier si le nom d'utilisateur existe déjà
        $stmt = $db->prepare("SELECT id FROM utilisateurs WHERE username = :username");
        $stmt->execute(['username' => $data['username']]);
        if ($stmt->fetch()) {
            throw new Exception('Ce nom d\'utilisateur existe déjà');
        }
        
        // Hasher le mot de passe
        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
        
        // Insérer le nouvel utilisateur
        $stmt = $db->prepare("
            INSERT INTO utilisateurs (username, password_hash, nom_complet, email, role, region, departement, actif)
            VALUES (:username, :password_hash, :nom_complet, :email, :role, :region, :departement, :actif)
        ");
        
        $stmt->execute([
            'username' => $data['username'],
            'password_hash' => $passwordHash,
            'nom_complet' => $data['nom_complet'] ?? null,
            'email' => $data['email'] ?? null,
            'role' => $data['role'] ?? 'saisie',
            'region' => $data['region'] ?? null,
            'departement' => $data['departement'] ?? null,
            'actif' => isset($data['actif']) ? (int)$data['actif'] : 1
        ]);
        
        $userId = $db->lastInsertId();
        
        // Logger l'activité
        logActivite($_SESSION['user_id'], 'creation_utilisateur', 'utilisateurs', $userId, 
                    'Création de l\'utilisateur: ' . $data['username']);
        
        echo json_encode([
            'success' => true,
            'message' => 'Utilisateur créé avec succès',
            'user_id' => $userId
        ], JSON_UNESCAPED_UNICODE);
        
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
}

// PUT - Mettre à jour un utilisateur
elseif ($method === 'PUT') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['id'])) {
            throw new Exception('ID utilisateur requis');
        }
        
        // Vérifier que l'utilisateur existe
        $stmt = $db->prepare("SELECT id FROM utilisateurs WHERE id = :id");
        $stmt->execute(['id' => $data['id']]);
        if (!$stmt->fetch()) {
            throw new Exception('Utilisateur non trouvé');
        }
        
        // Construire la requête de mise à jour
        $updates = [];
        $params = ['id' => $data['id']];
        
        if (isset($data['username'])) {
            $updates[] = "username = :username";
            $params['username'] = $data['username'];
        }
        
        if (isset($data['password']) && !empty($data['password'])) {
            $updates[] = "password_hash = :password_hash";
            $params['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        if (isset($data['nom_complet'])) {
            $updates[] = "nom_complet = :nom_complet";
            $params['nom_complet'] = $data['nom_complet'];
        }
        
        if (isset($data['email'])) {
            $updates[] = "email = :email";
            $params['email'] = $data['email'];
        }
        
        if (isset($data['role'])) {
            $updates[] = "role = :role";
            $params['role'] = $data['role'];
        }
        
        if (isset($data['region'])) {
            $updates[] = "region = :region";
            $params['region'] = $data['region'];
        }
        
        if (isset($data['departement'])) {
            $updates[] = "departement = :departement";
            $params['departement'] = $data['departement'];
        }
        
        if (isset($data['actif'])) {
            $updates[] = "actif = :actif";
            $params['actif'] = (int)$data['actif'];
        }
        
        if (empty($updates)) {
            throw new Exception('Aucune donnée à mettre à jour');
        }
        
        $sql = "UPDATE utilisateurs SET " . implode(', ', $updates) . " WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        
        // Logger l'activité
        logActivite($_SESSION['user_id'], 'modification_utilisateur', 'utilisateurs', $data['id'], 
                    'Modification de l\'utilisateur ID: ' . $data['id']);
        
        echo json_encode([
            'success' => true,
            'message' => 'Utilisateur mis à jour avec succès'
        ], JSON_UNESCAPED_UNICODE);
        
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
}

// DELETE - Supprimer un utilisateur
elseif ($method === 'DELETE') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['id'])) {
            throw new Exception('ID utilisateur requis');
        }
        
        // Empêcher la suppression de son propre compte
        if ($data['id'] == $_SESSION['user_id']) {
            throw new Exception('Vous ne pouvez pas supprimer votre propre compte');
        }
        
        // Vérifier que l'utilisateur existe
        $stmt = $db->prepare("SELECT username FROM utilisateurs WHERE id = :id");
        $stmt->execute(['id' => $data['id']]);
        $user = $stmt->fetch();
        
        if (!$user) {
            throw new Exception('Utilisateur non trouvé');
        }
        
        // Supprimer l'utilisateur
        $stmt = $db->prepare("DELETE FROM utilisateurs WHERE id = :id");
        $stmt->execute(['id' => $data['id']]);
        
        // Logger l'activité
        logActivite($_SESSION['user_id'], 'suppression_utilisateur', 'utilisateurs', $data['id'], 
                    'Suppression de l\'utilisateur: ' . $user['username']);
        
        echo json_encode([
            'success' => true,
            'message' => 'Utilisateur supprimé avec succès'
        ], JSON_UNESCAPED_UNICODE);
        
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
}

else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Méthode non autorisée'
    ]);
}
?>
