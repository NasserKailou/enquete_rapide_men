# Système d'Enquête Rapide - Rentrée Scolaire 2025-2026
## République du Niger - Ministère de l'Education Nationale

---

## 📋 Description

Application web professionnelle de gestion des enquêtes rapides pour la rentrée scolaire 2025-2026 au Niger. Le système permet de collecter, gérer et analyser les données statistiques des établissements d'enseignement préscolaire, primaire et secondaire général.

### ✨ Fonctionnalités principales

- **Formulaires dynamiques multi-étapes** pour les 3 cycles d'enseignement
- **Base de données MySQL** optimisée avec relations et vues
- **Tableau de bord interactif** avec graphiques et statistiques
- **Sauvegarde automatique** des données pendant la saisie
- **Validation en temps réel** des champs du formulaire
- **Calculs automatiques** des totaux et statistiques
- **Design responsive** adapté mobile, tablette et desktop
- **Interface en français** conforme aux documents officiels

---

## 🚀 Installation

### Prérequis

- **Serveur Web** : Apache 2.4+ ou Nginx
- **PHP** : Version 7.4 ou supérieure
- **MySQL** : Version 5.7+ ou MariaDB 10.3+
- **Extensions PHP requises** :
  - PDO
  - PDO_MySQL
  - mbstring
  - json

### Étape 1 : Configuration de la base de données

```bash
# Se connecter à MySQL
mysql -u root -p

# Créer la base de données et importer le schéma
mysql -u root -p < database.sql
```

Ou via phpMyAdmin :
1. Créer une nouvelle base de données : `enquete_rentree_niger`
2. Importer le fichier `database.sql`

### Étape 2 : Configuration de l'application

Éditer le fichier `config.php` :

```php
define('DB_HOST', 'localhost');      // Hôte de la base de données
define('DB_NAME', 'enquete_rentree_niger');  // Nom de la base
define('DB_USER', 'root');           // Utilisateur MySQL
define('DB_PASS', 'votre_mot_de_passe');     // Mot de passe MySQL
```

### Étape 3 : Déploiement des fichiers

```bash
# Copier tous les fichiers dans le répertoire web
cp -r enquete_education_niger/* /var/www/html/enquete/

# Définir les permissions appropriées
chmod 755 /var/www/html/enquete
chmod 644 /var/www/html/enquete/*.php
```

### Étape 4 : Configuration Apache (facultatif)

Créer un VirtualHost `/etc/apache2/sites-available/enquete.conf` :

```apache
<VirtualHost *:80>
    ServerName enquete.education.ne
    DocumentRoot /var/www/html/enquete
    
    <Directory /var/www/html/enquete>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/enquete_error.log
    CustomLog ${APACHE_LOG_DIR}/enquete_access.log combined
</VirtualHost>
```

Activer le site :
```bash
a2ensite enquete
systemctl reload apache2
```

---

## 📁 Structure des fichiers

```
enquete_education_niger/
│
├── index.html                 # Page d'accueil
├── formulaire.php             # Page principale du formulaire
├── submit_form.php            # Traitement des soumissions
├── dashboard.php              # Tableau de bord
├── config.php                 # Configuration de l'application
├── database.sql               # Schéma de la base de données
│
├── templates/                 # Templates des formulaires
│   ├── form_prescolaire.php
│   ├── form_primaire.php
│   └── form_secondaire.php
│
├── css/                       # Feuilles de style
│   └── formulaire.css
│
├── js/                        # Scripts JavaScript
│   └── formulaire.js
│
└── README.md                  # Documentation
```

---

## 💻 Utilisation

### 1. Page d'accueil

Accéder à l'application via : `http://votre-serveur/enquete/`

L'utilisateur peut choisir parmi 3 types d'enquête :
- **Préscolaire** : Jardins d'Enfants (JE)
- **Primaire** : Écoles du cycle primaire (CI à CM2)
- **Secondaire** : Établissements secondaires (6ème à Terminale)

### 2. Remplissage du formulaire

#### Navigation multi-étapes
- **Bouton Suivant** : Passe à la section suivante (avec validation)
- **Bouton Précédent** : Retourne à la section précédente
- **Bouton Soumettre** : Enregistre l'enquête (dernière étape)

#### Sauvegarde automatique
- Les données sont sauvegardées automatiquement dans le navigateur
- En cas de fermeture accidentelle, les données peuvent être restaurées

#### Validation
- Les champs obligatoires sont marqués d'un astérisque rouge (*)
- Validation en temps réel lors du changement de section
- Messages d'erreur clairs en cas de problème

### 3. Tableau de bord

Accès via : `http://votre-serveur/enquete/dashboard.php`

Fonctionnalités :
- **Statistiques globales** : Nombre d'établissements, élèves, enseignants
- **Graphiques interactifs** :
  - Répartition par cycle
  - Répartition par statut (public/privé)
  - Effectifs par genre
  - Établissements par région
- **Liste des enquêtes** : Dernières soumissions avec détails
- **Filtres** : Par région, cycle, statut

---

## 🗄️ Structure de la base de données

### Tables principales

1. **etablissements** : Informations générales des établissements
2. **effectifs_prescolaire** : Effectifs des jardins d'enfants
3. **effectifs_primaire** : Effectifs des écoles primaires
4. **effectifs_secondaire** : Effectifs des établissements secondaires
5. **personnel_enseignant** : Personnel enseignant
6. **personnel_administratif** : Personnel administratif (secondaire)
7. **salles_classes** : Infrastructures - salles de classe
8. **mobiliers** : Mobiliers et équipements
9. **infrastructures** : Autres infrastructures
10. **besoins** : Besoins identifiés
11. **utilisateurs** : Comptes utilisateurs
12. **logs_activites** : Journaux d'activité

### Vues pour les statistiques

- `v_synthese_par_region` : Synthèse par région
- `v_effectifs_primaire_global` : Effectifs primaire globaux
- `v_effectifs_secondaire_global` : Effectifs secondaire globaux
- `v_personnel_enseignant_global` : Personnel enseignant global

---

## 🎨 Personnalisation

### Couleurs du thème

Modifier les variables CSS dans `css/formulaire.css` :

```css
:root {
    --primary-color: #0C9F57;      /* Vert principal */
    --secondary-color: #E25822;    /* Orange */
    --dark-green: #0A7A43;         /* Vert foncé */
    --orange: #E25822;             /* Orange */
}
```

### Régions et départements

Modifier les tableaux dans `config.php` :

```php
$regions_niger = [
    'Agadez', 'Diffa', 'Dosso', 'Maradi',
    'Niamey', 'Tahoua', 'Tillabéri', 'Zinder'
];

$departements_par_region = [
    'Agadez' => ['Agadez', 'Arlit', 'Bilma', ...],
    // ...
];
```

---

## 🔒 Sécurité

### Bonnes pratiques implémentées

- ✅ Utilisation de **PDO avec requêtes préparées** (protection SQL Injection)
- ✅ **Validation et échappement** des entrées utilisateur
- ✅ **Protection CSRF** (à implémenter pour la production)
- ✅ **Logs d'activité** pour l'audit
- ✅ **Transactions SQL** pour l'intégrité des données

### Recommandations pour la production

1. **Activer HTTPS** avec certificat SSL
2. **Créer un utilisateur MySQL dédié** avec privilèges limités
3. **Implémenter l'authentification** des utilisateurs
4. **Activer les logs d'erreur PHP** sans affichage public
5. **Configurer les sauvegardes** automatiques de la base de données
6. **Limiter l'accès au dashboard** aux utilisateurs autorisés

---

## 📊 Export des données

### Export SQL

```bash
# Export complet de la base
mysqldump -u root -p enquete_rentree_niger > backup_enquete_$(date +%Y%m%d).sql

# Export d'une table spécifique
mysqldump -u root -p enquete_rentree_niger etablissements > etablissements.sql
```

### Export CSV (via phpMyAdmin)

1. Sélectionner la table
2. Cliquer sur "Exporter"
3. Choisir le format CSV
4. Télécharger le fichier

---

## 🐛 Dépannage

### Erreur de connexion à la base de données

**Problème** : "Erreur de connexion à la base de données"

**Solution** :
1. Vérifier les identifiants dans `config.php`
2. S'assurer que MySQL est démarré : `systemctl status mysql`
3. Vérifier les privilèges : `GRANT ALL ON enquete_rentree_niger.* TO 'user'@'localhost';`

### Formulaire ne se soumet pas

**Problème** : Le bouton "Soumettre" ne fonctionne pas

**Solution** :
1. Vérifier la console JavaScript (F12) pour les erreurs
2. S'assurer que tous les champs obligatoires sont remplis
3. Vérifier les permissions du fichier `submit_form.php`

### Graphiques ne s'affichent pas

**Problème** : Le tableau de bord est vide

**Solution** :
1. Vérifier que Chart.js est bien chargé (connexion internet)
2. S'assurer qu'il y a des données dans la base
3. Vérifier les erreurs PHP dans les logs

---

## 📞 Support et Contact

**Direction des Statistiques (DS/PTICE)**  
Ministère de l'Education Nationale du Niger

📧 Email : support@education.ne  
☎️ Téléphone : +227 XX XX XX XX  
🌐 Site web : www.education.ne

---

## 📝 Licence

© 2025 Ministère de l'Education Nationale du Niger  
Tous droits réservés.

Cette application est destinée à un usage officiel uniquement pour les besoins du Ministère de l'Education Nationale de la République du Niger.

---

## 🔄 Mises à jour

### Version 1.0.0 (Janvier 2025)
- Version initiale
- Formulaires pour les 3 cycles
- Tableau de bord avec statistiques
- Base de données complète

---

## 👥 Crédits

Développé pour le Ministère de l'Education Nationale du Niger  
Direction des Statistiques, de la Promotion des Technologies de l'Information et de la Communication pour l'Enseignement (DS/PTICE)
