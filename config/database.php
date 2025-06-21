<?php
/**
 * Configuration de la base de données
 * À adapter selon votre environnement local
 */

// Configuration pour l'environnement local
define('DB_HOST', 'localhost');
define('DB_NAME', 'educachien_db');
define('DB_USER', 'root'); // Changez selon votre configuration
define('DB_PASS', ''); // Changez selon votre configuration
define('DB_CHARSET', 'utf8mb4');

// Configuration pour la production (à décommenter et adapter)
// define('DB_HOST', 'votre_host_production');
// define('DB_NAME', 'educachien_db');
// define('DB_USER', 'votre_user_production');
// define('DB_PASS', 'votre_password_production');
// define('DB_CHARSET', 'utf8mb4');

/**
 * Fonction de connexion à la base de données
 */
function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        // En production, ne pas afficher les détails d'erreur
        error_log("Erreur de connexion à la base de données: " . $e->getMessage());
        return false;
    }
}

/**
 * Fonction pour tester la connexion
 */
function testDBConnection() {
    $pdo = getDBConnection();
    if ($pdo) {
        echo "Connexion à la base de données réussie !";
        return true;
    } else {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
}

// Test de connexion (à commenter en production)
// testDBConnection();
?> 