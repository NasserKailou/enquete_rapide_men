<?php
/**
 * API pour récupérer les statistiques globales
 * Enquête Rapide Rentrée Scolaire 2025-2026
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once '../config.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Total des établissements
    $stmt = $db->query("SELECT COUNT(*) as total FROM etablissements");
    $totalEtablissements = $stmt->fetch()['total'];
    
    // Total des élèves (préscolaire + primaire + secondaire)
    $stmt = $db->query("
        SELECT 
            (SELECT COALESCE(SUM(total_eleves), 0) FROM effectifs_prescolaire) +
            (SELECT COALESCE(SUM(total_eleves), 0) FROM effectifs_primaire) +
            (SELECT COALESCE(SUM(total_eleves), 0) FROM effectifs_secondaire) as total
    ");
    $totalEleves = $stmt->fetch()['total'];
    
    // Total des enseignants
    $stmt = $db->query("SELECT COALESCE(SUM(total), 0) as total FROM personnel_enseignant");
    $totalEnseignants = $stmt->fetch()['total'];
    
    // Statistiques par cycle
    $stmt = $db->query("
        SELECT 
            cycle,
            COUNT(*) as nb_etablissements
        FROM etablissements
        GROUP BY cycle
    ");
    $statsCycles = $stmt->fetchAll();
    
    // Statistiques par région
    $stmt = $db->query("
        SELECT 
            region,
            COUNT(*) as nb_etablissements
        FROM etablissements
        GROUP BY region
        ORDER BY nb_etablissements DESC
    ");
    $statsRegions = $stmt->fetchAll();
    
    // Répartition public/privé
    $stmt = $db->query("
        SELECT 
            statut,
            COUNT(*) as nb_etablissements
        FROM etablissements
        GROUP BY statut
    ");
    $statsStatut = $stmt->fetchAll();
    
    // Répartition rural/urbain
    $stmt = $db->query("
        SELECT 
            zone,
            COUNT(*) as nb_etablissements
        FROM etablissements
        GROUP BY zone
    ");
    $statsZone = $stmt->fetchAll();
    
    $response = [
        'success' => true,
        'data' => [
            'total_etablissements' => (int)$totalEtablissements,
            'total_eleves' => (int)$totalEleves,
            'total_enseignants' => (int)$totalEnseignants,
            'par_cycle' => $statsCycles,
            'par_region' => $statsRegions,
            'par_statut' => $statsStatut,
            'par_zone' => $statsZone
        ],
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur lors de la récupération des statistiques',
        'message' => $e->getMessage()
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>
