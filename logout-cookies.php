<?php
session_start();
require_once 'includes/config.php';

// Vérifier que c'est une requête AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    switch ($_POST['action']) {
        case 'delete_auth_cookies':
            // Supprimer le cookie d'authentification
            deleteAuthCookie();
            
            // Détruire la session
            session_destroy();
            
            echo json_encode(['success' => true, 'message' => 'Cookies d\'authentification supprimés']);
            break;
            
        case 'check_auth_status':
            $is_logged_in = isLoggedIn();
            echo json_encode(['logged_in' => $is_logged_in]);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Action non reconnue']);
    }
    exit();
}

// Si ce n'est pas une requête AJAX, rediriger
header('Location: index.php');
exit();
?> 