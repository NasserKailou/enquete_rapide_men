# Gestion des Années Scolaires

## Vue d'ensemble

Le système de gestion des années scolaires permet aux administrateurs de gérer les différentes années scolaires, d'activer une année pour la collecte de données, et d'ouvrir/fermer la collecte selon les besoins.

## Fonctionnalités

### 1. **Gestion complète des années scolaires**
- ✅ Créer de nouvelles années scolaires
- ✅ Modifier les années existantes
- ✅ Activer/désactiver une année scolaire
- ✅ Ouvrir/fermer la collecte de données
- ✅ Supprimer les années sans données

### 2. **Année scolaire active**
- **Une seule année peut être active à la fois**
- L'année active est utilisée par défaut pour toutes les statistiques
- Les nouvelles données sont automatiquement liées à l'année active

### 3. **Contrôle de la collecte**
- Ouvrir la collecte : Les utilisateurs peuvent saisir des données
- Fermer la collecte : La saisie est bloquée (consultation uniquement)
- Chaque année peut avoir son propre statut de collecte

## Structure de la base de données

### Table `annees_scolaires`

```sql
CREATE TABLE annees_scolaires (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(20) NOT NULL UNIQUE,        -- Ex: "2025-2026"
    date_debut DATE NOT NULL,                   -- Date de début
    date_fin DATE NOT NULL,                     -- Date de fin
    active BOOLEAN DEFAULT FALSE,               -- Année active (une seule à la fois)
    collecte_ouverte BOOLEAN DEFAULT FALSE,     -- Collecte ouverte ou fermée
    description TEXT,                           -- Description optionnelle
    created_by INT,                             -- Utilisateur créateur
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Relation avec les établissements

La table `etablissements` contient maintenant un champ `annee_scolaire_id` qui lie chaque établissement à une année scolaire spécifique.

```sql
ALTER TABLE etablissements 
ADD COLUMN annee_scolaire_id INT AFTER id,
ADD CONSTRAINT fk_etablissement_annee 
    FOREIGN KEY (annee_scolaire_id) REFERENCES annees_scolaires(id);
```

## Installation et Migration

### Étape 1 : Exécuter le script de migration

```bash
mysql -u root -p enq_rapide_men < migration_annees_scolaires.sql
```

Ce script va :
- Créer la table `annees_scolaires`
- Ajouter le champ `annee_scolaire_id` à la table `etablissements`
- Créer les années scolaires par défaut (2024-2025, 2025-2026, 2026-2027)
- Créer les vues et procédures stockées
- Lier les établissements existants à l'année active

### Étape 2 : Vérifier l'installation

Accédez à la page de gestion : `gestion_annees.php`

Vous devriez voir les 3 années scolaires créées par défaut, avec 2025-2026 active.

## Utilisation

### Page de gestion des années scolaires

Accès : **Administration** → **Années Scolaires** ou directement via `gestion_annees.php`

#### Créer une nouvelle année

1. Cliquer sur **"Ajouter une année scolaire"**
2. Saisir :
   - **Libellé** : Format "YYYY-YYYY" (ex: 2026-2027)
   - **Date de début** : Date de début de l'année
   - **Date de fin** : Date de fin de l'année
   - **Description** : Description optionnelle
   - **Année active** : Cocher si c'est l'année à activer
   - **Collecte ouverte** : Cocher pour autoriser la saisie
3. Cliquer sur **"Enregistrer"**

#### Activer une année scolaire

1. Trouver l'année à activer dans le tableau
2. Cliquer sur **"Activer"**
3. Confirmer l'activation

⚠️ **Attention** : L'activation désactive automatiquement toutes les autres années.

#### Ouvrir/Fermer la collecte

1. Trouver l'année dans le tableau
2. Cliquer sur **"Ouvrir"** ou **"Fermer"**
3. Confirmer l'action

**Statuts possibles** :
- 🔓 **Ouverte** : La saisie de données est autorisée
- 🔒 **Fermée** : La saisie est bloquée, consultation uniquement

#### Modifier une année

1. Cliquer sur **"Modifier"** dans la ligne de l'année
2. Modifier les informations souhaitées
3. Cliquer sur **"Enregistrer"**

#### Supprimer une année

1. Cliquer sur **"Supprimer"** (visible seulement si pas d'établissements)
2. Confirmer la suppression

⚠️ **Restrictions** :
- Impossible de supprimer l'année active
- Impossible de supprimer une année contenant des données

## API

### Endpoint : `api/annees_scolaires.php`

#### GET - Liste des années

```javascript
fetch('api/annees_scolaires.php')
    .then(response => response.json())
    .then(data => console.log(data.data));
```

#### GET - Année active

```javascript
fetch('api/annees_scolaires.php?action=active')
    .then(response => response.json())
    .then(data => console.log(data.data));
```

#### GET - Statistiques par année

```javascript
fetch('api/annees_scolaires.php?action=stats')
    .then(response => response.json())
    .then(data => console.log(data.data));
```

#### POST - Créer une année

```javascript
fetch('api/annees_scolaires.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        libelle: '2027-2028',
        date_debut: '2027-10-01',
        date_fin: '2028-06-30',
        description: 'Année scolaire 2027-2028',
        active: 0,
        collecte_ouverte: 0
    })
});
```

#### PUT - Activer une année

```javascript
fetch('api/annees_scolaires.php', {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        id: 2,
        action: 'activer'
    })
});
```

#### PUT - Ouvrir/Fermer la collecte

```javascript
fetch('api/annees_scolaires.php', {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        id: 2,
        action: 'toggle_collecte',
        collecte_ouverte: 1  // 1 = ouvrir, 0 = fermer
    })
});
```

#### DELETE - Supprimer une année

```javascript
fetch('api/annees_scolaires.php', {
    method: 'DELETE',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id: 3 })
});
```

## Filtrage des statistiques par année

### API Stats mise à jour

L'API `api/stats.php` accepte maintenant un paramètre `annee_id` :

```javascript
// Statistiques de l'année active (par défaut)
fetch('api/stats.php')
    .then(response => response.json())
    .then(data => console.log(data));

// Statistiques d'une année spécifique
fetch('api/stats.php?annee_id=2')
    .then(response => response.json())
    .then(data => console.log(data));
```

## Configuration PHP

Le fichier `config.php` contient maintenant une fonction pour récupérer l'année active :

```php
// Récupérer l'année scolaire active
$anneeActive = getAnneeScolaireActive();

echo $anneeActive['libelle'];  // "2025-2026"
echo $anneeActive['id'];        // 2
echo $anneeActive['collecte_ouverte'];  // 1 ou 0
```

L'année active est automatiquement stockée en session :

```php
$_SESSION['annee_scolaire_active']
```

## Procédures stockées

### Activer une année

```sql
CALL sp_activer_annee_scolaire(2);
```

### Ouvrir/Fermer la collecte

```sql
CALL sp_toggle_collecte(2, TRUE);  -- Ouvrir
CALL sp_toggle_collecte(2, FALSE); -- Fermer
```

### Obtenir l'année active

```sql
CALL sp_get_annee_active();
```

## Vues

### v_stats_par_annee

Vue pour obtenir des statistiques par année scolaire :

```sql
SELECT * FROM v_stats_par_annee 
WHERE annee_scolaire = '2025-2026';
```

## Sécurité et Permissions

- ✅ **Gestion réservée aux administrateurs** uniquement
- ✅ **Validation des données** avant insertion
- ✅ **Protection contre la suppression** d'années contenant des données
- ✅ **Logs d'activité** pour toutes les actions

## Workflow recommandé

### Début d'une nouvelle année scolaire

1. **Créer la nouvelle année** via l'interface
   - Libellé : 2026-2027
   - Dates appropriées
   - Laisser inactive et collecte fermée

2. **Finaliser l'année précédente**
   - Fermer la collecte de l'année en cours
   - Exporter/archiver les données si nécessaire

3. **Activer la nouvelle année**
   - Cliquer sur "Activer" pour la nouvelle année
   - Ouvrir la collecte quand prêt

4. **Commencer la collecte**
   - Les nouvelles données seront automatiquement liées à la nouvelle année

### Clôture d'année

1. **Vérifier les données**
   - S'assurer que toutes les données sont complètes

2. **Fermer la collecte**
   - Cliquer sur "Fermer" pour l'année active

3. **Générer les rapports**
   - Exporter les statistiques finales

4. **Archivage** (optionnel)
   - Backup de la base de données
   - Export des données en CSV/Excel

## Dépannage

### Problème : Aucune année scolaire active

**Solution** :
1. Aller sur `gestion_annees.php`
2. Activer une année existante
3. Ou créer une nouvelle année et l'activer

### Problème : Impossible de supprimer une année

**Cause** : L'année contient des établissements

**Solution** :
- Les années avec données ne peuvent pas être supprimées (protection)
- Archiver l'année en la désactivant au lieu de la supprimer

### Problème : Erreur lors de la migration

**Solution** :
1. Vérifier que la base de données existe
2. S'assurer d'avoir les droits suffisants
3. Vérifier que les tables ne sont pas verrouillées

## Bonnes pratiques

✅ **Créer les années à l'avance** pour anticiper les transitions

✅ **Fermer la collecte** avant d'activer une nouvelle année

✅ **Documenter les changements** dans le champ description

✅ **Faire des backups** avant d'activer une nouvelle année

✅ **Tester** sur un environnement de développement d'abord

---

**Version** : 1.0.0  
**Date** : Décembre 2025  
**Auteur** : Équipe Technique MEN Niger
