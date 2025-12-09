<?php
/**
 * API de gestion des années scolaires
 * Enquête Rapide Rentrée Scolaire
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config.php';

$method = $_SERVER['REQUEST_METHOD'];
$db = Database::getInstance()->getConnection();

// GET - Récupérer les années scolaires
if ($method === 'GET') {
    try {
        if (isset($_GET['action'])) {
            // Actions spéciales
            switch ($_GET['action']) {
                case 'active':
                    // Récupérer l'année scolaire active
                    $stmt = $db->query("SELECT * FROM annees_scolaires WHERE active = 1 LIMIT 1");
                    $annee = $stmt->fetch();
                    
                    if ($annee) {
                        echo json_encode([
                            'success' => true,
                            'data' => $annee
                        ], JSON_UNESCAPED_UNICODE);
                    } else {
                        echo json_encode([
                            'success' => false,
                            'error' => 'Aucune année scolaire active'
                        ], JSON_UNESCAPED_UNICODE);
                    }
                    break;
                    
                case 'stats':
                    // Statistiques par année
                    $annee_id = $_GET['annee_id'] ?? null;
                    
                    $sql = "SELECT 
                        a.id, a.libelle, a.active, a.collecte_ouverte,
                        COUNT(DISTINCT e.id) as nb_etablissements,
                        (SELECT COUNT(*) FROM etablissements WHERE annee_scolaire_id = a.id AND cycle = 'prescolaire') as nb_prescolaire,
                        (SELECT COUNT(*) FROM etablissements WHERE annee_scolaire_id = a.id AND cycle = 'primaire') as nb_primaire,
                        (SELECT COUNT(*) FROM etablissements WHERE annee_scolaire_id = a.id AND cycle = 'secondaire') as nb_secondaire
                    FROM annees_scolaires a
                    LEFT JOIN etablissements e ON a.id = e.annee_scolaire_id";
                    
                    if ($annee_id) {
                        $sql .= " WHERE a.id = :annee_id";
                    }
                    
                    $sql .= " GROUP BY a.id ORDER BY a.date_debut DESC";
                    
                    $stmt = $db->prepare($sql);
                    if ($annee_id) {
                        $stmt->execute(['annee_id' => $annee_id]);
                    } else {
                        $stmt->execute();
                    }
                    
                    $stats = $stmt->fetchAll();
                    
                    echo json_encode([
                        'success' => true,
                        'data' => $stats
                    ], JSON_UNESCAPED_UNICODE);
                    break;
                    
                default:
                    throw new Exception('Action non reconnue');
            }
        } elseif (isset($_GET['id'])) {
            // Récupérer une année spécifique
            $stmt = $db->prepare("SELECT * FROM annees_scolaires WHERE id = :id");
            $stmt->execute(['id' => $_GET['id']]);
            $annee = $stmt->fetch();
            
            if (!$annee) {
                throw new Exception('Année scolaire non trouvée');
            }
            
            echo json_encode([
                'success' => true,
                'data' => $annee
            ], JSON_UNESCAPED_UNICODE);
        } else {
            // Récupérer toutes les années
            $stmt = $db->query("
                SELECT a.*, 
                    COUNT(e.id) as nb_etablissements,
                    u.username as created_by_username
                FROM annees_scolaires a
                LEFT JOIN etablissements e ON a.id = e.annee_scolaire_id
                LEFT JOIN utilisateurs u ON a.created_by = u.id
                GROUP BY a.id
                ORDER BY a.date_debut DESC
            ");
            $annees = $stmt->fetchAll();
            
            echo json_encode([
                'success' => true,
                'data' => $annees,
                'count' => count($annees)
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

// POST - Créer une nouvelle année scolaire (Admin uniquement)
elseif ($method === 'POST') {
    // Vérifier que l'utilisateur est admin
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'error' => 'Accès non autorisé'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validation
        if (empty($data['libelle']) || empty($data['date_debut']) || empty($data['date_fin'])) {
            throw new Exception('Libellé, date de début et date de fin requis');
        }
        
        // Vérifier si l'année existe déjà
        $stmt = $db->prepare("SELECT id FROM annees_scolaires WHERE libelle = :libelle");
        $stmt->execute(['libelle' => $data['libelle']]);
        if ($stmt->fetch()) {
            throw new Exception('Cette année scolaire existe déjà');
        }
        
        // Si l'année doit être active, désactiver les autres
        if (isset($data['active']) && $data['active']) {
            $db->exec("UPDATE annees_scolaires SET active = 0");
        }
        
        // Insérer la nouvelle année
        $stmt = $db->prepare("
            INSERT INTO annees_scolaires (libelle, date_debut, date_fin, active, collecte_ouverte, description, created_by)
            VALUES (:libelle, :date_debut, :date_fin, :active, :collecte_ouverte, :description, :created_by)
        ");
        
        $stmt->execute([
            'libelle' => $data['libelle'],
            'date_debut' => $data['date_debut'],
            'date_fin' => $data['date_fin'],
            'active' => isset($data['active']) ? (int)$data['active'] : 0,
            'collecte_ouverte' => isset($data['collecte_ouverte']) ? (int)$data['collecte_ouverte'] : 0,
            'description' => $data['description'] ?? null,
            'created_by' => $_SESSION['user_id']
        ]);
        
        $annee_id = $db->lastInsertId();
        
        // Logger l'activité
        logActivite($_SESSION['user_id'], 'creation_annee_scolaire', 'annees_scolaires', $annee_id, 
                    'Création de l\'année scolaire: ' . $data['libelle']);
        
        echo json_encode([
            'success' => true,
            'message' => 'Année scolaire créée avec succès',
            'annee_id' => $annee_id
        ], JSON_UNESCAPED_UNICODE);
        
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
}

// PUT - Mettre à jour une année scolaire (Admin uniquement)
elseif ($method === 'PUT') {
    // Vérifier que l'utilisateur est admin
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'error' => 'Accès non autorisé'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['id'])) {
            throw new Exception('ID de l\'année scolaire requis');
        }
        
        // Actions spéciales
        if (isset($data['action'])) {
            switch ($data['action']) {
                case 'activer':
                    // Désactiver toutes les années
                    $db->exec("UPDATE annees_scolaires SET active = 0");
                    
                    // Activer l'année sélectionnée
                    $stmt = $db->prepare("UPDATE annees_scolaires SET active = 1 WHERE id = :id");
                    $stmt->execute(['id' => $data['id']]);
                    
                    logActivite($_SESSION['user_id'], 'activation_annee_scolaire', 'annees_scolaires', $data['id'], 
                                'Activation de l\'année scolaire ID: ' . $data['id']);
                    
                    echo json_encode([
                        'success' => true,
                        'message' => 'Année scolaire activée avec succès'
                    ], JSON_UNESCAPED_UNICODE);
                    break;
                    
                case 'toggle_collecte':
                    $etat = isset($data['collecte_ouverte']) ? (int)$data['collecte_ouverte'] : 0;
                    
                    $stmt = $db->prepare("UPDATE annees_scolaires SET collecte_ouverte = :etat WHERE id = :id");
                    $stmt->execute(['etat' => $etat, 'id' => $data['id']]);
                    
                    logActivite($_SESSION['user_id'], 'toggle_collecte', 'annees_scolaires', $data['id'], 
                                'Collecte ' . ($etat ? 'ouverte' : 'fermée') . ' pour l\'année ID: ' . $data['id']);
                    
                    echo json_encode([
                        'success' => true,
                        'message' => 'Statut de collecte modifié avec succès'
                    ], JSON_UNESCAPED_UNICODE);
                    break;
                    
                default:
                    throw new Exception('Action non reconnue');
            }
        } else {
            // Mise à jour normale
            $updates = [];
            $params = ['id' => $data['id']];
            
            if (isset($data['libelle'])) {
                $updates[] = "libelle = :libelle";
                $params['libelle'] = $data['libelle'];
            }
            
            if (isset($data['date_debut'])) {
                $updates[] = "date_debut = :date_debut";
                $params['date_debut'] = $data['date_debut'];
            }
            
            if (isset($data['date_fin'])) {
                $updates[] = "date_fin = :date_fin";
                $params['date_fin'] = $data['date_fin'];
            }
            
            if (isset($data['description'])) {
                $updates[] = "description = :description";
                $params['description'] = $data['description'];
            }
            
            if (isset($data['active'])) {
                // Si on active cette année, désactiver les autres
                if ($data['active']) {
                    $db->exec("UPDATE annees_scolaires SET active = 0");
                }
                $updates[] = "active = :active";
                $params['active'] = (int)$data['active'];
            }
            
            if (isset($data['collecte_ouverte'])) {
                $updates[] = "collecte_ouverte = :collecte_ouverte";
                $params['collecte_ouverte'] = (int)$data['collecte_ouverte'];
            }
            
            if (empty($updates)) {
                throw new Exception('Aucune donnée à mettre à jour');
            }
            
            $sql = "UPDATE annees_scolaires SET " . implode(', ', $updates) . " WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            
            logActivite($_SESSION['user_id'], 'modification_annee_scolaire', 'annees_scolaires', $data['id'], 
                        'Modification de l\'année scolaire ID: ' . $data['id']);
            
            echo json_encode([
                'success' => true,
                'message' => 'Année scolaire mise à jour avec succès'
            ], JSON_UNESCAPED_UNICODE);
        }
        
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
}

// DELETE - Supprimer une année scolaire (Admin uniquement)
elseif ($method === 'DELETE') {
    // Vérifier que l'utilisateur est admin
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'error' => 'Accès non autorisé'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['id'])) {
            throw new Exception('ID de l\'année scolaire requis');
        }
        
        // Vérifier si l'année est active
        $stmt = $db->prepare("SELECT active, libelle FROM annees_scolaires WHERE id = :id");
        $stmt->execute(['id' => $data['id']]);
        $annee = $stmt->fetch();
        
        if (!$annee) {
            throw new Exception('Année scolaire non trouvée');
        }
        
        if ($annee['active']) {
            throw new Exception('Impossible de supprimer l\'année scolaire active');
        }
        
        // Vérifier s'il y a des données liées
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM etablissements WHERE annee_scolaire_id = :id");
        $stmt->execute(['id' => $data['id']]);
        $count = $stmt->fetch()['count'];
        
        if ($count > 0) {
            throw new Exception('Impossible de supprimer une année contenant des données (' . $count . ' établissements)');
        }
        
        // Supprimer l'année
        $stmt = $db->prepare("DELETE FROM annees_scolaires WHERE id = :id");
        $stmt->execute(['id' => $data['id']]);
        
        logActivite($_SESSION['user_id'], 'suppression_annee_scolaire', 'annees_scolaires', $data['id'], 
                    'Suppression de l\'année scolaire: ' . $annee['libelle']);
        
        echo json_encode([
            'success' => true,
            'message' => 'Année scolaire supprimée avec succès'
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
