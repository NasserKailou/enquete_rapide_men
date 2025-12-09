-- =====================================================
-- Base de données pour l'Enquête Rapide Rentrée Scolaire 2025-2026
-- Ministère de l'Education Nationale du Niger
-- =====================================================


-- =====================================================
-- TABLE PRINCIPALE: etablissements
-- =====================================================
CREATE TABLE etablissements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cycle ENUM('prescolaire', 'primaire', 'secondaire') NOT NULL,
    annee_scolaire VARCHAR(20) DEFAULT '2025-2026',
    
    -- Identification commune
    nom_etablissement VARCHAR(255) NOT NULL,
    code_etablissement VARCHAR(50) UNIQUE NOT NULL,
    date_creation DATE,
    date_ouverture DATE,
    
    -- Localisation Administrative
    region VARCHAR(100),
    departement VARCHAR(100),
    commune VARCHAR(100),
    village_quartier VARCHAR(100),
    
    -- Localisation Scolaire
    dren_apl VARCHAR(100),
    iden_apl VARCHAR(100),
    inspection VARCHAR(100),
    secteur_pedagogique VARCHAR(100),
    iesg_iefa VARCHAR(100), -- Pour secondaire
    
    -- Caractéristiques
    zone ENUM('Rurale', 'Urbaine'),
    statut ENUM('Public', 'Privé', 'Communautaire'),
    type_enseignement VARCHAR(50),
    cycle_accueilli VARCHAR(50), -- Pour secondaire
    
    -- Statut de fonctionnement
    fonction_annee_precedente BOOLEAN,
    jardin_enfants_adosse BOOLEAN, -- Pour primaire
    
    -- Métadonnées
    date_remplissage DATE,
    telephone_directeur VARCHAR(20),
    nom_signature_directeur VARCHAR(255),
    telephone_chef_secteur VARCHAR(20),
    visa_chef_secteur VARCHAR(255),
    telephone_statisticien VARCHAR(20),
    
    -- Audit
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_cycle (cycle),
    INDEX idx_region (region),
    INDEX idx_departement (departement),
    INDEX idx_annee (annee_scolaire)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: effectifs_prescolaire
-- =====================================================
CREATE TABLE effectifs_prescolaire (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etablissement_id INT NOT NULL,
    
    -- Sections
    section VARCHAR(20) NOT NULL, -- 'Section 1', 'Section 2', 'Unique', 'Jumelés'
    nb_groupes_pedagogiques INT DEFAULT 0,
    
    -- Effectifs
    garcons INT DEFAULT 0,
    filles INT DEFAULT 0,
    total_eleves INT GENERATED ALWAYS AS (garcons + filles) STORED,
    
    FOREIGN KEY (etablissement_id) REFERENCES etablissements(id) ON DELETE CASCADE,
    INDEX idx_etablissement (etablissement_id)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: effectifs_primaire
-- =====================================================
CREATE TABLE effectifs_primaire (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etablissement_id INT NOT NULL,
    
    -- Niveau
    niveau ENUM('CI', 'CP', 'CE1', 'CE2', 'CM1', 'CM2') NOT NULL,
    
    -- Groupes pédagogiques
    nb_groupes_unique INT DEFAULT 0,
    nb_groupes_jumeles INT DEFAULT 0,
    
    -- Effectifs totaux
    garcons INT DEFAULT 0,
    filles INT DEFAULT 0,
    total_eleves INT GENERATED ALWAYS AS (garcons + filles) STORED,
    
    -- Redoublants
    redoublants_garcons INT DEFAULT 0,
    redoublants_filles INT DEFAULT 0,
    total_redoublants INT GENERATED ALWAYS AS (redoublants_garcons + redoublants_filles) STORED,
    
    FOREIGN KEY (etablissement_id) REFERENCES etablissements(id) ON DELETE CASCADE,
    INDEX idx_etablissement (etablissement_id),
    INDEX idx_niveau (niveau)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: effectifs_secondaire
-- =====================================================
CREATE TABLE effectifs_secondaire (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etablissement_id INT NOT NULL,
    
    -- Niveau et série
    niveau VARCHAR(10) NOT NULL, -- '6ème', '5ème', '4ème', '3ème', '2nde A', '2nde C', etc.
    serie VARCHAR(10), -- 'A', 'C', 'D', null pour collège
    
    -- Effectifs
    garcons INT DEFAULT 0,
    filles INT DEFAULT 0,
    total_eleves INT GENERATED ALWAYS AS (garcons + filles) STORED,
    
    -- Redoublants
    redoublants_garcons INT DEFAULT 0,
    redoublants_filles INT DEFAULT 0,
    total_redoublants INT GENERATED ALWAYS AS (redoublants_garcons + redoublants_filles) STORED,
    
    -- Nombre de groupes pédagogiques (classes)
    nb_groupes_pedagogiques INT DEFAULT 0,
    
    FOREIGN KEY (etablissement_id) REFERENCES etablissements(id) ON DELETE CASCADE,
    INDEX idx_etablissement (etablissement_id),
    INDEX idx_niveau (niveau)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: personnel_enseignant
-- =====================================================
CREATE TABLE personnel_enseignant (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etablissement_id INT NOT NULL,
    
    -- Type de personnel
    categorie VARCHAR(50) NOT NULL, -- 'Fonctionnaire', 'Contractuel public', 'Maître Communautaire', etc.
    sous_categorie VARCHAR(50), -- 'I', 'IA', etc.
    discipline VARCHAR(50), -- Pour secondaire
    
    -- Effectifs
    hommes INT DEFAULT 0,
    femmes INT DEFAULT 0,
    total INT GENERATED ALWAYS AS (hommes + femmes) STORED,
    
    -- Stagiaires
    est_stagiaire BOOLEAN DEFAULT FALSE,
    
    FOREIGN KEY (etablissement_id) REFERENCES etablissements(id) ON DELETE CASCADE,
    INDEX idx_etablissement (etablissement_id),
    INDEX idx_categorie (categorie)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: personnel_administratif (Secondaire uniquement)
-- =====================================================
CREATE TABLE personnel_administratif (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etablissement_id INT NOT NULL,
    
    poste VARCHAR(100) NOT NULL, -- 'Directeur', 'Censeur', 'Surveillant général', etc.
    existant INT DEFAULT 0,
    besoin INT DEFAULT 0,
    
    FOREIGN KEY (etablissement_id) REFERENCES etablissements(id) ON DELETE CASCADE,
    INDEX idx_etablissement (etablissement_id)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: salles_classes
-- =====================================================
CREATE TABLE salles_classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etablissement_id INT NOT NULL,
    
    -- Totaux
    total_salles INT DEFAULT 0,
    salles_utilisees INT DEFAULT 0,
    salles_mauvais_etat INT DEFAULT 0, -- Secondaire
    salles_paillote INT DEFAULT 0, -- Secondaire
    salles_non_occupees INT DEFAULT 0, -- Secondaire
    
    -- Répartition par type
    dur INT DEFAULT 0,
    semi_dur INT DEFAULT 0,
    banco_terre_stabilisee INT DEFAULT 0,
    structure_evolutive INT DEFAULT 0,
    prefabriquees INT DEFAULT 0,
    paillotes_ameliorees INT DEFAULT 0,
    paillotes_ordinaire INT DEFAULT 0,
    
    FOREIGN KEY (etablissement_id) REFERENCES etablissements(id) ON DELETE CASCADE,
    INDEX idx_etablissement (etablissement_id)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: mobiliers
-- =====================================================
CREATE TABLE mobiliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etablissement_id INT NOT NULL,
    
    -- Tables-bancs
    total_tables_bancs INT DEFAULT 0,
    tables_bancs_a_reparer INT DEFAULT 0,
    tables_bancs_a_completer INT DEFAULT 0,
    tables_bancs_handicapes INT DEFAULT 0,
    
    -- Mobilier préscolaire spécifique
    tables_adaptables INT DEFAULT 0,
    tables_bancs_adaptees INT DEFAULT 0,
    tables_bancs_ordinaires INT DEFAULT 0,
    chaises_ordinaires_tabourets INT DEFAULT 0,
    chaises_adaptees INT DEFAULT 0,
    
    -- Équipements secondaire
    tableau_chevalet INT DEFAULT 0,
    
    FOREIGN KEY (etablissement_id) REFERENCES etablissements(id) ON DELETE CASCADE,
    INDEX idx_etablissement (etablissement_id)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: infrastructures
-- =====================================================
CREATE TABLE infrastructures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etablissement_id INT NOT NULL,
    
    -- Latrines
    total_latrines INT DEFAULT 0,
    latrines_fonctionnelles INT DEFAULT 0,
    
    -- Point d'eau
    point_eau BOOLEAN DEFAULT FALSE,
    
    -- Électricité
    electricite BOOLEAN DEFAULT FALSE,
    
    -- Infrastructures secondaire
    bibliotheque BOOLEAN DEFAULT FALSE,
    infirmerie BOOLEAN DEFAULT FALSE,
    laboratoires INT DEFAULT 0,
    salle_informatique BOOLEAN DEFAULT FALSE,
    cloture BOOLEAN DEFAULT FALSE,
    
    FOREIGN KEY (etablissement_id) REFERENCES etablissements(id) ON DELETE CASCADE,
    INDEX idx_etablissement (etablissement_id)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: besoins (pour le suivi des besoins)
-- =====================================================
CREATE TABLE besoins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etablissement_id INT NOT NULL,
    
    type_besoin VARCHAR(50) NOT NULL, -- 'enseignants', 'salles', 'personnel_admin', etc.
    quantite_besoin INT DEFAULT 0,
    description TEXT,
    
    FOREIGN KEY (etablissement_id) REFERENCES etablissements(id) ON DELETE CASCADE,
    INDEX idx_etablissement (etablissement_id),
    INDEX idx_type (type_besoin)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: utilisateurs (pour l'authentification)
-- =====================================================
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    nom_complet VARCHAR(255),
    email VARCHAR(255),
    role ENUM('admin', 'saisie', 'consultation') DEFAULT 'saisie',
    region VARCHAR(100),
    departement VARCHAR(100),
    actif BOOLEAN DEFAULT TRUE,
    derniere_connexion TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_username (username),
    INDEX idx_role (role)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: logs_activites (pour l'audit)
-- =====================================================
CREATE TABLE logs_activites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT,
    action VARCHAR(100) NOT NULL,
    table_concernee VARCHAR(50),
    id_enregistrement INT,
    details TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE SET NULL,
    INDEX idx_utilisateur (utilisateur_id),
    INDEX idx_action (action),
    INDEX idx_date (created_at)
) ENGINE=InnoDB;

-- =====================================================
-- VUES POUR LES STATISTIQUES
-- =====================================================

-- Vue: Synthèse par région
CREATE VIEW v_synthese_par_region AS
SELECT 
    e.region,
    e.cycle,
    COUNT(e.id) as nb_etablissements,
    SUM(CASE WHEN e.statut = 'Public' THEN 1 ELSE 0 END) as nb_public,
    SUM(CASE WHEN e.statut = 'Privé' THEN 1 ELSE 0 END) as nb_prive,
    SUM(CASE WHEN e.zone = 'Rurale' THEN 1 ELSE 0 END) as nb_rural,
    SUM(CASE WHEN e.zone = 'Urbaine' THEN 1 ELSE 0 END) as nb_urbain
FROM etablissements e
GROUP BY e.region, e.cycle;

-- Vue: Effectifs globaux primaire
CREATE VIEW v_effectifs_primaire_global AS
SELECT 
    e.region,
    e.departement,
    ep.niveau,
    SUM(ep.garcons) as total_garcons,
    SUM(ep.filles) as total_filles,
    SUM(ep.total_eleves) as total_eleves,
    SUM(ep.total_redoublants) as total_redoublants
FROM etablissements e
JOIN effectifs_primaire ep ON e.id = ep.etablissement_id
WHERE e.cycle = 'primaire'
GROUP BY e.region, e.departement, ep.niveau;

-- Vue: Effectifs globaux secondaire
CREATE VIEW v_effectifs_secondaire_global AS
SELECT 
    e.region,
    e.departement,
    es.niveau,
    es.serie,
    SUM(es.garcons) as total_garcons,
    SUM(es.filles) as total_filles,
    SUM(es.total_eleves) as total_eleves,
    SUM(es.total_redoublants) as total_redoublants
FROM etablissements e
JOIN effectifs_secondaire es ON e.id = es.etablissement_id
WHERE e.cycle = 'secondaire'
GROUP BY e.region, e.departement, es.niveau, es.serie;

-- Vue: Personnel enseignant global
CREATE VIEW v_personnel_enseignant_global AS
SELECT 
    e.region,
    e.cycle,
    pe.categorie,
    SUM(pe.hommes) as total_hommes,
    SUM(pe.femmes) as total_femmes,
    SUM(pe.total) as total_enseignants
FROM etablissements e
JOIN personnel_enseignant pe ON e.id = pe.etablissement_id
GROUP BY e.region, e.cycle, pe.categorie;

-- =====================================================
-- INSERTION DE DONNÉES DE TEST
-- =====================================================

-- Utilisateur admin par défaut (mot de passe: admin123)
INSERT INTO utilisateurs (username, password_hash, nom_complet, email, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrateur Système', 'admin@education.ne', 'admin'),
('operateur_niamey', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Opérateur Niamey', 'niamey@education.ne', 'saisie');

-- =====================================================
-- PROCÉDURES STOCKÉES
-- =====================================================

DELIMITER //

-- Procédure: Calculer les statistiques d'un établissement
CREATE PROCEDURE sp_statistiques_etablissement(IN etab_id INT)
BEGIN
    SELECT 
        e.*,
        (SELECT SUM(total_eleves) FROM effectifs_primaire WHERE etablissement_id = etab_id) as total_eleves_primaire,
        (SELECT SUM(total_eleves) FROM effectifs_secondaire WHERE etablissement_id = etab_id) as total_eleves_secondaire,
        (SELECT SUM(total) FROM personnel_enseignant WHERE etablissement_id = etab_id) as total_enseignants
    FROM etablissements e
    WHERE e.id = etab_id;
END //

-- Procédure: Rapport synthèse par cycle
CREATE PROCEDURE sp_rapport_synthese_cycle(IN p_cycle VARCHAR(20), IN p_region VARCHAR(100))
BEGIN
    IF p_region IS NULL THEN
        SELECT 
            region,
            COUNT(*) as nb_etablissements,
            SUM(CASE WHEN statut = 'Public' THEN 1 ELSE 0 END) as nb_public,
            SUM(CASE WHEN statut = 'Privé' THEN 1 ELSE 0 END) as nb_prive
        FROM etablissements
        WHERE cycle = p_cycle
        GROUP BY region;
    ELSE
        SELECT 
            departement,
            COUNT(*) as nb_etablissements,
            SUM(CASE WHEN statut = 'Public' THEN 1 ELSE 0 END) as nb_public,
            SUM(CASE WHEN statut = 'Privé' THEN 1 ELSE 0 END) as nb_prive
        FROM etablissements
        WHERE cycle = p_cycle AND region = p_region
        GROUP BY departement;
    END IF;
END //

DELIMITER ;

-- =====================================================
-- INDEX ADDITIONNELS POUR PERFORMANCE
-- =====================================================
CREATE INDEX idx_etablissement_cycle_region ON etablissements(cycle, region);
CREATE INDEX idx_etablissement_statut ON etablissements(statut);
CREATE INDEX idx_etablissement_zone ON etablissements(zone);

-- =====================================================
-- FIN DU SCRIPT
-- =====================================================
