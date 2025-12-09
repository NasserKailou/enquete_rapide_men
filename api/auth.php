<?php
/**
 * API d'authentification
 * Enquête Rapide Rentrée Scolaire 2025-2026
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config.php';

$method = $_SERVER['REQUEST_METHOD'];

// Login
if ($method === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    try {
        $username = securise($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($username) || empty($password)) {
            throw new Exception('Nom d\'utilisateur et mot de passe requis');
        }
        
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("SELECT * FROM utilisateurs WHERE username = :username AND actif = 1");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();
        
        if (!$user) {
            throw new Exception('Nom d\'utilisateur ou mot de passe incorrect');
        }
        
        // Vérification du mot de passe
        if (!password_verify($password, $user['password_hash'])) {
            throw new Exception('Nom d\'utilisateur ou mot de passe incorrect');
        }
        
        // Mise à jour de la dernière connexion
        $stmt = $db->prepare("UPDATE utilisateurs SET derniere_connexion = NOW() WHERE id = :id");
        $stmt->execute(['id' => $user['id']]);
        
        // Démarrer la session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['nom_complet'] = $user['nom_complet'];
        
        // Logger l'activité
        logActivite($user['id'], 'connexion', 'utilisateurs', $user['id'], 'Connexion réussie');
        
        echo json_encode([
            'success' => true,
            'message' => 'Connexion réussie',
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'nom_complet' => $user['nom_complet'],
                'role' => $user['role'],
                'region' => $user['region']
            ]
        ], JSON_UNESCAPED_UNICODE);
        
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
}

// Logout
elseif ($method === 'POST' && isset($_POST['action']) && $_POST['action'] === 'logout') {
    if (isset($_SESSION['user_id'])) {
        logActivite($_SESSION['user_id'], 'deconnexion', 'utilisateurs', $_SESSION['user_id'], 'Déconnexion');
    }
    
    session_destroy();
    
    echo json_encode([
        'success' => true,
        'message' => 'Déconnexion réussie'
    ], JSON_UNESCAPED_UNICODE);
}

// Check session
elseif ($method === 'GET' && isset($_GET['action']) && $_GET['action'] === 'check') {
    if (isset($_SESSION['user_id'])) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT id, username, nom_complet, role, region FROM utilisateurs WHERE id = :id AND actif = 1");
        $stmt->execute(['id' => $_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        if ($user) {
            echo json_encode([
                'success' => true,
                'authenticated' => true,
                'user' => $user
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([
                'success' => true,
                'authenticated' => false
            ]);
        }
    } else {
        echo json_encode([
            'success' => true,
            'authenticated' => false
        ]);
    }
}

else {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Requête invalide'
    ]);
}
?>
