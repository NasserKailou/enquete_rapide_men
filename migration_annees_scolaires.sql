-- =====================================================
-- Migration : Ajout de la gestion des années scolaires
-- Date : 2025-12-09
-- =====================================================

-- =====================================================
-- TABLE: annees_scolaires
-- =====================================================
CREATE TABLE IF NOT EXISTS annees_scolaires (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(20) NOT NULL UNIQUE,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    active BOOLEAN DEFAULT FALSE,
    collecte_ouverte BOOLEAN DEFAULT FALSE,
    description TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (created_by) REFERENCES utilisateurs(id) ON DELETE SET NULL,
    INDEX idx_active (active),
    INDEX idx_collecte_ouverte (collecte_ouverte)
) ENGINE=InnoDB;

-- =====================================================
-- Modifier la table etablissements pour ajouter annee_scolaire_id
-- =====================================================
ALTER TABLE etablissements 
ADD COLUMN annee_scolaire_id INT AFTER id,
ADD CONSTRAINT fk_etablissement_annee 
    FOREIGN KEY (annee_scolaire_id) REFERENCES annees_scolaires(id) ON DELETE RESTRICT;

-- Créer un index sur annee_scolaire_id pour optimiser les requêtes
ALTER TABLE etablissements ADD INDEX idx_annee_scolaire (annee_scolaire_id);

-- =====================================================
-- Insertion des années scolaires par défaut
-- =====================================================

-- Année scolaire 2024-2025 (année précédente)
INSERT INTO annees_scolaires (libelle, date_debut, date_fin, active, collecte_ouverte, description) 
VALUES 
    ('2024-2025', '2024-10-01', '2025-06-30', 0, 0, 'Année scolaire 2024-2025'),
    ('2025-2026', '2025-10-01', '2026-06-30', 1, 1, 'Année scolaire 2025-2026 (Active)'),
    ('2026-2027', '2026-10-01', '2027-06-30', 0, 0, 'Année scolaire 2026-2027');

-- =====================================================
-- Mettre à jour les établissements existants
-- =====================================================
-- Associer tous les établissements existants à l'année active (2025-2026)
UPDATE etablissements 
SET annee_scolaire_id = (SELECT id FROM annees_scolaires WHERE active = 1 LIMIT 1)
WHERE annee_scolaire_id IS NULL;

-- =====================================================
-- Vue pour les statistiques par année scolaire
-- =====================================================
CREATE OR REPLACE VIEW v_stats_par_annee AS
SELECT 
    a.id as annee_id,
    a.libelle as annee_scolaire,
    e.cycle,
    e.region,
    COUNT(e.id) as nb_etablissements,
    SUM(CASE WHEN e.statut = 'Public' THEN 1 ELSE 0 END) as nb_public,
    SUM(CASE WHEN e.statut = 'Privé' THEN 1 ELSE 0 END) as nb_prive,
    SUM(CASE WHEN e.zone = 'Rurale' THEN 1 ELSE 0 END) as nb_rural,
    SUM(CASE WHEN e.zone = 'Urbaine' THEN 1 ELSE 0 END) as nb_urbain
FROM annees_scolaires a
LEFT JOIN etablissements e ON a.id = e.annee_scolaire_id
GROUP BY a.id, a.libelle, e.cycle, e.region;

-- =====================================================
-- Procédure : Activer une année scolaire
-- =====================================================
DELIMITER //

DROP PROCEDURE IF EXISTS sp_activer_annee_scolaire//

CREATE PROCEDURE sp_activer_annee_scolaire(IN p_annee_id INT)
BEGIN
    -- Désactiver toutes les années
    UPDATE annees_scolaires SET active = 0;
    
    -- Activer l'année sélectionnée
    UPDATE annees_scolaires SET active = 1 WHERE id = p_annee_id;
    
    -- Log de l'action
    INSERT INTO logs_activites (utilisateur_id, action, table_concernee, id_enregistrement, details)
    VALUES (NULL, 'activation_annee_scolaire', 'annees_scolaires', p_annee_id, 
            CONCAT('Activation de l\'année scolaire ID: ', p_annee_id));
END//

-- =====================================================
-- Procédure : Ouvrir/Fermer la collecte pour une année
-- =====================================================
DROP PROCEDURE IF EXISTS sp_toggle_collecte//

CREATE PROCEDURE sp_toggle_collecte(IN p_annee_id INT, IN p_etat BOOLEAN)
BEGIN
    UPDATE annees_scolaires 
    SET collecte_ouverte = p_etat 
    WHERE id = p_annee_id;
    
    -- Log de l'action
    INSERT INTO logs_activites (utilisateur_id, action, table_concernee, id_enregistrement, details)
    VALUES (NULL, 'toggle_collecte', 'annees_scolaires', p_annee_id, 
            CONCAT('Collecte ', IF(p_etat, 'ouverte', 'fermée'), ' pour l\'année ID: ', p_annee_id));
END//

-- =====================================================
-- Procédure : Obtenir l'année scolaire active
-- =====================================================
DROP PROCEDURE IF EXISTS sp_get_annee_active//

CREATE PROCEDURE sp_get_annee_active()
BEGIN
    SELECT * FROM annees_scolaires WHERE active = 1 LIMIT 1;
END//

-- =====================================================
-- Fonction : Vérifier si la collecte est ouverte
-- =====================================================
DROP FUNCTION IF EXISTS fn_collecte_ouverte//

CREATE FUNCTION fn_collecte_ouverte(p_annee_id INT) 
RETURNS BOOLEAN
DETERMINISTIC
BEGIN
    DECLARE v_ouvert BOOLEAN;
    
    SELECT collecte_ouverte INTO v_ouvert 
    FROM annees_scolaires 
    WHERE id = p_annee_id;
    
    RETURN IFNULL(v_ouvert, FALSE);
END//

DELIMITER ;

-- =====================================================
-- Trigger : Empêcher la suppression d'une année avec données
-- =====================================================
DELIMITER //

DROP TRIGGER IF EXISTS before_delete_annee_scolaire//

CREATE TRIGGER before_delete_annee_scolaire
BEFORE DELETE ON annees_scolaires
FOR EACH ROW
BEGIN
    DECLARE v_count INT;
    
    -- Compter les établissements liés à cette année
    SELECT COUNT(*) INTO v_count 
    FROM etablissements 
    WHERE annee_scolaire_id = OLD.id;
    
    -- Empêcher la suppression si des données existent
    IF v_count > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Impossible de supprimer une année scolaire contenant des données';
    END IF;
END//

DELIMITER ;

-- =====================================================
-- Index additionnels pour la performance
-- =====================================================
CREATE INDEX idx_etablissement_annee_cycle ON etablissements(annee_scolaire_id, cycle);
CREATE INDEX idx_etablissement_annee_region ON etablissements(annee_scolaire_id, region);

-- =====================================================
-- Afficher un résumé de la migration
-- =====================================================
SELECT 'Migration terminée avec succès!' as status;
SELECT * FROM annees_scolaires ORDER BY date_debut DESC;
