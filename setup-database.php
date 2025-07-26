<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'educachien_club');
define('DB_USER', 'root');
define('DB_PASS', '');

echo "<h1>Configuration de la Base de Données</h1>";

try {
    // Connexion à MySQL sans spécifier de base de données
    $pdo = new PDO("mysql:host=" . DB_HOST . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>✅ Connexion à MySQL réussie</p>";
    
    // Créer la base de données si elle n'existe pas
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "<p>✅ Base de données '" . DB_NAME . "' créée ou vérifiée</p>";
    
    // Se connecter à la base de données spécifique
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    
    // Créer la table des membres avec le champ nom du chien
    $sql_members = "CREATE TABLE IF NOT EXISTS members (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id VARCHAR(50) NOT NULL,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255),
        dog_name VARCHAR(255),
        role ENUM('member', 'admin') DEFAULT 'member',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql_members);
    echo "<p>✅ Table 'members' créée</p>";
    
    // Vérifier si le champ dog_name existe, sinon l'ajouter
    $stmt = $pdo->query("SHOW COLUMNS FROM members LIKE 'dog_name'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE members ADD COLUMN dog_name VARCHAR(255) AFTER email");
        echo "<p>✅ Champ 'dog_name' ajouté à la table 'members'</p>";
    } else {
        echo "<p>✅ Champ 'dog_name' existe déjà</p>";
    }
    
    // Vérifier et supprimer la contrainte UNIQUE sur user_id si elle existe
    try {
        $stmt = $pdo->query("SHOW CREATE TABLE members");
        $create_table = $stmt->fetch(PDO::FETCH_ASSOC);
        $table_structure = $create_table['Create Table'];
        
        if (strpos($table_structure, 'UNIQUE KEY') !== false) {
            // Supprimer la contrainte UNIQUE sur user_id
            $pdo->exec("ALTER TABLE members DROP INDEX user_id");
            echo "<p>✅ Contrainte UNIQUE supprimée de la table members</p>";
        } else {
            echo "<p>✅ Aucune contrainte UNIQUE trouvée sur user_id</p>";
        }
    } catch (Exception $e) {
        echo "<p>ℹ️ Pas de contrainte UNIQUE à supprimer</p>";
    }
    
    // Insérer l'admin par défaut (éviter les doublons)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM members WHERE user_id = ?");
    $stmt->execute(['2305']);
    if ($stmt->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO members (user_id, name, dog_name, role) VALUES (?, ?, ?, ?)");
        $stmt->execute(['2305', 'Martin Heylen', 'Lenka', 'admin']);
        echo "<p>✅ Compte admin (2305) créé</p>";
    } else {
        echo "<p>✅ Compte admin (2305) existe déjà</p>";
    }
    
    // Créer la table des documents (nouvelle structure)
    $sql_documents = "CREATE TABLE IF NOT EXISTS documents (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        filename VARCHAR(255) NOT NULL,
        filepath VARCHAR(500) NOT NULL,
        category VARCHAR(100) NOT NULL,
        access_level ENUM('public', 'member', 'admin', 'archived') DEFAULT 'member',
        description TEXT,
        upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        file_size INT,
        file_type VARCHAR(50),
        active BOOLEAN DEFAULT TRUE
    )";
    $pdo->exec($sql_documents);
    echo "<p>✅ Table 'documents' créée avec la nouvelle structure</p>";
    
    // Vérifier si la table existe déjà avec l'ancienne structure et la mettre à jour
    $stmt = $pdo->query("SHOW COLUMNS FROM documents LIKE 'filepath'");
    if ($stmt->rowCount() == 0) {
        // Ajouter les nouvelles colonnes
        $pdo->exec("ALTER TABLE documents ADD COLUMN filepath VARCHAR(500) AFTER filename");
        $pdo->exec("ALTER TABLE documents ADD COLUMN description TEXT AFTER access_level");
        $pdo->exec("ALTER TABLE documents ADD COLUMN upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER description");
        $pdo->exec("ALTER TABLE documents ADD COLUMN updated_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER upload_date");
        $pdo->exec("ALTER TABLE documents ADD COLUMN file_size INT AFTER updated_date");
        $pdo->exec("ALTER TABLE documents ADD COLUMN file_type VARCHAR(50) AFTER file_size");
        $pdo->exec("ALTER TABLE documents ADD COLUMN active BOOLEAN DEFAULT TRUE AFTER file_type");
        echo "<p>✅ Structure de la table 'documents' mise à jour</p>";
    }
    
    // Mettre à jour l'ENUM access_level pour inclure 'archived'
    try {
        $pdo->exec("ALTER TABLE documents MODIFY COLUMN access_level ENUM('public', 'member', 'admin', 'archived') DEFAULT 'member'");
        echo "<p>✅ Niveau d'accès 'archived' ajouté à la table documents</p>";
    } catch (Exception $e) {
        echo "<p>ℹ️ La table documents a déjà le niveau d'accès 'archived' ou n'existe pas encore</p>";
    }
    
    // Insérer les documents existants avec la nouvelle structure
    $existing_documents = [
        [
            'title' => 'Fiche d\'inscription',
            'filename' => 'Fiche inscription.pdf',
            'filepath' => 'assets/documents/01-formulaires/Fiche inscription.pdf',
            'category' => 'formulaires',
            'access_level' => 'member',
            'description' => 'Formulaire d\'inscription au club'
        ],
        [
            'title' => 'Fiche Test Soc 2023',
            'filename' => 'Fiche inscription Test soc 2023.pdf',
            'filepath' => 'assets/documents/01-formulaires/Fiche inscription Test soc 2023.pdf',
            'category' => 'formulaires',
            'access_level' => 'member',
            'description' => 'Formulaire pour le test de sociabilité 2023'
        ],
        [
            'title' => 'Fiche Test Soc 2024',
            'filename' => 'Fiche inscription Test soc 2024.pdf',
            'filepath' => 'assets/documents/01-formulaires/Fiche inscription Test soc 2024.pdf',
            'category' => 'formulaires',
            'access_level' => 'member',
            'description' => 'Formulaire pour le test de sociabilité 2024'
        ],
        [
            'title' => 'Fiche Test Soc 2025',
            'filename' => 'Fiche inscription Test soc 2025.pdf',
            'filepath' => 'assets/documents/01-formulaires/Fiche inscription Test soc 2025.pdf',
            'category' => 'formulaires',
            'access_level' => 'member',
            'description' => 'Formulaire pour le test de sociabilité 2025'
        ],
        [
            'title' => 'Test de sociabilité 2024',
            'filename' => 'TS-2024.pdf',
            'filepath' => 'assets/documents/06-divers/TS-2024.pdf',
            'category' => 'divers',
            'access_level' => 'member',
            'description' => 'Règlement du test de sociabilité 2024'
        ],
        [
            'title' => 'Règlement muselière',
            'filename' => 'Muselière.pdf',
            'filepath' => 'assets/documents/04-reglements/Muselière.pdf',
            'category' => 'reglements',
            'access_level' => 'member',
            'description' => 'Règlement concernant les muselières'
        ],
        [
            'title' => 'Animations 2023 hiver',
            'filename' => 'Animations-2023-hiver.pdf',
            'filepath' => 'assets/documents/05-animations/Animations-2023-hiver.pdf',
            'category' => 'animations',
            'access_level' => 'member',
            'description' => 'Programme des animations hivernales 2023'
        ],
        [
            'title' => 'Animations 2024 fin année',
            'filename' => 'Animations-2024-fin-année.pdf',
            'filepath' => 'assets/documents/05-animations/Animations-2024-fin-année.pdf',
            'category' => 'animations',
            'access_level' => 'member',
            'description' => 'Programme des animations de fin d\'année 2024'
        ]
    ];
    
    // Insérer les documents existants (éviter les doublons)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM documents WHERE filename = ? AND filepath = ?");
    $insert_stmt = $pdo->prepare("INSERT INTO documents (title, filename, filepath, category, access_level, description) VALUES (?, ?, ?, ?, ?, ?)");
    
    $count = 0;
    $skipped = 0;
    foreach ($existing_documents as $doc) {
        $stmt->execute([$doc['filename'], $doc['filepath']]);
        if ($stmt->fetchColumn() == 0) {
            $insert_stmt->execute([
                $doc['title'],
                $doc['filename'],
                $doc['filepath'],
                $doc['category'],
                $doc['access_level'],
                $doc['description']
            ]);
            $count++;
        } else {
            $skipped++;
        }
    }
    echo "<p>✅ $count documents ajoutés, $skipped documents déjà existants (évités)</p>";
    
    // Créer la table activites (pour les activités du club)
    $sql_activites = "CREATE TABLE IF NOT EXISTS activites (
        id INT AUTO_INCREMENT PRIMARY KEY,
        date DATE NOT NULL,
        heure_debut TIME NOT NULL,
        heure_fin TIME NOT NULL,
        titre VARCHAR(255) NOT NULL,
        description TEXT,
        lieu VARCHAR(255),
        prix VARCHAR(100),
        modalites TEXT,
        type VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_date (date),
        INDEX idx_type (type)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    $pdo->exec($sql_activites);
    echo "<p>✅ Table 'activites' créée</p>";
    
    // Créer la table annulations (pour les règles d'annulation)
    $sql_annulations = "CREATE TABLE IF NOT EXISTS annulations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        critere_type VARCHAR(50) NOT NULL,
        critere_valeur VARCHAR(255) NOT NULL,
        date_debut DATE,
        date_fin DATE,
        message TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_critere (critere_type, critere_valeur)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    $pdo->exec($sql_annulations);
    echo "<p>✅ Table 'annulations' créée</p>";
    
    // Importer les données CSV
    echo "<h3>📊 Import des données CSV...</h3>";
    
    // Fonction pour parser les CSV
    function parseCSV($file) {
        $data = [];
        $handle = fopen($file, 'r');
        
        if ($handle === false) {
            return [];
        }
        
        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            return [];
        }
        
        $headers = array_map(function($header) {
            return trim(str_replace('"', '', $header));
        }, $headers);
        
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) === count($headers)) {
                $data[] = array_combine($headers, $row);
            }
        }
        
        fclose($handle);
        return $data;
    }
    
    // Importer les activités (éviter les doublons)
    $activites_csv = 'assets/data/activites.csv';
    if (file_exists($activites_csv)) {
        $data = parseCSV($activites_csv);
        $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM activites WHERE date = ? AND titre = ? AND heure_debut = ?");
        $insert_stmt = $pdo->prepare("INSERT INTO activites (date, heure_debut, heure_fin, titre, description, lieu, prix, modalites, type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $count = 0;
        $skipped = 0;
        foreach ($data as $row) {
            $date = !empty($row['date']) ? $row['date'] : null;
            $heure_debut = !empty($row['heure_debut']) ? $row['heure_debut'] . ':00' : '00:00:00';
            $heure_fin = !empty($row['heure_fin']) ? $row['heure_fin'] . ':00' : '00:00:00';
            
            if ($date) {
                $check_stmt->execute([$date, $row['titre'] ?? '', $heure_debut]);
                if ($check_stmt->fetchColumn() == 0) {
                    $insert_stmt->execute([
                        $date, $heure_debut, $heure_fin,
                        $row['titre'] ?? '', $row['description'] ?? '',
                        $row['lieu'] ?? '', $row['prix'] ?? '',
                        $row['modalites'] ?? '', $row['type'] ?? ''
                    ]);
                    $count++;
                } else {
                    $skipped++;
                }
            }
        }
        echo "<p>✅ $count activités importées, $skipped activités déjà existantes (évitées)</p>";
    }
    
    // Importer les annulations (éviter les doublons)
    $annulations_csv = 'assets/data/annulations.csv';
    if (file_exists($annulations_csv)) {
        $data = parseCSV($annulations_csv);
        $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM annulations WHERE critere_type = ? AND critere_valeur = ?");
        $insert_stmt = $pdo->prepare("INSERT INTO annulations (critere_type, critere_valeur, date_debut, date_fin, message) VALUES (?, ?, ?, ?, ?)");
        
        $count = 0;
        $skipped = 0;
        foreach ($data as $row) {
            $check_stmt->execute([$row['critere_type'] ?? '', $row['critere_valeur'] ?? '']);
            if ($check_stmt->fetchColumn() == 0) {
                $insert_stmt->execute([
                    $row['critere_type'] ?? '',
                    $row['critere_valeur'] ?? '',
                    !empty($row['date_debut']) ? $row['date_debut'] : null,
                    !empty($row['date_fin']) ? $row['date_fin'] : null,
                    $row['message'] ?? ''
                ]);
                $count++;
            } else {
                $skipped++;
            }
        }
        echo "<p>✅ $count règles d'annulation importées, $skipped règles déjà existantes (évitées)</p>";
    }
    
    // Importer les membres depuis le CSV
    $members_csv = 'assets/data/listes_MEMBRES_2025.csv';
    if (file_exists($members_csv)) {
        echo "<h3>📥 Import des membres...</h3>";
        
        $handle = fopen($members_csv, 'r');
        if ($handle) {
            // Lire l'en-tête
            $header = fgetcsv($handle, 0, ';');
            
            $imported_count = 0;
            $skipped_count = 0;
            
            while (($data = fgetcsv($handle, 0, ';')) !== FALSE) {
                // Ignorer les lignes vides
                if (empty($data[0]) || count($data) < 6) {
                    continue;
                }
                
                $numero_fiche = trim($data[0]);
                $titre = trim($data[1]);
                $nom = trim($data[2]);
                $prenom = trim($data[3]);
                $nom_chien = trim($data[4]);
                $race = trim($data[5]);
                
                // Ignorer les lignes sans nom de chien
                if (empty($nom_chien)) {
                    $skipped_count++;
                    continue;
                }
                
                // Créer le nom complet du maître
                $nom_complet = trim($prenom . ' ' . $nom);
                
                // Extraire seulement les 4 chiffres du numéro de fiche pour le user_id
                preg_match('/(\d{4})/', $numero_fiche, $matches);
                $user_id = isset($matches[1]) ? $matches[1] : $numero_fiche;
                
                // Déterminer le rôle selon le préfixe du numéro de fiche
                $role = 'member';
                if (preg_match('/^(MAL|ML)/', $numero_fiche)) {
                    $role = 'admin';
                }
                
                // Traiter les noms de chiens séparés par des tirets ou des slashes
                $chiens = [];
                
                // Diviser par les différents séparateurs possibles
                $separateurs = [' - ', ' / ', '/', '-'];
                $chiens_temp = [$nom_chien];
                
                foreach ($separateurs as $separateur) {
                    $nouveaux_chiens = [];
                    foreach ($chiens_temp as $chien) {
                        $parts = explode($separateur, $chien);
                        $nouveaux_chiens = array_merge($nouveaux_chiens, $parts);
                    }
                    $chiens_temp = $nouveaux_chiens;
                }
                
                // Nettoyer chaque nom de chien
                foreach ($chiens_temp as $chien) {
                    $chien_clean = trim($chien);
                    if (!empty($chien_clean)) {
                        $chiens[] = $chien_clean;
                    }
                }
                
                // Créer une entrée pour chaque chien
                foreach ($chiens as $chien) {
                    // Vérifier si cette combinaison user_id-name-dog_name existe déjà
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM members WHERE user_id = ? AND name = ? AND dog_name = ?");
                    $stmt->execute([$user_id, $nom_complet, $chien]);
                    
                    if ($stmt->fetchColumn() == 0) {
                        // Insérer le nouveau membre
                        $stmt = $pdo->prepare("INSERT INTO members (user_id, name, dog_name, role) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$user_id, $nom_complet, $chien, $role]);
                        $imported_count++;
                    } else {
                        $skipped_count++;
                    }
                }
            }
            
            fclose($handle);
            echo "<p>✅ $imported_count membres importés, $skipped_count entrées ignorées (doublons ou lignes vides)</p>";
        } else {
            echo "<p>❌ Impossible d'ouvrir le fichier CSV des membres</p>";
        }
    } else {
        echo "<p>ℹ️ Fichier CSV des membres non trouvé : $members_csv</p>";
    }
    
    echo "<h2>🎉 Configuration terminée avec succès !</h2>";
    echo "<p><a href='login.php'>Aller à la page de connexion</a></p>";
    echo "<p><a href='activites.php'>Voir les activités</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Erreur : " . $e->getMessage() . "</p>";
    echo "<h3>Vérifiez votre configuration :</h3>";
    echo "<ul>";
    echo "<li>MySQL est-il démarré ?</li>";
    echo "<li>Les paramètres de connexion dans includes/config.php sont-ils corrects ?</li>";
    echo "<li>L'utilisateur MySQL a-t-il les droits suffisants ?</li>";
    echo "</ul>";
}
?> 