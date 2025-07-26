<?php
session_start();
require_once 'includes/config.php';

// Initialiser la session et restaurer depuis le cookie si nécessaire
initSession();

// Vérifier si l'utilisateur est connecté
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$user_name = $_SESSION['name'];
$user_role = $_SESSION['role'];

// Récupérer les documents depuis la base de données
$documents = [];
try {
    $pdo = getDBConnection();
    
    // Construire la requête selon le rôle de l'utilisateur
    if (isAdmin()) {
        // Les admins voient tous les documents (sauf les archivés)
        $stmt = $pdo->prepare("SELECT * FROM documents WHERE access_level = 'member' OR access_level = 'public' OR access_level = 'admin' AND active = 1 ORDER BY category, title");
        $stmt->execute();
    } else {
        // Les membres voient les documents publics et membres (pas les archivés)
        $stmt = $pdo->prepare("SELECT * FROM documents WHERE (access_level = 'member' OR access_level = 'public') AND active = 1 AND access_level != 'archived' ORDER BY category, title");
        $stmt->execute();
    }
    
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // En cas d'erreur, on continue avec les documents statiques
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="manifest" href="manifest.json">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS personnalisé pour la navigation -->
    <style>
/* Personnalisation Bootstrap pour Educachien */
.navbar-dark.bg-dark {
  background-color: rgb(158, 0, 1) !important;
  width: 100% !important;
  max-width: 100% !important;
  position: fixed !important;
  top: 0 !important;
  z-index: 1030 !important;
  height: 80px !important;
  min-height: 80px !important;
}

.navbar {
  width: 100% !important;
  max-width: 100% !important;
}

.container-fluid {
  width: 100% !important;
  max-width: 100% !important;
  padding-left: 15px !important;
  padding-right: 15px !important;
}

.navbar-collapse {
  justify-content: flex-end !important;
  margin-left: auto !important;
  flex: 1 !important;
}

.navbar-nav {
  margin-left: auto !important;
}

.navbar-nav:last-child {
  margin-left: 0 !important;
}

.navbar-brand {
  color: yellow !important;
  font-weight: bold !important;
}

.navbar-brand:hover {
  color: #fff !important;
}

.navbar-brand img {
  margin-right: 10px !important;
  height: 50px !important;
  padding: 10px !important;
}

.nav-link {
  color: #aaa !important;
}

.nav-link:hover {
  color: yellow !important;
}

.nav-item.active .nav-link {
  color: yellow !important;
}

.dropdown-menu {
  background-color: rgb(158, 0, 1) !important;
  border: 1px solid rgba(255, 255, 255, 0.1) !important;
}

.dropdown-item {
  color: #aaa !important;
}

.dropdown-item:hover {
  background-color: rgba(255, 255, 255, 0.1) !important;
  color: yellow !important;
}

/* Styles de mise en page généraux */
body {
  padding-top: 80px !important;
}

.section {
  max-width: 1600px !important;
  margin: 0 auto !important;
  padding: 0 22px !important;
  padding-top: 20px !important;
}

.text-justify {
  text-align: justify !important;
}

.text-center {
  text-align: center !important;
}

.title {
  font-weight: bold !important;
}

/* Styles pour l'espace membre */
.member-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
}
.member-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid rgb(158, 0, 1);
}

.member-header h1 {
    margin: 0;
    color: rgb(158, 0, 1);
}

.member-content {
    display: grid;
    gap: 30px;
}

.member-section {
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
}

.member-section h2 {
    color: rgb(158, 0, 1);
    margin-bottom: 20px;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
}

.documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.document-card {
    background: #f8f9fa;
    padding: 20px;
    text-align: center;

    /* border-radius: 8px; */
    
    /* border-left: 4px solid rgb(158, 0, 1); */
}

.document-card h3 {
    color: rgb(158, 0, 1);
    /* font-weight: bold !important; */

    margin-bottom: 15px;
    /* font-size: 20px; */
}

.document-card ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.document-card li {
    margin-bottom: 10px;
}

.document-card a {
    /* font-weight: bold !important; */
    text-align: left;
    color: #333;
    text-decoration: none;
    padding: 8px 12px;
    display: block;
    border-radius: 5px;
    transition: background-color 0.2s;
}

.document-card a:hover {
    color: rgb(158, 0, 1); 
    background-color: rgba(158, 0, 1, 0.05); 
} 
/* Styles pour l'administration */

.admin-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid rgb(158, 0, 1);
}

.admin-header h1 {
    margin: 0;
    color: rgb(158, 0, 1);
}

.admin-content {
    display: grid;
    gap: 30px;
}

.admin-section {
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.admin-section h2 {
    color: rgb(158, 0, 1);
    margin-bottom: 20px;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
}

.admin-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.admin-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.admin-card {
    background: #f8f9fa;
    padding: 20px;
    /* border-radius: 8px; */
    /* border-left: 4px solid rgb(158, 0, 1); */
    text-align: center;
}

.admin-card h3 {
    color: rgb(158, 0, 1);
    margin-bottom: 10px;
}

.admin-card p {
    margin-bottom: 15px;
    color: #666;
}

.btn-member {
    background-color: rgb(158, 0, 1);
    color: yellow;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-block;
}

.btn-member:hover {
    background-color: #8b0001;
    color: yellow;
    text-decoration: none;
}

.btn-secondary {
    background-color: transparent;
    color: rgb(158, 0, 1);
    border: 2px solid rgb(158, 0, 1);
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-block;
}

.btn-secondary:hover {
    background-color: rgb(158, 0, 1);
    color: yellow;
    text-decoration: none;
}

/* Mode sombre*/
@media (prefers-color-scheme: dark) {
    .member-section {
        background: #1a1a1a;
        color: #fff;
    }
    .member-header h1 {
    margin: 0;
    color: #fff !important;
}

    .member-section h2 {
        margin: 0;
        color: #fff !important;
    }
    .document-card,
    .admin-card {
        background: #2a2a2a;
    }
    
    .document-card a {
        color: #fff;
    }
    
    .document-card .field-title {
        color: yellow;
    }

    .document-card a:hover {
        background-color: #a5a5a5;
        /* color: yellow; */
    }
    
    .admin-card p {
        color: #ccc;
    }

    .admin-card .field-title {
        color: yellow;
    }
} 
</style>

    <!-- Montserrat font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />

    <!-- moment.js -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="assets/scripts/moment-locale.js?v=1.7.1"></script>

    <script src="assets/scripts/create-ics.js?v=1.7.1"></script>
    <script src="assets/scripts/tables.js?v=1.7.1"></script>

    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" type="text/css" href="main.css?v=1.7.1" />

    <link rel="icon" href="assets/images/favicon.png" sizes="any" type="image/png" />
    <link rel="apple-touch-icon" href="assets/images/logo.png" />

    <title>Espace Membre - ÉducaChien Club</title>
    <meta name="description" content="Espace membre du Club Canin Educachien Engis-Fagnes - Accès aux documents réservés" />
</head>

<body>
    <!-- Container pour le header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Contenu principal -->
    <section class="section container-fluid text-justify">
        <div class="member-header">
            <h1>Espace Membre</h1>
            <p>Bienvenue, <?php echo $user_name; ?> !</p>
            <a href="logout.php" class="btn-member">Se déconnecter</a>
        </div>
        
        <div class="member-content">
            <div class="member-section">
                <h2>Documents</h2>
                <div class="documents-grid">
                    <?php if (!empty($documents)): ?>
                        <?php
                        // Grouper les documents par catégorie
                        $categories = [];
                        foreach ($documents as $doc) {
                            $category = $doc['category'] ?? 'autres';
                            if (!isset($categories[$category])) {
                                $categories[$category] = [];
                            }
                            $categories[$category][] = $doc;
                        }
                        
                        // Afficher chaque catégorie
                        foreach ($categories as $category => $docs):
                            $category_title = ucfirst($category);
                            if ($category === 'formulaires') $category_title = 'Formulaires';
                            if ($category === 'tests') $category_title = 'Tests de sociabilité';
                            if ($category === 'reglements') $category_title = 'Règlements';
                            if ($category === 'entrainements') $category_title = 'Plans d\'entraînement';
                            if ($category === 'animations') $category_title = 'Animations';
                            if ($category === 'divers') $category_title = 'Divers';
                        ?>
                        <div class="document-card">
                            <h3 class="field-title"><?php echo htmlspecialchars($category_title); ?></h3>
                            <ul>
                                <?php foreach ($docs as $doc): ?>
                                <li>
                                    <a href="<?php echo htmlspecialchars($doc['filepath']); ?>" target="_blank">
                                        <?php echo htmlspecialchars($doc['title']); ?>
                                        <?php if ($doc['description']): ?>
                                            <small> - <?php echo htmlspecialchars($doc['description']); ?></small>
                                        <?php endif; ?>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Fallback vers les documents statiques si la base de données n'est pas disponible -->
                        <div class="document-card">
                            <h3>Documents Internes</h3>
                            <ul>
                                <li><a href="assets/documents/Fiche inscription.pdf" target="_blank">Fiche d'inscription</a></li>
                                <li><a href="assets/documents/Muselière.pdf" target="_blank">Règlement muselière</a></li>
                                <li><a href="assets/documents/TS-2024.pdf" target="_blank">Test de sociabilité 2024</a></li>
                            </ul>
                        </div>
                        
                        <div class="document-card">
                            <h3>Formulaires</h3>
                            <ul>
                                <li><a href="assets/documents/Fiche inscription Test soc 2023.pdf" target="_blank">Fiche Test Soc 2023</a></li>
                                <li><a href="assets/documents/Fiche inscription Test soc 2024.pdf" target="_blank">Fiche Test Soc 2024</a></li>
                                <li><a href="assets/documents/Fiche inscription Test soc 2025.pdf" target="_blank">Fiche Test Soc 2025</a></li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if (isAdmin()): ?>
            <div class="member-section">
                <h2>Administration</h2>
                <div class="admin-actions">
                    <div class="admin-card">
                        <h3 class="field-title">Gestion des Activités</h3>
                        <p>Ajouter, modifier ou supprimer des activités</p>
                        <a href="admin-activities.php" class="btn-member">Gérer les activités</a>
                    </div>
                    
                    <div class="admin-card">
                        <h3 class="field-title">Gestion des Membres</h3>
                        <p>Gérer les identifiants et accès des membres</p>
                        <a href="admin-members.php" class="btn-member">Gérer les membres</a>
                    </div>
                    
                    <div class="admin-card">
                        <h3 class="field-title">Gestion des Documents</h3>
                        <p>Ajouter, modifier ou supprimer des documents</p>
                        <a href="admin-documents.php" class="btn-member">Gérer les documents</a>
                    </div>
                    
                    <div class="admin-card">
                        <h3 class="field-title">Critères d'Annulation</h3>
                        <p>Configurer les règles d'annulation des activités</p>
                        <a href="admin-annulations.php" class="btn-member">Gérer les annulations</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <footer>
        <hr width="100%" />
        <div class="text-center">
            <small class="large-screen-only">© 2025 Educachien Engis-Fagnes ASBL. Tous droits réservés.</small>
            <small class="small-screen-only">© 2025 Educachien Engis-Fagnes ASBL.<br/>Tous droits réservés.</small>
        </div>
    </footer>

    <script src="assets/scripts/tables.js?v=<?php echo time(); ?>"></script>
</body>
</html> 