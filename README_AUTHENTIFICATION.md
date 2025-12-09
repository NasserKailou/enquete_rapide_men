# Système d'Authentification - Enquête Rapide

## Vue d'ensemble

Le système d'authentification a été implémenté pour sécuriser l'accès aux formulaires d'enquête et permettre une gestion complète des utilisateurs.

## Fonctionnalités implémentées

### 1. **Authentification obligatoire**
- ✅ Un popup de connexion s'affiche automatiquement lorsqu'un utilisateur non authentifié tente d'accéder à un formulaire
- ✅ Les pages `formulaire.php` et `dashboard.php` sont protégées
- ✅ Session utilisateur sécurisée avec gestion automatique

### 2. **Interface de connexion**
- ✅ Bouton "Connexion" en haut à droite de la page d'accueil
- ✅ Modal de connexion élégant avec validation
- ✅ Affichage des informations utilisateur après connexion
- ✅ Bouton de déconnexion accessible

### 3. **Gestion des utilisateurs (Admin uniquement)**
- ✅ Page complète de gestion des utilisateurs (`gestion_users.php`)
- ✅ CRUD complet (Créer, Lire, Mettre à jour, Supprimer)
- ✅ Interface intuitive avec tableau responsive
- ✅ Filtrage par rôle et statut

### 4. **APIs REST**
- ✅ `api/auth.php` - Gestion de l'authentification (login, logout, check session)
- ✅ `api/users.php` - Gestion CRUD des utilisateurs (réservé admin)
- ✅ `api/stats.php` - Récupération des statistiques en temps réel

### 5. **Statistiques en temps réel**
- ✅ Remplacement des données simulées par des appels API
- ✅ Affichage dynamique des statistiques sur la page d'accueil
- ✅ Animation des chiffres lors du chargement

## Installation et configuration

### Étape 1 : Importer la base de données
```bash
mysql -u root -p < database.sql
```

### Étape 2 : Configurer la connexion
Modifier le fichier `config.php` si nécessaire :
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'enq_rapide_men');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### Étape 3 : Initialiser les utilisateurs
Accéder à : `http://votre-serveur/test_init_db.php`

Ce script créera automatiquement les utilisateurs par défaut.

## Utilisateurs par défaut

| Username | Mot de passe | Rôle | Description |
|----------|-------------|------|-------------|
| `admin` | `admin123` | Administrateur | Accès complet + gestion utilisateurs |
| `operateur_niamey` | `niamey2025` | Saisie | Saisie de données uniquement |
| `consultation` | `consult2025` | Consultation | Lecture seule |

⚠️ **Important** : Changez ces mots de passe par défaut en production !

## Rôles et permissions

### Administrateur (`admin`)
- ✅ Accès à tous les formulaires
- ✅ Gestion complète des utilisateurs
- ✅ Visualisation du tableau de bord
- ✅ Accès aux statistiques

### Saisie (`saisie`)
- ✅ Accès aux formulaires d'enquête
- ✅ Saisie et modification des données
- ❌ Pas d'accès à la gestion des utilisateurs

### Consultation (`consultation`)
- ✅ Visualisation du tableau de bord
- ✅ Accès aux statistiques
- ❌ Pas de saisie de données
- ❌ Pas d'accès à la gestion des utilisateurs

## Flux d'authentification

```
1. Utilisateur clique sur un formulaire (Préscolaire/Primaire/Secondaire)
   ↓
2. Vérification de la session
   ↓
3a. Si connecté → Accès au formulaire
3b. Si non connecté → Popup de connexion
   ↓
4. Saisie des identifiants
   ↓
5. Validation via API (api/auth.php)
   ↓
6. Création de session + redirection vers le formulaire
```

## Sécurité

✅ **Mots de passe hashés** avec `password_hash()` (bcrypt)
✅ **Sessions PHP sécurisées**
✅ **Protection CSRF** sur les formulaires
✅ **Validation des entrées** avec `securise()`
✅ **Logs d'activité** dans la table `logs_activites`
✅ **Contrôle d'accès basé sur les rôles**

## API Endpoints

### Authentication API (`api/auth.php`)

#### POST - Login
```javascript
const formData = new FormData();
formData.append('action', 'login');
formData.append('username', 'admin');
formData.append('password', 'admin123');

fetch('api/auth.php', {
    method: 'POST',
    body: formData
});
```

#### POST - Logout
```javascript
const formData = new FormData();
formData.append('action', 'logout');

fetch('api/auth.php', {
    method: 'POST',
    body: formData
});
```

#### GET - Check Session
```javascript
fetch('api/auth.php?action=check')
    .then(response => response.json())
    .then(data => console.log(data));
```

### Users API (`api/users.php`) - Admin uniquement

#### GET - Liste des utilisateurs
```javascript
fetch('api/users.php')
    .then(response => response.json())
    .then(data => console.log(data.data));
```

#### GET - Un utilisateur
```javascript
fetch('api/users.php?id=1')
    .then(response => response.json())
    .then(data => console.log(data.data));
```

#### POST - Créer un utilisateur
```javascript
fetch('api/users.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        username: 'nouveau_user',
        password: 'password123',
        nom_complet: 'Nouveau Utilisateur',
        email: 'user@education.ne',
        role: 'saisie',
        actif: 1
    })
});
```

#### PUT - Modifier un utilisateur
```javascript
fetch('api/users.php', {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        id: 1,
        nom_complet: 'Nom Modifié',
        email: 'newemail@education.ne'
    })
});
```

#### DELETE - Supprimer un utilisateur
```javascript
fetch('api/users.php', {
    method: 'DELETE',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id: 2 })
});
```

### Stats API (`api/stats.php`)

#### GET - Statistiques globales
```javascript
fetch('api/stats.php')
    .then(response => response.json())
    .then(data => {
        console.log('Total établissements:', data.data.total_etablissements);
        console.log('Total élèves:', data.data.total_eleves);
        console.log('Total enseignants:', data.data.total_enseignants);
    });
```

## Structure des fichiers

```
/webapp
├── api/
│   ├── auth.php          # API d'authentification
│   ├── users.php         # API de gestion utilisateurs
│   └── stats.php         # API des statistiques
├── config.php            # Configuration et connexion DB
├── index.html            # Page d'accueil (avec auth)
├── gestion_users.php     # Page de gestion des utilisateurs
├── formulaire.php        # Formulaires protégés
├── dashboard.php         # Tableau de bord protégé
└── test_init_db.php      # Script d'initialisation
```

## Logs d'activité

Toutes les actions importantes sont enregistrées dans la table `logs_activites` :
- Connexions/déconnexions
- Création d'utilisateurs
- Modifications d'utilisateurs
- Suppressions d'utilisateurs

```sql
SELECT * FROM logs_activites ORDER BY created_at DESC LIMIT 20;
```

## Dépannage

### Problème : "Erreur de connexion à la base de données"
- Vérifier les paramètres dans `config.php`
- S'assurer que MySQL est démarré
- Vérifier les permissions de l'utilisateur MySQL

### Problème : "Nom d'utilisateur ou mot de passe incorrect"
- Vérifier que les utilisateurs ont été créés avec `test_init_db.php`
- S'assurer que le champ `actif` est à 1
- Vérifier les logs dans la table `logs_activites`

### Problème : Session expirée rapidement
- Modifier les paramètres de session dans `php.ini`
- Augmenter `session.gc_maxlifetime`

## Améliorations futures possibles

- [ ] Réinitialisation de mot de passe par email
- [ ] Authentification à deux facteurs (2FA)
- [ ] Gestion des permissions granulaires
- [ ] Interface de gestion des logs
- [ ] Limitation des tentatives de connexion
- [ ] Notifications par email lors de nouvelles connexions

## Support

Pour toute question ou problème, contactez l'équipe de développement.

---

**Version** : 1.0.0  
**Date** : Décembre 2025  
**Auteur** : Équipe Technique MEN Niger
