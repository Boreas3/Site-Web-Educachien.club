<?php
/**
 * Script de test pour vérifier le bon fonctionnement du système
 * Accédez à ce fichier via votre navigateur pour voir les résultats
 */

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Test du système Educachien</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { background-color: #d4edda; border-color: #c3e6cb; color: #155724; }
        .error { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .warning { background-color: #fff3cd; border-color: #ffeaa7; color: #856404; }
        pre { background-color: #f8f9fa; padding: 10px; border-radius: 3px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Test du système de gestion des activités - Educachien</h1>";

// Test 1: Vérification de PHP
echo "<div class='test-section success'>
    <h3>✅ Test 1: Version PHP</h3>
    <p>Version PHP détectée: " . phpversion() . "</p>
    <p>Extensions requises:</p>
    <ul>";

$required_extensions = ['pdo', 'pdo_mysql', 'json'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<li>✅ $ext</li>";
    } else {
        echo "<li>❌ $ext (manquante)</li>";
    }
}
echo "</ul></div>";

// Test 2: Test de connexion à la base de données
echo "<div class='test-section'>";
echo "<h3>Test 2: Connexion à la base de données</h3>";

try {
    require_once 'config/database.php';
    $pdo = getDBConnection();
    
    if ($pdo) {
        echo "<p class='success'>✅ Connexion à la base de données réussie</p>";
        
        // Test de la table activites
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM activites");
        $result = $stmt->fetch();
        echo "<p>📊 Nombre d'activités dans la base: " . $result['count'] . "</p>";
        
        // Afficher quelques activités
        $stmt = $pdo->query("SELECT id, titre, date_debut, type_activite FROM activites ORDER BY date_debut DESC LIMIT 3");
        $activites = $stmt->fetchAll();
        
        if ($activites) {
            echo "<p>📋 Dernières activités:</p><ul>";
            foreach ($activites as $activite) {
                echo "<li>ID {$activite['id']}: {$activite['titre']} ({$activite['type_activite']}) - " . date('d/m/Y', strtotime($activite['date_debut'])) . "</li>";
            }
            echo "</ul>";
        }
        
    } else {
        echo "<p class='error'>❌ Échec de la connexion à la base de données</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Erreur: " . $e->getMessage() . "</p>";
}
echo "</div>";

// Test 3: Test de l'API
echo "<div class='test-section'>";
echo "<h3>Test 3: API des activités</h3>";

$api_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/api/activites.php';

try {
    $context = stream_context_create([
        'http' => [
            'timeout' => 10,
            'ignore_errors' => true
        ]
    ]);
    
    $response = file_get_contents($api_url, false, $context);
    
    if ($response !== false) {
        $data = json_decode($response, true);
        
        if ($data && isset($data['success'])) {
            echo "<p class='success'>✅ API fonctionnelle</p>";
            echo "<p>📊 Nombre d'activités retournées: " . $data['count'] . "</p>";
            echo "<p>🕒 Timestamp: " . $data['timestamp'] . "</p>";
            
            // Afficher un exemple d'activité
            if (!empty($data['data'])) {
                $activite = $data['data'][0];
                echo "<p>📋 Exemple d'activité:</p>";
                echo "<pre>" . json_encode($activite, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
            }
        } else {
            echo "<p class='error'>❌ Réponse API invalide</p>";
            echo "<pre>$response</pre>";
        }
    } else {
        echo "<p class='error'>❌ Impossible d'accéder à l'API</p>";
        echo "<p>URL testée: $api_url</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Erreur lors du test de l'API: " . $e->getMessage() . "</p>";
}
echo "</div>";

// Test 4: Vérification des fichiers
echo "<div class='test-section'>";
echo "<h3>Test 4: Vérification des fichiers</h3>";

$required_files = [
    'config/database.php' => 'Configuration de la base de données',
    'api/activites.php' => 'API des activités',
    'assets/scripts/load-activites.js' => 'Script JavaScript de chargement',
    'activites-new.html' => 'Nouvelle page des activités',
    'database.sql' => 'Script de création de la base de données'
];

foreach ($required_files as $file => $description) {
    if (file_exists($file)) {
        echo "<p>✅ $description ($file)</p>";
    } else {
        echo "<p class='error'>❌ $description ($file) - Fichier manquant</p>";
    }
}
echo "</div>";

// Test 5: Recommandations
echo "<div class='test-section warning'>";
echo "<h3>📋 Recommandations</h3>";

echo "<h4>Pour les tests locaux:</h4>";
echo "<ol>";
echo "<li>Assurez-vous que votre serveur web (Apache/Nginx) est démarré</li>";
echo "<li>Vérifiez que MySQL/MariaDB est en cours d'exécution</li>";
echo "<li>Testez l'API directement: <a href='api/activites.php' target='_blank'>api/activites.php</a></li>";
echo "<li>Testez la page des activités: <a href='activites-new.html' target='_blank'>activites-new.html</a></li>";
echo "</ol>";

echo "<h4>Pour la production:</h4>";
echo "<ol>";
echo "<li>Changez les identifiants de base de données dans config/database.php</li>";
echo "<li>Créez un utilisateur MySQL dédié avec des permissions limitées</li>";
echo "<li>Protégez le dossier config/ contre l'accès direct</li>";
echo "<li>Activez HTTPS</li>";
echo "<li>Configurez les logs d'erreur PHP</li>";
echo "</ol>";
echo "</div>";

// Test 6: Liens utiles
echo "<div class='test-section'>";
echo "<h3>🔗 Liens utiles</h3>";
echo "<ul>";
echo "<li><a href='api/activites.php' target='_blank'>API des activités (JSON)</a></li>";
echo "<li><a href='api/activites.php?limite=5' target='_blank'>API avec limite (5 activités)</a></li>";
echo "<li><a href='api/activites.php?type=balade' target='_blank'>API filtrée par type (balades)</a></li>";
echo "<li><a href='activites-new.html' target='_blank'>Page des activités (nouvelle version)</a></li>";
echo "<li><a href='README-INSTALLATION.md' target='_blank'>Guide d'installation</a></li>";
echo "</ul>";
echo "</div>";

echo "</body></html>";
?> 