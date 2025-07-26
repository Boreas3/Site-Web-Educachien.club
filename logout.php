<?php
session_start();
require_once 'includes/config.php';

// Supprimer le cookie d'authentification
deleteAuthCookie();

// Détruire toutes les variables de session
$_SESSION = array();

// Détruire la session
session_destroy();

// Rediriger vers la page de connexion
header('Location: login.php');
exit();
?> 