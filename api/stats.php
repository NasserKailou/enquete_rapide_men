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
    
    // Récupérer l'année scolaire à filtrer (paramètre GET ou année active)
    $annee_id = isset($_GET['annee_id']) ? (int)$_GET['annee_id'] : null;
    
    // Si aucune année n'est spécifiée, utiliser l'année active
    if ($annee_id === null) {
        $anneeActive = getAnneeScolaireActive();
        $annee_id = $anneeActive['id'];
    }
    
    // Construire la clause WHERE pour le filtre d'année
    $whereClause = $annee_id ? "WHERE annee_scolaire_id = :annee_id" : "";
    $params = $annee_id ? ['annee_id' => $annee_id] : [];
    
    // Total des établissements
    $sql = "SELECT COUNT(*) as total FROM etablissements $whereClause";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $totalEtablissements = $stmt->fetch()['total'];
    
    // Total des élèves (préscolaire + primaire + secondaire)
    $joinClause = $annee_id ? "JOIN etablissements e ON ep.etablissement_id = e.id WHERE e.annee_scolaire_id = :annee_id" : "";
    $sql = "
        SELECT 
            (SELECT COALESCE(SUM(ep.total_eleves), 0) FROM effectifs_prescolaire ep $joinClause) +
            (SELECT COALESCE(SUM(ep.total_eleves), 0) FROM effectifs_primaire ep " . 
            ($annee_id ? "JOIN etablissements e ON ep.etablissement_id = e.id WHERE e.annee_scolaire_id = :annee_id" : "") . ") +
            (SELECT COALESCE(SUM(es.total_eleves), 0) FROM effectifs_secondaire es " .
            ($annee_id ? "JOIN etablissements e ON es.etablissement_id = e.id WHERE e.annee_scolaire_id = :annee_id" : "") . ") as total
    ";
    $stmt = $db->prepare($sql);
    if ($annee_id) {
        $stmt->execute(['annee_id' => $annee_id, 'annee_id' => $annee_id, 'annee_id' => $annee_id]);
    } else {
        $stmt->execute();
    }
    $totalEleves = $stmt->fetch()['total'];
    
    // Total des enseignants
    $joinPers = $annee_id ? "JOIN etablissements e ON pe.etablissement_id = e.id WHERE e.annee_scolaire_id = :annee_id" : "";
    $sql = "SELECT COALESCE(SUM(pe.total), 0) as total FROM personnel_enseignant pe $joinPers";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $totalEnseignants = $stmt->fetch()['total'];
    
    // Statistiques par cycle
    $sql = "
        SELECT 
            cycle,
            COUNT(*) as nb_etablissements
        FROM etablissements
        $whereClause
        GROUP BY cycle
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $statsCycles = $stmt->fetchAll();
    
    // Statistiques par région
    $sql = "
        SELECT 
            region,
            COUNT(*) as nb_etablissements
        FROM etablissements
        $whereClause
        GROUP BY region
        ORDER BY nb_etablissements DESC
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $statsRegions = $stmt->fetchAll();
    
    // Répartition public/privé
    $sql = "
        SELECT 
            statut,
            COUNT(*) as nb_etablissements
        FROM etablissements
        $whereClause
        GROUP BY statut
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $statsStatut = $stmt->fetchAll();
    
    // Répartition rural/urbain
    $sql = "
        SELECT 
            zone,
            COUNT(*) as nb_etablissements
        FROM etablissements
        $whereClause
        GROUP BY zone
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $statsZone = $stmt->fetchAll();
    
    // Récupérer les informations de l'année scolaire filtrée
    $anneeInfo = null;
    if ($annee_id) {
        $stmt = $db->prepare("SELECT libelle, active FROM annees_scolaires WHERE id = :annee_id");
        $stmt->execute(['annee_id' => $annee_id]);
        $anneeInfo = $stmt->fetch();
    }
    
    $response = [
        'success' => true,
        'data' => [
            'annee_scolaire' => $anneeInfo ? $anneeInfo['libelle'] : 'Toutes les années',
            'annee_id' => $annee_id,
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
