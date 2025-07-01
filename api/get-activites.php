<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once '../config/database.php';

try {
    $pdo = getDBConnection();
    
    if (!$pdo) {
        throw new Exception('Erreur de connexion à la base de données');
    }
    
    // Récupérer toutes les activités actives, triées par date de début (plus récentes en premier)
    $query = "SELECT * FROM activites WHERE actif = 1 ORDER BY date_debut DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    $activites = $stmt->fetchAll();
    
    // Formater les données pour l'affichage
    $formattedActivites = [];
    foreach ($activites as $activite) {
        $formattedActivites[] = [
            'id' => $activite['id'],
            'titre' => $activite['titre'],
            'description' => $activite['description'],
            'date_debut' => $activite['date_debut'],
            'date_fin' => $activite['date_fin'],
            'lieu' => $activite['lieu'],
            'paf' => $activite['paf'],
            'modalites' => $activite['modalites'],
            'type_activite' => $activite['type_activite'],
            'public_cible' => $activite['public_cible']
        ];
    }
    
    echo json_encode($formattedActivites, JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
?> 