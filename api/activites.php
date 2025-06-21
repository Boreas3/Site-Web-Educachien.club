<?php
/**
 * API pour récupérer les activités depuis la base de données
 * Retourne les données au format JSON
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Inclure la configuration de la base de données
require_once '../config/database.php';

// Gestion des requêtes OPTIONS (CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Vérifier que la méthode est GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit();
}

try {
    $pdo = getDBConnection();
    
    if (!$pdo) {
        throw new Exception('Impossible de se connecter à la base de données');
    }
    
    // Paramètres de filtrage
    $filtre_actif = isset($_GET['actif']) ? (bool)$_GET['actif'] : true;
    $filtre_type = isset($_GET['type']) ? $_GET['type'] : null;
    $filtre_public = isset($_GET['public']) ? $_GET['public'] : null;
    $limite = isset($_GET['limite']) ? (int)$_GET['limite'] : 50;
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
    
    // Construction de la requête SQL
    $sql = "SELECT * FROM activites WHERE 1=1";
    $params = [];
    
    if ($filtre_actif !== null) {
        $sql .= " AND actif = :actif";
        $params[':actif'] = $filtre_actif;
    }
    
    if ($filtre_type) {
        $sql .= " AND type_activite = :type";
        $params[':type'] = $filtre_type;
    }
    
    if ($filtre_public) {
        $sql .= " AND (public_cible = :public OR public_cible = 'tous')";
        $params[':public'] = $filtre_public;
    }
    
    // Tri par date de début (plus récent en premier)
    $sql .= " ORDER BY date_debut DESC";
    
    // Limitation des résultats
    $sql .= " LIMIT :limite OFFSET :offset";
    $params[':limite'] = $limite;
    $params[':offset'] = $offset;
    
    // Préparation et exécution de la requête
    $stmt = $pdo->prepare($sql);
    
    // Binding des paramètres
    foreach ($params as $key => $value) {
        if ($key === ':limite' || $key === ':offset') {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($key, $value);
        }
    }
    
    $stmt->execute();
    $activites = $stmt->fetchAll();
    
    // Formatage des données pour correspondre à l'ancien format
    $activites_formatees = [];
    
    foreach ($activites as $activite) {
        // Génération d'un ID unique basé sur la date et le titre
        $date_id = date('Y_m_d', strtotime($activite['date_debut']));
        $titre_slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $activite['titre']));
        $unique_id = $date_id . '_' . $titre_slug;
        
        // Formatage des dates pour Moment.js
        $date_debut_iso = date('Ymd\THis', strtotime($activite['date_debut']));
        $date_fin_iso = date('Ymd\THis', strtotime($activite['date_fin']));
        
        $activites_formatees[] = [
            'id' => $activite['id'],
            'unique_id' => $unique_id,
            'titre' => $activite['titre'],
            'description' => $activite['description'],
            'date_debut' => $activite['date_debut'],
            'date_fin' => $activite['date_fin'],
            'date_debut_iso' => $date_debut_iso,
            'date_fin_iso' => $date_fin_iso,
            'lieu' => $activite['lieu'],
            'paf' => $activite['paf'],
            'modalites' => $activite['modalites'],
            'type_activite' => $activite['type_activite'],
            'public_cible' => $activite['public_cible'],
            'actif' => (bool)$activite['actif'],
            'created_at' => $activite['created_at'],
            'updated_at' => $activite['updated_at']
        ];
    }
    
    // Réponse JSON
    echo json_encode([
        'success' => true,
        'data' => $activites_formatees,
        'count' => count($activites_formatees),
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erreur serveur',
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE);
}
?> 