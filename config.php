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
    "Agadez",
    "Diffa",
    "Dosso",
    "Maradi",
    "Niamey",
    "Tahoua",
    "Tillabery",
    "Zinder",
];

// Départements par région
$departements_par_region = [
    "Agadez" => ["Aderbisanat", "Agadez  Ville", "Arlit", "Bilma", "Iferouane", "Ingall", "Tchirozerine"],
    "Diffa" => ["Bosso", "Diffa", "Goudoumaria", "Maine Soroa", "N'Gourti", "N'Guigmi"],
    "Dosso" => ["Boboye", "Dioundiou", "Dogondoutchi", "Dosso", "Falmey", "Gaya", "Loga", "Tibiri"],
    "Maradi" => ["Aguie", "Bermo", "Dakoro", "Gazaoua", "Guidan-Roumdji", "Madarounfa", "Mayahi", "Tessaoua", "Ville  De Maradi"],
    "Niamey" => ["Niamey Ville"],
    "Tahoua" => ["Abalak", "Bagaroua", "Birni N'Konni", "Bouza", "Illela", "Keita", "Madaoua", "Malbaza", "Tahoua Departement", "Tassara", "Tchintabaraden", "Tillia", "Ville De Tahoua"],
    "Tillabery" => ["Abala", "Ayorou", "Balleyara", "Banibangou", "Bankilare", "Filingue", "Gotheye", "Kollo", "Ouallam", "Say", "Tera", "Tillaberi", "Torodi"],
    "Zinder" => ["Belbedji", "Damagaram Takaya", "Dungass", "Goure", "Kantche", "Magaria", "Mirriah", "Takeita", "Tanout", "Tesker", "Ville De Zinder"],
];

// Communes par département
$communes_par_departement = [
    "Abala" => [
        "Abala",
        "Sanam",
    ],
    "Abalak" => [
        "Abalak",
        "Akoubounou",
        "Azeye",
        "Tabalak",
        "Tamaya",
    ],
    "Aderbisanat" => [
        "Adebissanat",
    ],
    "Agadez  Ville" => [
        "Agadez  Commune",
    ],
    "Aguie" => [
        "Aguie",
        "Tchadoua",
    ],
    "Arlit" => [
        "Arlit",
        "Dannet",
        "Gougaram",
    ],
    "Ayorou" => [
        "Ayorou",
        "Inattes",
    ],
    "Bagaroua" => [
        "Bagaroua",
    ],
    "Balleyara" => [
        "Tagazar",
    ],
    "Banibangou" => [
        "Banibangou",
    ],
    "Bankilare" => [
        "Bankilare",
    ],
    "Belbedji" => [
        "Tarka",
    ],
    "Bermo" => [
        "Bermo",
        "Gadabedji",
    ],
    "Bilma" => [
        "Bilma",
        "Dirkou",
        "Djado",
        "Fachi",
    ],
    "Birni N'Konni" => [
        "Allela",
        "Bazaga",
        "Birni N'Konni",
        "Tsernaoua",
    ],
    "Boboye" => [
        "Birni N'Gaoure",
        "Fabidji",
        "Fakara",
        "Harika-Nassou",
        "Kankandi",
        "Kiota",
        "Koygolo",
        "N'Gonga",
    ],
    "Bosso" => [
        "Bosso",
        "Toumour",
    ],
    "Bouza" => [
        "Allakeye",
        "Baban Katami",
        "Bouza",
        "Deoule",
        "Karofane",
        "Tabotaki",
        "Tama",
    ],
    "Dakoro" => [
        "Adjiekoria",
        "Azagor",
        "Bader Goula",
        "Birnin Lalle",
        "Dakoro",
        "Dan Goulbi",
        "Korahane",
        "Kornaka",
        "Maiyara",
        "Roumbou",
        "Sabonmachi",
        "Tagriss",
    ],
    "Damagaram Takaya" => [
        "Alberkaram",
        "Damagaram Takaya",
        "Guidimouni",
        "Kagna Wame",
        "Mazamni",
        "Moa",
    ],
    "Diffa" => [
        "Chetimari",
        "Diffa  Commune",
        "Gueskerou",
    ],
    "Dioundiou" => [
        "Dioundiou",
        "Kara Kara",
        "Zabori",
    ],
    "Dogondoutchi" => [
        "Dan Kassari",
        "Dogon Kiria",
        "Dogondoutchi",
        "Kieche",
        "Matankari",
        "Soucoucoutane",
    ],
    "Dosso" => [
        "Dosso Commune",
        "Farrey",
        "Garankedeye",
        "Golle",
        "Gorouban Kassam",
        "Kargui Bangou",
        "Mokko",
        "Sakadamna",
        "Sambera",
        "Tessa",
        "Tombo Koarey",
    ],
    "Dungass" => [
        "Dogo Dogo",
        "Dungass",
        "Gouchi",
        "Mallaoua",
    ],
    "Falmey" => [
        "Falmey",
        "Guilladje",
    ],
    "Filingue" => [
        "Damana",
        "Filingue",
        "Imanan",
        "Kourfeye Centre",
    ],
    "Gaya" => [
        "Bana",
        "Bengou",
        "Gaya",
        "Tanda",
        "Tounouga",
        "Yelou",
    ],
    "Gazaoua" => [
        "Gangara",
        "Gazaoua",
    ],
    "Gotheye" => [
        "Dargol",
        "Gotheye",
    ],
    "Goudoumaria" => [
        "Goudoumaria",
    ],
    "Goure" => [
        "Alakos",
        "Boune",
        "Gamou",
        "Goure",
        "Guidiguir",
        "Kelle",
    ],
    "Guidan-Roumdji" => [
        "Chadakori",
        "Guidan Roumdji",
        "Guidan Sori",
        "Sae Saboua",
        "Tibiri (Maradi)",
    ],
    "Iferouane" => [
        "Iferouane",
        "Tmia",
    ],
    "Illela" => [
        "Badaguichiri",
        "Illela",
        "Tajae",
    ],
    "Ingall" => [
        "Ingall",
    ],
    "Kantche" => [
        "Dan Barto",
        "Daoutche",
        "Doungou",
        "Ichernaoua",
        "Kantche",
        "Kourni",
        "Matameye",
        "Tsouni",
        "Yaouri",
    ],
    "Keita" => [
        "Garhanga",
        "Ibohamane",
        "Keita",
        "Tamaske",
    ],
    "Kollo" => [
        "Bitinkodji",
        "Dantchandou",
        "Hamdallaye",
        "Karma",
        "Kirtachi",
        "Kollo",
        "Koure",
        "Libore",
        "N'Dounga",
        "Namaro",
        "Youri",
    ],
    "Loga" => [
        "Falwel",
        "Loga",
        "Sokorbe",
    ],
    "Madaoua" => [
        "Azarori",
        "Bangui",
        "Galma Koudawatche",
        "Madaoua",
        "Ourno",
        "Sabon Guida",
    ],
    "Madarounfa" => [
        "Dan Issa",
        "Djirataoua",
        "Gabi",
        "Madarounfa",
        "Safo",
        "Serki Yama",
    ],
    "Magaria" => [
        "Bande",
        "Dan Tchio",
        "Kouaya",
        "Magaria",
        "Sassoumbroum",
        "Wacha",
        "Yekoua",
    ],
    "Maine Soroa" => [
        "Foulateri",
        "Maine Soroa",
        "N'Guelbeyli",
    ],
    "Malbaza" => [
        "Dogueraoua",
        "Malbaza",
    ],
    "Mayahi" => [
        "Attantane",
        "El Allassan Mairerey",
        "Guidan Amoumoune",
        "Issawane",
        "Kanambakache",
        "Mayahi",
        "Serkin Haoussa",
        "Tchake",
    ],
    "Mirriah" => [
        "Dala Koleram",
        "Dogo",
        "Droum",
        "Gaffati",
        "Gouna",
        "Hamdara",
        "Mirriah",
        "Zermou",
    ],
    "N'Gourti" => [
        "N'Gourti",
    ],
    "N'Guigmi" => [
        "Kabelewa",
        "N'Guigmi",
    ],
    "Niamey Ville" => [
        "Niamey I",
        "Niamey Ii",
        "Niamey Iii",
        "Niamey Iv",
        "Niamey V",
    ],
    "Ouallam" => [
        "Dingazi Banda",
        "Ouallam",
        "Simiri",
        "Tondikiwindi",
    ],
    "Say" => [
        "Ouro Gueladio",
        "Say",
        "Tamou",
    ],
    "Tahoua Departement" => [
        "Afala",
        "Bambeye",
        "Barmou",
        "Kalfou",
        "Takanamatt",
        "Tebaram",
    ],
    "Takeita" => [
        "Dakoussa",
        "Garagoumsa",
        "Tirmini",
    ],
    "Tanout" => [
        "Falenco",
        "Gangara",
        "Ollelewa",
        "Tanout",
        "Tenhya",
    ],
    "Tassara" => [
        "Tassara",
    ],
    "Tchintabaraden" => [
        "Kao",
        "Tchintabaraden",
    ],
    "Tchirozerine" => [
        "Dabaga",
        "Tabelot",
        "Tchirozerine",
    ],
    "Tera" => [
        "Diagourou",
        "Goroual",
        "Kokorou",
        "Mehana",
        "Tera",
    ],
    "Tesker" => [
        "Tesker",
    ],
    "Tessaoua" => [
        "Baoudeta",
        "Hawandawaki",
        "Koona",
        "Korgom",
        "Maijirgui",
        "Ourafane",
        "Tessaoua",
    ],
    "Tibiri" => [
        "Doumega",
        "Guecheme",
        "Kore Mairoua",
        "Tibiri (Dogondoutchi)",
    ],
    "Tillaberi" => [
        "Anzourou",
        "Bibiyergou",
        "Dessa",
        "Kourteye",
        "Sakoira",
        "Sinder",
        "Tillaberi",
    ],
    "Tillia" => [
        "Tillia",
    ],
    "Torodi" => [
        "Makalondi",
        "Torodi",
    ],
    "Ville  De Maradi" => [
        "Maradi 1",
        "Maradi 2",
        "Maradi 3",
    ],
    "Ville De Tahoua" => [
        "Tahoua Commune 1",
        "Tahoua Commune 2",
    ],
    "Ville De Zinder" => [
        "Zinder I",
        "Zinder Ii",
        "Zinder Iii",
        "Zinder Iv",
        "Zinder V",
    ],
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
    'Maths', 'M/PC', 'M/SVT', 'PC/SVT', 'PC', 'SVT', 'EF', 'EPS'
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
