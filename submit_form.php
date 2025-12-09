<?php
/**
 * Traitement de la soumission du formulaire d'enquête
 * Enregistrement dans la base de données MySQL
 */

require_once 'config.php';

header('Content-Type: application/json');

// Vérifier la méthode
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

try {
    $db = Database::getInstance()->getConnection();
    $db->beginTransaction();
    
    // Récupérer le cycle
    $cycle = securise($_POST['cycle'] ?? '');
    $allowed_cycles = ['prescolaire', 'primaire', 'secondaire'];
    
    if (!in_array($cycle, $allowed_cycles)) {
        throw new Exception('Cycle invalide');
    }
    
    // ====== INSERTION ÉTABLISSEMENT ======
    $sql_etablissement = "INSERT INTO etablissements (
        cycle, nom_etablissement, code_etablissement, date_creation, date_ouverture,
        region, departement, commune, village_quartier,
        dren_apl, iden_apl, inspection, secteur_pedagogique, iesg_iefa,
        zone, statut, type_enseignement, cycle_accueilli,
        fonction_annee_precedente, jardin_enfants_adosse,
        date_remplissage, telephone_directeur, nom_signature_directeur,
        telephone_chef_secteur, visa_chef_secteur, telephone_statisticien
    ) VALUES (
        :cycle, :nom_etablissement, :code_etablissement, :date_creation, :date_ouverture,
        :region, :departement, :commune, :village_quartier,
        :dren_apl, :iden_apl, :inspection, :secteur_pedagogique, :iesg_iefa,
        :zone, :statut, :type_enseignement, :cycle_accueilli,
        :fonction_annee_precedente, :jardin_enfants_adosse,
        :date_remplissage, :telephone_directeur, :nom_signature_directeur,
        :telephone_chef_secteur, :visa_chef_secteur, :telephone_statisticien
    )";
    
    $stmt_etablissement = $db->prepare($sql_etablissement);
    
    // Générer le code si vide
    $code = securise($_POST['code_etablissement'] ?? '');
    if (empty($code)) {
        $region = securise($_POST['region'] ?? '');
        $code = genererCodeEtablissement($cycle, $region);
    }
    
    // Type d'enseignement (peut être multiple)
    $type_enseignement = '';
    if (isset($_POST['type_enseignement']) && is_array($_POST['type_enseignement'])) {
        $type_enseignement = implode(', ', array_map('securise', $_POST['type_enseignement']));
    } elseif (isset($_POST['type_enseignement'])) {
        $type_enseignement = securise($_POST['type_enseignement']);
    }
    
    $stmt_etablissement->execute([
        'cycle' => $cycle,
        'nom_etablissement' => securise($_POST['nom_etablissement'] ?? ''),
        'code_etablissement' => $code,
        'date_creation' => $_POST['date_creation'] ?? null,
        'date_ouverture' => $_POST['date_ouverture'] ?? null,
        'region' => securise($_POST['region'] ?? ''),
        'departement' => securise($_POST['departement'] ?? ''),
        'commune' => securise($_POST['commune'] ?? ''),
        'village_quartier' => securise($_POST['village_quartier'] ?? ''),
        'dren_apl' => securise($_POST['dren_apl'] ?? ''),
        'iden_apl' => securise($_POST['iden_apl'] ?? ''),
        'inspection' => securise($_POST['inspection'] ?? ''),
        'secteur_pedagogique' => securise($_POST['secteur_pedagogique'] ?? ''),
        'iesg_iefa' => securise($_POST['iesg_iefa'] ?? null),
        'zone' => securise($_POST['zone'] ?? ''),
        'statut' => securise($_POST['statut'] ?? ''),
        'type_enseignement' => $type_enseignement,
        'cycle_accueilli' => securise($_POST['cycle_accueilli'] ?? null),
        'fonction_annee_precedente' => isset($_POST['fonction_annee_precedente']) ? (int)$_POST['fonction_annee_precedente'] : null,
        'jardin_enfants_adosse' => isset($_POST['jardin_enfants_adosse']) ? (int)$_POST['jardin_enfants_adosse'] : null,
        'date_remplissage' => $_POST['date_remplissage'] ?? date('Y-m-d'),
        'telephone_directeur' => securise($_POST['telephone_directeur'] ?? ''),
        'nom_signature_directeur' => securise($_POST['nom_signature_directeur'] ?? ''),
        'telephone_chef_secteur' => securise($_POST['telephone_chef_secteur'] ?? null),
        'visa_chef_secteur' => securise($_POST['visa_chef_secteur'] ?? null),
        'telephone_statisticien' => securise($_POST['telephone_statisticien'] ?? null)
    ]);
    
    $etablissement_id = $db->lastInsertId();
    
    // ====== INSERTION DES EFFECTIFS ======
    if ($cycle === 'prescolaire' && isset($_POST['effectifs'])) {
        $sql_effectifs = "INSERT INTO effectifs_prescolaire (etablissement_id, section, nb_groupes_pedagogiques, garcons, filles) 
                         VALUES (:etablissement_id, :section, :nb_groupes, :garcons, :filles)";
        $stmt_effectifs = $db->prepare($sql_effectifs);
        
        foreach ($_POST['effectifs'] as $section => $data) {
            $section_name = ($section === 'section1') ? 'Section 1' : 'Section 2';
            $nb_groupes = (int)($data['groupes_unique'] ?? 0) + (int)($data['groupes_jumeles'] ?? 0);
            
            $stmt_effectifs->execute([
                'etablissement_id' => $etablissement_id,
                'section' => $section_name,
                'nb_groupes' => $nb_groupes,
                'garcons' => (int)($data['garcons'] ?? 0),
                'filles' => (int)($data['filles'] ?? 0)
            ]);
        }
    }
    
    if ($cycle === 'primaire' && isset($_POST['effectifs'])) {
        $sql_effectifs = "INSERT INTO effectifs_primaire (
            etablissement_id, niveau, nb_groupes_unique, nb_groupes_jumeles,
            garcons, filles, redoublants_garcons, redoublants_filles
        ) VALUES (
            :etablissement_id, :niveau, :nb_groupes_unique, :nb_groupes_jumeles,
            :garcons, :filles, :redoublants_garcons, :redoublants_filles
        )";
        $stmt_effectifs = $db->prepare($sql_effectifs);
        
        foreach ($_POST['effectifs'] as $niveau => $data) {
            $stmt_effectifs->execute([
                'etablissement_id' => $etablissement_id,
                'niveau' => strtoupper($niveau),
                'nb_groupes_unique' => (int)($data['groupes_unique'] ?? 0),
                'nb_groupes_jumeles' => (int)($data['groupes_jumeles'] ?? 0),
                'garcons' => (int)($data['garcons'] ?? 0),
                'filles' => (int)($data['filles'] ?? 0),
                'redoublants_garcons' => (int)($data['redoublants_garcons'] ?? 0),
                'redoublants_filles' => (int)($data['redoublants_filles'] ?? 0)
            ]);
        }
    }
    
    if ($cycle === 'secondaire' && isset($_POST['effectifs'])) {
        $sql_effectifs = "INSERT INTO effectifs_secondaire (
            etablissement_id, niveau, serie, garcons, filles,
            redoublants_garcons, redoublants_filles, nb_groupes_pedagogiques
        ) VALUES (
            :etablissement_id, :niveau, :serie, :garcons, :filles,
            :redoublants_garcons, :redoublants_filles, :nb_groupes_pedagogiques
        )";
        $stmt_effectifs = $db->prepare($sql_effectifs);
        
        foreach ($_POST['effectifs'] as $niveau => $data) {
            $stmt_effectifs->execute([
                'etablissement_id' => $etablissement_id,
                'niveau' => $niveau,
                'serie' => $data['serie'] ?? null,
                'garcons' => (int)($data['garcons'] ?? 0),
                'filles' => (int)($data['filles'] ?? 0),
                'redoublants_garcons' => (int)($data['redoublants_garcons'] ?? 0),
                'redoublants_filles' => (int)($data['redoublants_filles'] ?? 0),
                'nb_groupes_pedagogiques' => (int)($data['nb_groupes'] ?? 0)
            ]);
        }
    }
    
    // ====== INSERTION DU PERSONNEL ======
    if (isset($_POST['personnel'])) {
        $sql_personnel = "INSERT INTO personnel_enseignant (
            etablissement_id, categorie, sous_categorie, discipline, hommes, femmes, est_stagiaire
        ) VALUES (
            :etablissement_id, :categorie, :sous_categorie, :discipline, :hommes, :femmes, :est_stagiaire
        )";
        $stmt_personnel = $db->prepare($sql_personnel);
        
        foreach ($_POST['personnel'] as $categorie => $data) {
            $hommes = (int)($data['hommes'] ?? 0);
            $femmes = (int)($data['femmes'] ?? 0);
            
            if ($hommes > 0 || $femmes > 0) {
                // Parser la catégorie et sous-catégorie
                $cat_parts = explode('_', $categorie);
                $sous_cat = end($cat_parts);
                $cat_name = str_replace('_' . $sous_cat, '', $categorie);
                $cat_name = ucwords(str_replace('_', ' ', $cat_name));
                
                $stmt_personnel->execute([
                    'etablissement_id' => $etablissement_id,
                    'categorie' => $cat_name,
                    'sous_categorie' => in_array(strtoupper($sous_cat), ['I', 'IA']) ? strtoupper($sous_cat) : null,
                    'discipline' => $data['discipline'] ?? null,
                    'hommes' => $hommes,
                    'femmes' => $femmes,
                    'est_stagiaire' => 0
                ]);
            }
        }
    }
    
    // Personnel stagiaires
    if (isset($_POST['stagiaires_hommes']) || isset($_POST['stagiaires_femmes'])) {
        $hommes = (int)($_POST['stagiaires_hommes'] ?? 0);
        $femmes = (int)($_POST['stagiaires_femmes'] ?? 0);
        
        if ($hommes > 0 || $femmes > 0) {
            $sql_stagiaires = "INSERT INTO personnel_enseignant (
                etablissement_id, categorie, hommes, femmes, est_stagiaire
            ) VALUES (:etablissement_id, 'Stagiaire', :hommes, :femmes, 1)";
            
            $stmt_stagiaires = $db->prepare($sql_stagiaires);
            $stmt_stagiaires->execute([
                'etablissement_id' => $etablissement_id,
                'hommes' => $hommes,
                'femmes' => $femmes
            ]);
        }
    }
    
    // Personnel administratif (secondaire)
    if ($cycle === 'secondaire' && isset($_POST['personnel_admin'])) {
        $sql_admin = "INSERT INTO personnel_administratif (etablissement_id, poste, existant, besoin) 
                      VALUES (:etablissement_id, :poste, :existant, :besoin)";
        $stmt_admin = $db->prepare($sql_admin);
        
        foreach ($_POST['personnel_admin'] as $poste => $data) {
            $stmt_admin->execute([
                'etablissement_id' => $etablissement_id,
                'poste' => ucwords(str_replace('_', ' ', $poste)),
                'existant' => (int)($data['existant'] ?? 0),
                'besoin' => (int)($data['besoin'] ?? 0)
            ]);
        }
    }
    
    // ====== INSERTION DES SALLES ======
    if (isset($_POST['salles']) || isset($_POST['total_salles'])) {
        $sql_salles = "INSERT INTO salles_classes (
            etablissement_id, total_salles, salles_utilisees, salles_mauvais_etat, salles_paillote, salles_non_occupees,
            dur, semi_dur, banco_terre_stabilisee, structure_evolutive, prefabriquees, paillotes_ameliorees, paillotes_ordinaire
        ) VALUES (
            :etablissement_id, :total_salles, :salles_utilisees, :salles_mauvais_etat, :salles_paillote, :salles_non_occupees,
            :dur, :semi_dur, :banco_terre_stabilisee, :structure_evolutive, :prefabriquees, :paillotes_ameliorees, :paillotes_ordinaire
        )";
        
        $stmt_salles = $db->prepare($sql_salles);
        $stmt_salles->execute([
            'etablissement_id' => $etablissement_id,
            'total_salles' => (int)($_POST['total_salles'] ?? 0),
            'salles_utilisees' => (int)($_POST['salles_utilisees'] ?? 0),
            'salles_mauvais_etat' => (int)($_POST['salles_mauvais_etat'] ?? 0),
            'salles_paillote' => (int)($_POST['salles_paillote'] ?? 0),
            'salles_non_occupees' => (int)($_POST['salles_non_occupees'] ?? 0),
            'dur' => (int)($_POST['salles']['dur'] ?? 0),
            'semi_dur' => (int)($_POST['salles']['semi_dur'] ?? 0),
            'banco_terre_stabilisee' => (int)($_POST['salles']['banco_terre_stabilisee'] ?? 0),
            'structure_evolutive' => (int)($_POST['salles']['structure_evolutive'] ?? 0),
            'prefabriquees' => (int)($_POST['salles']['prefabriquees'] ?? 0),
            'paillotes_ameliorees' => (int)($_POST['salles']['paillotes_ameliorees'] ?? 0),
            'paillotes_ordinaire' => (int)($_POST['salles']['paillotes_ordinaire'] ?? 0)
        ]);
    }
    
    // ====== INSERTION DES MOBILIERS ======
    if (isset($_POST['mobiliers'])) {
        $sql_mobiliers = "INSERT INTO mobiliers (
            etablissement_id, total_tables_bancs, tables_bancs_a_reparer, tables_bancs_a_completer, tables_bancs_handicapes,
            tables_adaptables, tables_bancs_adaptees, tables_bancs_ordinaires, chaises_ordinaires_tabourets, chaises_adaptees, tableau_chevalet
        ) VALUES (
            :etablissement_id, :total_tables_bancs, :tables_bancs_a_reparer, :tables_bancs_a_completer, :tables_bancs_handicapes,
            :tables_adaptables, :tables_bancs_adaptees, :tables_bancs_ordinaires, :chaises_ordinaires_tabourets, :chaises_adaptees, :tableau_chevalet
        )";
        
        $stmt_mobiliers = $db->prepare($sql_mobiliers);
        $stmt_mobiliers->execute([
            'etablissement_id' => $etablissement_id,
            'total_tables_bancs' => (int)($_POST['mobiliers']['total_tables_bancs'] ?? 0),
            'tables_bancs_a_reparer' => (int)($_POST['mobiliers']['tables_bancs_a_reparer'] ?? 0),
            'tables_bancs_a_completer' => (int)($_POST['mobiliers']['tables_bancs_a_completer'] ?? 0),
            'tables_bancs_handicapes' => (int)($_POST['mobiliers']['tables_bancs_handicapes'] ?? 0),
            'tables_adaptables' => (int)($_POST['mobiliers']['tables_adaptables'] ?? 0),
            'tables_bancs_adaptees' => (int)($_POST['mobiliers']['tables_bancs_adaptees'] ?? 0),
            'tables_bancs_ordinaires' => (int)($_POST['mobiliers']['tables_bancs_ordinaires'] ?? 0),
            'chaises_ordinaires_tabourets' => (int)($_POST['mobiliers']['chaises_ordinaires_tabourets'] ?? 0),
            'chaises_adaptees' => (int)($_POST['mobiliers']['chaises_adaptees'] ?? 0),
            'tableau_chevalet' => (int)($_POST['mobiliers']['tableau_chevalet'] ?? 0)
        ]);
    }
    
    // ====== INSERTION DES INFRASTRUCTURES ======
    if (isset($_POST['infrastructures'])) {
        $sql_infra = "INSERT INTO infrastructures (
            etablissement_id, total_latrines, latrines_fonctionnelles, point_eau, electricite,
            bibliotheque, infirmerie, laboratoires, salle_informatique, cloture
        ) VALUES (
            :etablissement_id, :total_latrines, :latrines_fonctionnelles, :point_eau, :electricite,
            :bibliotheque, :infirmerie, :laboratoires, :salle_informatique, :cloture
        )";
        
        $stmt_infra = $db->prepare($sql_infra);
        $stmt_infra->execute([
            'etablissement_id' => $etablissement_id,
            'total_latrines' => (int)($_POST['infrastructures']['total_latrines'] ?? 0),
            'latrines_fonctionnelles' => (int)($_POST['infrastructures']['latrines_fonctionnelles'] ?? 0),
            'point_eau' => (int)($_POST['infrastructures']['point_eau'] ?? 0),
            'electricite' => (int)($_POST['infrastructures']['electricite'] ?? 0),
            'bibliotheque' => (int)($_POST['infrastructures']['bibliotheque'] ?? 0),
            'infirmerie' => (int)($_POST['infrastructures']['infirmerie'] ?? 0),
            'laboratoires' => (int)($_POST['infrastructures']['laboratoires'] ?? 0),
            'salle_informatique' => (int)($_POST['infrastructures']['salle_informatique'] ?? 0),
            'cloture' => (int)($_POST['infrastructures']['cloture'] ?? 0)
        ]);
    }
    
    // ====== BESOINS ======
    if (isset($_POST['nb_educateurs_besoin']) || isset($_POST['nb_enseignants_besoin'])) {
        $besoin = (int)($_POST['nb_educateurs_besoin'] ?? $_POST['nb_enseignants_besoin'] ?? 0);
        if ($besoin > 0) {
            $sql_besoins = "INSERT INTO besoins (etablissement_id, type_besoin, quantite_besoin) 
                           VALUES (:etablissement_id, 'enseignants', :quantite)";
            $stmt_besoins = $db->prepare($sql_besoins);
            $stmt_besoins->execute([
                'etablissement_id' => $etablissement_id,
                'quantite' => $besoin
            ]);
        }
    }
    
    // Log d'activité
    // logActivite(1, 'INSERT', 'etablissements', $etablissement_id, "Nouvelle enquête $cycle");
    
    // Commit
    $db->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Enquête enregistrée avec succès',
        'etablissement_id' => $etablissement_id,
        'code' => $code
    ]);
    
} catch (Exception $e) {
    if (isset($db)) {
        $db->rollBack();
    }
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
