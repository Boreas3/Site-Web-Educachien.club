<?php
session_start();
require_once 'includes/config.php';

// Initialiser la session et restaurer depuis le cookie si nécessaire
initSession();

// Vérifier si l'utilisateur est admin
if (!isAdmin()) {
    header('Location: login.php');
    exit();
}

$message = '';
$error = '';
$tables = [];

// Tentative de connexion à la base de données
try {
    $pdo = getDBConnection();
    
    // Récupérer la liste des tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
} catch (Exception $e) {
    $error = "Erreur de connexion à la base de données : " . $e->getMessage();
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

/* Styles pour l'administration */
.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 20px 0;
    border-bottom: 2px solid rgb(158, 0, 1);
    flex-wrap: wrap;
    gap: 15px;
}

.admin-header h1 {
    color: rgb(158, 0, 1);
    margin: 0;
    font-size: 2.5rem;
    font-weight: bold;
    flex: 1;
    min-width: 0;
}

.admin-header .btn-secondary {
    white-space: nowrap;
    flex-shrink: 0;
    margin-top: 200px;
    align-self: flex-end;
}

.admin-content {
    background: #fff;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
}

.admin-section {
    margin-bottom: 40px;
}

.admin-section h2 {
    color: rgb(158, 0, 1);
    border-bottom: 2px solid rgb(158, 0, 1);
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.btn-admin {
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

.btn-admin:hover {
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

.btn-danger {
    background-color: #dc3545;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
}

.btn-danger:hover {
    background-color: #c82333;
    color: white;
}

.tables-list {
    display: grid;
    gap: 20px;
}

.table-item {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    border-left: 4px solid rgb(158, 0, 1);
}

.table-item h3 {
    margin: 0 0 15px 0;
    color: rgb(158, 0, 1);
    font-size: 1.5rem;
}

/* .table-actions supprimé car plus utilisé */

.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.action-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    border-left: 4px solid rgb(158, 0, 1);
}

.action-card h3 {
    margin: 0 0 10px 0;
    color: rgb(158, 0, 1);
}

.action-card p {
    margin: 0 0 15px 0;
    color: #666;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
}

.alert-success {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.alert-error {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

/* Responsive pour petits écrans */
@media (max-width: 768px) {
    .admin-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .admin-header h1 {
        font-size: 2rem;
        margin-bottom: 10px;
    }
    
    .admin-header .btn-secondary {
        align-self: flex-end;
    }
}

/* Mode sombre */
@media (prefers-color-scheme: dark) {
    body {
        background-color: #1a1a1a;
        color: #fff;
    }
    
    .admin-content {
        background: #2a2a2a;
        color: #fff;
    }
    
    .table-item,
    .action-card {
        background: #2a2a2a;
        color: #fff;
    }
    
    .table-item h3,
    .action-card h3 {
        color: #fff;
    }
    
    .action-card p {
        color: #ccc;
    }
}
</style>

    <!-- Montserrat font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" type="text/css" href="main.css?v=1.7.1" />

    <link rel="icon" href="assets/images/favicon.png" sizes="any" type="image/png" />
    <link rel="apple-touch-icon" href="assets/images/logo.png" />

    <title>Base de Données - Admin</title>
    <meta name="description" content="Gestion de la base de données du Club Canin Educachien Engis-Fagnes" />
</head>

<body>
    <!-- Container pour le header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Contenu principal -->
    <section class="section container-fluid text-justify">
        <div class="admin-header">
            <h1>Gestion de la Base de Données</h1>
            <a href="member.php" class="btn-secondary">Retour à l'espace membre</a>
        </div>
            
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="admin-content">
            <?php if (empty($error)): ?>

            
            <div class="admin-section">
                <h2>Actions rapides</h2>
                <div class="quick-actions">
                    <div class="action-card">
                        <h3>Gérer les activités</h3>
                        <p>Ajouter, modifier ou supprimer des activités du club</p>
                        <a href="admin-activities.php" class="btn-admin">Gérer les activités</a>
                    </div>
                    
                    <div class="action-card">
                        <h3>Gérer les membres</h3>
                        <p>Ajouter ou supprimer des membres du club</p>
                        <a href="admin-members.php" class="btn-admin">Gérer les membres</a>
                    </div>
                    
                    <div class="action-card">
                        <h3>Configuration</h3>
                        <p>Configurer la base de données et importer les données</p>
                        <a href="setup-database.php" class="btn-admin">Configurer la BDD</a>
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