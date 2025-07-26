<?php
// Fonctions utilitaires pour la gestion des données
require_once __DIR__ . '/config.php';

/**
 * Récupère toutes les activités depuis la base de données
 */
function getAllActivites() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("
        SELECT * FROM activites 
        ORDER BY date DESC, heure_debut ASC
    ");
    return $stmt->fetchAll();
}

/**
 * Récupère les activités à venir (date >= aujourd'hui)
 */
function getActivitesAVenir() {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        SELECT * FROM activites 
        WHERE date >= CURDATE()
        ORDER BY date ASC, heure_debut ASC
    ");
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Récupère les activités passées (date < aujourd'hui)
 */
function getActivitesPassees() {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        SELECT * FROM activites 
        WHERE date < CURDATE()
        ORDER BY date DESC, heure_debut ASC
    ");
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Récupère les activités par type
 */
function getActivitesByType($type) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        SELECT * FROM activites 
        WHERE type = :type
        ORDER BY date DESC, heure_debut ASC
    ");
    $stmt->execute(['type' => $type]);
    return $stmt->fetchAll();
}

/**
 * Récupère toutes les règles d'annulation
 */
function getAnnulations() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT * FROM annulations ORDER BY critere_type, critere_valeur");
    return $stmt->fetchAll();
}

/**
 * Vérifie si une activité doit être annulée selon les règles
 */
function checkAnnulation($activite) {
    $annulations = getAnnulations();
    
    foreach ($annulations as $regle) {
        if ($regle['critere_type'] === 'type' && $activite['type'] === $regle['critere_valeur']) {
            // Vérifier les conditions de date si elles sont définies
            $shouldCancel = true;
            
            if ($regle['date_debut'] && $regle['date_fin']) {
                $activityDate = $activite['date'];
                $startDate = $regle['date_debut'];
                $endDate = $regle['date_fin'];
                
                // Vérifier si l'activité est dans la période d'annulation
                $shouldCancel = $activityDate >= $startDate && $activityDate <= $endDate;
            }
            
            if ($shouldCancel) {
                return [
                    'cancelled' => true,
                    'message' => $regle['message'] ?: 'ANNULÉ'
                ];
            }
        }
    }
    
    return ['cancelled' => false];
}

/**
 * Formate une date pour l'affichage
 */
function formatDate($date) {
    $timestamp = strtotime($date);
    return date('d/m/Y', $timestamp);
}

/**
 * Formate une heure pour l'affichage
 */
function formatHeure($heure) {
    if (empty($heure) || $heure === '00:00:00') {
        return '';
    }
    return date('H:i', strtotime($heure));
}

/**
 * Formate le type d'activité pour l'affichage
 */
function formatType($type) {
    $types = [
        'balade' => 'Balade éducative',
        'formation' => 'Formation',
        'evenement' => 'Événement',
        'test' => 'Test de sociabilité',
        'travaux' => 'Travaux',
        'jeux' => 'Jeux',
        'demo' => 'Démonstration',
        'salon' => 'Salon',
        'fermeture' => 'Fermeture',
        'rassemblement' => 'Rassemblement',
        'training' => 'Training',
        'conference' => 'Conférence'
    ];
    
    return $types[$type] ?? ucfirst($type);
}

/**
 * Génère le HTML pour une activité
 */
function generateActiviteHTML($activite) {
    $annulation = checkAnnulation($activite);
    $cancelledClass = $annulation['cancelled'] ? 'cancelled' : '';
    $cancelledBadge = $annulation['cancelled'] ? '<span class="badge bg-danger">' . htmlspecialchars($annulation['message']) . '</span>' : '';
    
    $html = '<div class="activity-card ' . $cancelledClass . '">';
    $html .= '<div class="activity-header">';
    $html .= '<h3>' . htmlspecialchars($activite['titre']) . ' ' . $cancelledBadge . '</h3>';
    $html .= '<span class="activity-type">' . formatType($activite['type']) . '</span>';
    $html .= '</div>';
    
    $html .= '<div class="activity-details">';
    $html .= '<p><strong>Date :</strong> ' . formatDate($activite['date']) . '</p>';
    
    if (!empty($activite['heure_debut']) && $activite['heure_debut'] !== '00:00:00') {
        $html .= '<p><strong>Heure :</strong> ' . formatHeure($activite['heure_debut']);
        if (!empty($activite['heure_fin']) && $activite['heure_fin'] !== '00:00:00') {
            $html .= ' - ' . formatHeure($activite['heure_fin']);
        }
        $html .= '</p>';
    }
    
    if (!empty($activite['lieu'])) {
        $html .= '<p><strong>Lieu :</strong> ' . htmlspecialchars($activite['lieu']) . '</p>';
    }
    
    if (!empty($activite['prix'])) {
        $html .= '<p><strong>Prix :</strong> ' . htmlspecialchars($activite['prix']) . '</p>';
    }
    
    if (!empty($activite['description'])) {
        $html .= '<p><strong>Description :</strong> ' . htmlspecialchars($activite['description']) . '</p>';
    }
    
    if (!empty($activite['modalites'])) {
        $html .= '<p><strong>Modalités :</strong> ' . htmlspecialchars($activite['modalites']) . '</p>';
    }
    
    $html .= '</div>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Génère le HTML pour une activité au format accordion (comme l'original)
 */
function generateActiviteAccordionHTML($activite, $section, $index) {
    $annulation = checkAnnulation($activite);
    $cancelledClass = $annulation['cancelled'] ? 'cancelled-activity' : '';
    $cancelledBadge = $annulation['cancelled'] ? '<span class="badge bg-danger">' . htmlspecialchars($annulation['message']) . '</span>' : '';
    
    $accordionId = 'accordion' . ucfirst($section) . $index;
    $collapseId = 'collapse' . ucfirst($section) . $index;
    
    // Déterminer si l'activité est passée
    $isPast = strtotime($activite['date']) < time();
    $pastClass = $isPast ? 'past' : '';
    
    $html = '<div class="accordion-item event-item ' . $cancelledClass . ' ' . $pastClass . '">';
    
    // Header de l'accordion
    $html .= '<h2 class="accordion-header" id="heading' . $accordionId . '">';
    $html .= '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#' . $collapseId . '" aria-expanded="false" aria-controls="' . $collapseId . '">';
    
    // Contenu du header
    $html .= '<div class="row w-100">';
    $html .= '<div class="col-md-3 event-date-column">';
    $html .= '<strong>' . formatDate($activite['date']) . '</strong>';
    if (!empty($activite['heure_debut']) && $activite['heure_debut'] !== '00:00:00') {
        $html .= '<br><small>' . formatHeure($activite['heure_debut']);
        if (!empty($activite['heure_fin']) && $activite['heure_fin'] !== '00:00:00') {
            $html .= ' - ' . formatHeure($activite['heure_fin']);
        }
        $html .= '</small>';
    }
    $html .= '</div>';
    
    $html .= '<div class="col-md-9 event-title-column">';
    $html .= htmlspecialchars($activite['titre']) . ' ' . $cancelledBadge;
    $html .= '<br><small class="text-red">' . formatType($activite['type']) . '</small>';
    $html .= '</div>';
    
    $html .= '</div>';
    $html .= '</button>';
    $html .= '</h2>';
    
    // Contenu de l'accordion
    $html .= '<div id="' . $collapseId . '" class="accordion-collapse collapse" aria-labelledby="heading' . $accordionId . '" data-bs-parent="#accordion' . ucfirst($section) . '">';
    $html .= '<div class="accordion-body">';
    
    // Détails de l'activité
    if (!empty($activite['description'])) {
        $html .= '<p><strong>Description :</strong> ' . htmlspecialchars($activite['description']) . '</p>';
    }
    
    if (!empty($activite['lieu'])) {
        $html .= '<p><strong>Lieu :</strong> ' . htmlspecialchars($activite['lieu']) . '</p>';
    }
    
    if (!empty($activite['prix'])) {
        $html .= '<p><strong>Prix :</strong> ' . htmlspecialchars($activite['prix']) . '</p>';
    }
    
    if (!empty($activite['modalites'])) {
        $html .= '<p><strong>Modalités :</strong> ' . htmlspecialchars($activite['modalites']) . '</p>';
    }
    
    // Liens d'action (calendrier, etc.)
    $html .= '<div class="mt-3">';
    $html .= '<a href="#" class="calendar" onclick="addToCalendar(\'' . $activite['date'] . '\', \'' . htmlspecialchars($activite['titre']) . '\', \'' . htmlspecialchars($activite['description']) . '\', \'' . htmlspecialchars($activite['lieu']) . '\')">';
    $html .= '<i class="bi bi-calendar-plus"></i> Ajouter au calendrier';
    $html .= '</a>';
    $html .= '</div>';
    
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    
    return $html;
}
?> 