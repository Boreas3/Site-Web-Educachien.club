<?php
// Configuration de la base de données
define('DB_HOST', 'educacxadmin.mysql.db');
define('DB_NAME', 'educacxadmin');
define('DB_USER', 'educacxadmin');
define('DB_PASS', '6sVhRCjXYq1FT1Dt1428');


// Configuration des cookies
define('COOKIE_NAME', 'educachien_auth');
define('COOKIE_SECRET', 'educachien_secret_key_2025'); // Changez cette clé en production
define('COOKIE_DURATION', 30 * 24 * 60 * 60); // 30 jours

// Identifiants des utilisateurs
$users = [
    '2305' => [
        'name' => 'Admin',
        'role' => 'admin',
        'email' => 'admin@educachien.club'
    ]
    // Ajoutez d'autres utilisateurs ici
];

// Fonction de connexion à la base de données
function getDBConnection() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}

// Fonction pour créer un cookie d'authentification sécurisé
function createAuthCookie($user_id, $name, $role) {
    $data = [
        'user_id' => $user_id,
        'name' => $name,
        'role' => $role,
        'expires' => time() + COOKIE_DURATION
    ];
    
    $json_data = json_encode($data);
    $signature = hash_hmac('sha256', $json_data, COOKIE_SECRET);
    $cookie_value = base64_encode($json_data . '.' . $signature);
    
    // Déterminer si on est en HTTPS
    $is_secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    
    setcookie(
        COOKIE_NAME,
        $cookie_value,
        time() + COOKIE_DURATION,
        '/',
        '',
        $is_secure,  // Secure seulement en HTTPS
        true   // HttpOnly
    );
}

// Fonction pour vérifier et récupérer les données du cookie
function verifyAuthCookie() {
    if (!isset($_COOKIE[COOKIE_NAME])) {
        return false;
    }
    
    $cookie_value = $_COOKIE[COOKIE_NAME];
    $decoded = base64_decode($cookie_value);
    
    if ($decoded === false) {
        return false;
    }
    
    $parts = explode('.', $decoded);
    if (count($parts) !== 2) {
        return false;
    }
    
    $json_data = $parts[0];
    $signature = $parts[1];
    
    // Vérifier la signature
    $expected_signature = hash_hmac('sha256', $json_data, COOKIE_SECRET);
    if (!hash_equals($expected_signature, $signature)) {
        return false;
    }
    
    $data = json_decode($json_data, true);
    if (!$data || !isset($data['expires']) || $data['expires'] < time()) {
        return false;
    }
    
    return $data;
}

// Fonction pour supprimer le cookie d'authentification
function deleteAuthCookie() {
    // Déterminer si on est en HTTPS
    $is_secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    
    setcookie(
        COOKIE_NAME,
        '',
        time() - 3600,
        '/',
        '',
        $is_secure,  // Secure seulement en HTTPS
        true
    );
}

// Fonction pour initialiser la session et restaurer depuis le cookie si nécessaire
function initSession() {
    // S'assurer que la session est démarrée
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Si pas de session active, essayer de restaurer depuis le cookie
    if (!isset($_SESSION['user_id'])) {
        restoreSessionFromCookie();
    }
}

// Fonction pour restaurer la session à partir du cookie
function restoreSessionFromCookie() {
    $cookie_data = verifyAuthCookie();
    if ($cookie_data) {
        $_SESSION['user_id'] = $cookie_data['user_id'];
        $_SESSION['name'] = $cookie_data['name'];
        $_SESSION['role'] = $cookie_data['role'];
        return true;
    }
    return false;
}

// Fonction pour vérifier si l'utilisateur est connecté
function isLoggedIn() {
    // Initialiser la session si nécessaire
    initSession();
    
    // Vérifier si l'utilisateur est connecté
    return isset($_SESSION['user_id']);
}

// Fonction pour vérifier si l'utilisateur est admin
function isAdmin() {
    // Initialiser la session si nécessaire
    initSession();
    
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Fonction pour sécuriser les données
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?> 