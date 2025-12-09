<?php
/**
 * Configuration de l'application
 * Enquête Rapide Rentrée Scolaire 2025-2026
 * Ministère de l'Education Nationale du Niger
 */

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'enq_rapide_men');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Configuration de l'application
define('APP_NAME', 'Enquête Rapide - Rentrée Scolaire 2025-2026');
define('APP_VERSION', '1.0.0');
define('ANNEE_SCOLAIRE', '2025-2026');

// Fuseau horaire
date_default_timezone_set('Africa/Niamey');

// Démarrage de la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Classe de connexion à la base de données
class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch(PDOException $e) {
            die("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
}

// Fonction pour sécuriser les entrées
function securise($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Fonction pour générer un code unique
function genererCodeEtablissement($cycle, $region) {
    $prefixes = [
        'prescolaire' => 'PRE',
        'primaire' => 'PRI',
        'secondaire' => 'SEC'
    ];
    
    $prefix = $prefixes[$cycle] ?? 'ETB';
    $regionCode = strtoupper(substr($region, 0, 3));
    $timestamp = date('ymd');
    $random = sprintf('%04d', mt_rand(0, 9999));
    
    return $prefix . '-' . $regionCode . '-' . $timestamp . '-' . $random;
}

// Fonction pour enregistrer les logs d'activités
function logActivite($utilisateur_id, $action, $table_concernee = null, $id_enregistrement = null, $details = null) {
    $db = Database::getInstance()->getConnection();
    
    $sql = "INSERT INTO logs_activites (utilisateur_id, action, table_concernee, id_enregistrement, details, ip_address) 
            VALUES (:utilisateur_id, :action, :table_concernee, :id_enregistrement, :details, :ip_address)";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([
        'utilisateur_id' => $utilisateur_id,
        'action' => $action,
        'table_concernee' => $table_concernee,
        'id_enregistrement' => $id_enregistrement,
        'details' => $details,
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
    ]);
}

// Listes des régions du Niger
$regions_niger = [
    'Agadez',
    'Diffa',
    'Dosso',
    'Maradi',
    'Niamey',
    'Tahoua',
    'Tillabéri',
    'Zinder'
];

// Départements par région (exemple simplifié)
$departements_par_region = [
    'Agadez' => ['Agadez', 'Arlit', 'Bilma', 'Tchirozérine'],
    'Diffa' => ['Diffa', 'Maïné-Soroa', 'N\'Guigmi'],
    'Dosso' => ['Dosso', 'Boboye', 'Dogondoutchi', 'Gaya', 'Loga'],
    'Maradi' => ['Maradi', 'Aguié', 'Dakoro', 'Guidan Roumdji', 'Madarounfa', 'Mayahi', 'Tessaoua'],
    'Niamey' => ['Niamey I', 'Niamey II', 'Niamey III', 'Niamey IV', 'Niamey V'],
    'Tahoua' => ['Tahoua', 'Abalak', 'Birni N\'Konni', 'Bouza', 'Illela', 'Keita', 'Madaoua', 'Malbaza', 'Tahoua', 'Tchintabaraden'],
    'Tillabéri' => ['Tillabéri', 'Ayorou', 'Balleyara', 'Filingué', 'Kollo', 'Ouallam', 'Say', 'Téra', 'Torodi'],
    'Zinder' => ['Zinder', 'Damagaram Takaya', 'Gouré', 'Kantché', 'Magaria', 'Mirriah', 'Tanout']
];

// Niveaux d'enseignement
$niveaux_primaire = ['CI', 'CP', 'CE1', 'CE2', 'CM1', 'CM2'];
$sections_prescolaire = ['Section 1', 'Section 2', 'Unique', 'Jumelés'];

$niveaux_secondaire_college = ['6ème', '5ème', '4ème', '3ème'];
$niveaux_secondaire_lycee = ['2nde A', '2nde C', '1ère A', '1ère C', '1ère D', 'Tle A', 'Tle C', 'Tle D'];

// Catégories de personnel enseignant
$categories_enseignants = [
    'Fonctionnaire' => ['I', 'IA'],
    'Contractuel du public' => ['I', 'IA'],
    'Maître Communautaire' => ['I', 'IA'],
    'Fonctionnaire au privé' => ['I', 'IA'],
    'Contractuel de l\'état au privé' => ['I', 'IA'],
    'Enseignant du privé' => ['I', 'IA'],
    'Autre' => [],
    'Aucun' => []
];

// Disciplines secondaire
$disciplines_secondaire = [
    'FR', 'FR/HG', 'H-G', 'Anglais', 'Etude. Islam', 'L. Arabe', 'Philo',
    'Maths', 'M/PC', 'M/SVT', 'PC/SVT', 'PC', 'SVT', 'EF', 'EPS', 'ASCN'
];

// Personnel administratif secondaire
$postes_administratifs = [
    'Directeur / Proviseur',
    'Censeur(s)',
    'Surveillant(s) général(aux)',
    'Sécretaire(s)',
    'Bibliothécaire',
    'Laborantin',
    'Planton',
    'Gardien',
    'Manœuvre',
    'Intendant/Econome',
    'Autre'
];
?>
