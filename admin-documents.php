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

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        try {
            $pdo = getDBConnection();
            
            switch ($_POST['action']) {
                case 'upload':
                    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
                        $title = sanitize($_POST['title']);
                        $category = sanitize($_POST['category']);
                        $access_level = sanitize($_POST['access_level']);
                        $description = sanitize($_POST['description']);
                        
                        $file = $_FILES['document'];
                        $filename = $file['name'];
                        $file_size = $file['size'];
                        $file_type = pathinfo($filename, PATHINFO_EXTENSION);
                        
                        // Vérifier le type de fichier
                        $allowed_types = ['pdf', 'doc', 'docx', 'txt', 'jpg', 'jpeg', 'png'];
                        if (!in_array(strtolower($file_type), $allowed_types)) {
                            $error = "Type de fichier non autorisé. Types acceptés : " . implode(', ', $allowed_types);
                            break;
                        }
                        
                        // Vérifier la taille (max 10MB)
                        if ($file_size > 10 * 1024 * 1024) {
                            $error = "Fichier trop volumineux. Taille maximum : 10MB";
                            break;
                        }
                        
                        // Créer le nom de fichier unique
                        $unique_filename = time() . '_' . $filename;
                        
                        // Déterminer le dossier de destination selon la catégorie
                        $category_folders = [
                            'formulaires' => '01-formulaires',
                            'tests' => '02-tests-sociabilite',
                            'entrainements' => '03-plans-entrainement',
                            'reglements' => '04-reglements',
                            'animations' => '05-animations',
                            'divers' => '06-divers'
                        ];
                        
                        $folder = $category_folders[$category] ?? '06-divers';
                        $upload_dir = "assets/documents/$folder/";
                        
                        // Créer le dossier s'il n'existe pas
                        if (!is_dir($upload_dir)) {
                            mkdir($upload_dir, 0755, true);
                        }
                        
                        $filepath = $upload_dir . $unique_filename;
                        
                        // Déplacer le fichier
                        if (move_uploaded_file($file['tmp_name'], $filepath)) {
                            // Insérer dans la base de données
                            $stmt = $pdo->prepare("INSERT INTO documents (title, filename, filepath, category, access_level, description, file_size, file_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                            $stmt->execute([$title, $unique_filename, $filepath, $category, $access_level, $description, $file_size, $file_type]);
                            
                            $message = "Document '$title' uploadé avec succès !";
                        } else {
                            $error = "Erreur lors de l'upload du fichier";
                        }
                    } else {
                        $error = "Erreur lors de l'upload du fichier";
                    }
                    break;
                    
                case 'delete':
                    $id = sanitize($_POST['id']);
                    
                    // Récupérer les informations du fichier
                    $stmt = $pdo->prepare("SELECT filename, filepath FROM documents WHERE id = ?");
                    $stmt->execute([$id]);
                    $document = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($document) {
                        // Supprimer le fichier physique
                        if (file_exists($document['filepath'])) {
                            unlink($document['filepath']);
                        }
                        
                        // Supprimer de la base de données
                        $stmt = $pdo->prepare("DELETE FROM documents WHERE id = ?");
                        $stmt->execute([$id]);
                        
                        $message = "Document supprimé avec succès !";
                    } else {
                        $error = "Document non trouvé";
                    }
                    break;
                    
                case 'update':
                    $id = sanitize($_POST['id']);
                    $title = sanitize($_POST['title']);
                    $category = sanitize($_POST['category']);
                    $access_level = sanitize($_POST['access_level']);
                    $description = sanitize($_POST['description']);
                    
                    $stmt = $pdo->prepare("UPDATE documents SET title = ?, category = ?, access_level = ?, description = ? WHERE id = ?");
                    $stmt->execute([$title, $category, $access_level, $description, $id]);
                    
                    $message = "Document mis à jour avec succès !";
                    break;
            }
        } catch (Exception $e) {
            $error = "Erreur de base de données : " . $e->getMessage();
        }
    }
}

// Charger les documents existants
$documents = [];
try {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT * FROM documents ORDER BY category, title");
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = "Erreur lors du chargement des documents : " . $e->getMessage();
}

// Catégories disponibles
$categories = [
    'formulaires' => 'Formulaires',
    'tests' => 'Tests de sociabilité',
    'entrainements' => 'Plans d\'entraînement',
    'reglements' => 'Règlements',
    'animations' => 'Animations',
    'divers' => 'Divers'
];

$access_levels = [
    'public' => 'Public',
    'member' => 'Membres',
    'admin' => 'Administrateurs',
    'archived' => 'Archivé (non visible)'
];
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

/* Styles pour l'administration */
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
    margin-bottom: 30px;
}

.admin-section h2 {
    color: rgb(158, 0, 1);
    margin-bottom: 20px;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
}

.admin-form {
    max-width: 1000px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #333;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: rgb(158, 0, 1);
    box-shadow: 0 0 0 2px rgba(158, 0, 1, 0.2);
}

.document-card {
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid rgb(158, 0, 1);
    padding: 15px;
    margin-bottom: 15px;
}

.document-info {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.document-details h5 {
    margin: 0 0 10px 0;
    color: rgb(158, 0, 1);
}

.document-details p {
    margin: 5px 0;
    color: #666;
}

.category-badge,
.access-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
    margin-right: 5px;
    margin-bottom: 5px;
}

.category-formulaires { background-color: #e3f2fd; color: #1976d2; }
.category-tests { background-color: #f3e5f5; color: #7b1fa2; }
.category-entrainements { background-color: #e8f5e8; color: #388e3c; }
.category-reglements { background-color: #fff3e0; color: #f57c00; }
.category-animations { background-color: #fce4ec; color: #c2185b; }
.category-divers { background-color: #f5f5f5; color: #616161; }

.access-public { background-color: #e8f5e8; color: #388e3c; }
.access-member { background-color: #fff3e0; color: #f57c00; }
.access-admin { background-color: #ffebee; color: #d32f2f; }

.file-info {
    margin-top: 10px;
    font-size: 14px;
    color: #666;
}

.document-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
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
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-block;
}

.btn-danger:hover {
    background-color: #c82333;
    color: white;
    text-decoration: none;
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

.alert-danger {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.category-section {
    margin-bottom: 30px;
}

.category-section h4 {
    color: rgb(158, 0, 1);
    margin-bottom: 15px;
    border-bottom: 1px solid #eee;
    padding-bottom: 5px;
}

/* Mode sombre */
@media (prefers-color-scheme: dark) {
    .admin-section {
        background: #1a1a1a;
        color: #fff;
    }
    
    .document-card {
        background: #2a2a2a;
    }
    
    .form-group input,
    .form-group textarea,
    .form-group select {
        background: #2a2a2a;
        color: #fff;
        border-color: #444;
    }
    
    .document-details p {
        color: #ccc;
    }
    
    .file-info {
        color: #ccc;
    }
}
</style>

    <!-- Montserrat font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="main.css?v=1.7.1" />

    <link rel="icon" href="assets/images/favicon.png" sizes="any" type="image/png" />
    <link rel="apple-touch-icon" href="assets/images/logo.png" />

    <title>Gestion des Documents - ÉducaChien Club</title>
    <meta name="description" content="Gestion des documents du Club Canin Educachien Engis-Fagnes" />

</head>

<body>
    <!-- Container pour le header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Contenu principal -->
    <section class="section container-fluid text-justify">
        <div class="admin-header">
            <h1>Gestion des Documents</h1>
            <a href="member.php" class="btn-secondary">Retour à l'espace membre</a>
        </div>
            
            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
        <div class="mt-4">
                <a href="admin-activities.php" class="btn btn-secondary">Gestion des activités</a>
                <a href="admin-documents.php" class="btn btn-secondary">Gestion des documents</a>
                <a href="admin-annulations.php" class="btn btn-secondary">Gestion des annulations</a>
                <a href="index.php" class="btn btn-secondary">Retour à l'accueil</a>
        </div>
        <div class="admin-content">
            <div class="admin-section">
                <h2>Uploader un nouveau document</h2>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="upload">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Titre du document :</label>
                                <input type="text" id="title" name="title" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category">Catégorie :</label>
                                <select id="category" name="category" class="form-control" required>
                                    <option value="">Sélectionner une catégorie</option>
                                    <?php foreach ($categories as $key => $value): ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="access_level">Niveau d'accès :</label>
                                <select id="access_level" name="access_level" class="form-control" required>
                                    <?php foreach ($access_levels as $key => $value): ?>
                                        <option value="<?php echo $key; ?>" <?php echo $key === 'member' ? 'selected' : ''; ?>><?php echo $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="document">Fichier :</label>
                                <input type="file" id="document" name="document" class="form-control" required accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png">
                                <small class="form-text text-muted">Types acceptés : PDF, DOC, DOCX, TXT, JPG, PNG (max 10MB)</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description :</label>
                        <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <button type="submit" class="btn-admin">Uploader le document</button>
                </form>
            </div>
            
            </div>
            
            <div class="admin-section">
                <h2>Documents existants</h2>
            
            <?php if (!empty($documents)): ?>
                <?php
                // Grouper par catégorie
                $grouped_docs = [];
                foreach ($documents as $doc) {
                    $category = $doc['category'];
                    if (!isset($grouped_docs[$category])) {
                        $grouped_docs[$category] = [];
                    }
                    $grouped_docs[$category][] = $doc;
                }
                
                foreach ($grouped_docs as $category => $docs):
                    $category_name = $categories[$category] ?? ucfirst($category);
                ?>
                <div class="category-section">
                    <h4><?php echo $category_name; ?></h4>
                    
                    <?php foreach ($docs as $doc): ?>
                        <div class="document-card">
                            <div class="document-info">
                                <div class="document-details">
                                    <h5><?php echo htmlspecialchars($doc['title']); ?></h5>
                                    <p><?php echo htmlspecialchars($doc['description']); ?></p>
                                    
                                    <div>
                                        <span class="category-badge category-<?php echo $doc['category']; ?>">
                                            <?php echo $categories[$doc['category']] ?? ucfirst($doc['category']); ?>
                                        </span>
                                        <span class="access-badge access-<?php echo $doc['access_level']; ?>">
                                            <?php echo $access_levels[$doc['access_level']]; ?>
                                        </span>
                                    </div>
                                    
                                    <div class="file-info">
                                        <strong>Fichier :</strong> <?php echo htmlspecialchars($doc['filename']); ?><br>
                                        <strong>Uploadé le :</strong> <?php echo date('d/m/Y H:i', strtotime($doc['upload_date'])); ?>
                                        <?php if ($doc['file_size']): ?>
                                            <br><strong>Taille :</strong> <?php echo number_format($doc['file_size'] / 1024, 1); ?> KB
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="document-actions">
                                    <a href="<?php echo $doc['filepath']; ?>" class="btn-secondary" target="_blank">
                                        Voir
                                    </a>
                                    <button class="btn-admin" onclick="editDocument(<?php echo $doc['id']; ?>, '<?php echo addslashes($doc['title']); ?>', '<?php echo $doc['category']; ?>', '<?php echo $doc['access_level']; ?>', '<?php echo addslashes($doc['description'] ?? ''); ?>')">
                                        Modifier
                                    </button>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?')">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $doc['id']; ?>">
                                        <button type="submit" class="btn-danger">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun document enregistré.</p>
            <?php endif; ?>
            

        </div>
    </section>

    <!-- Modal pour modifier un document -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier le document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editForm">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" id="edit_id">
                        
                        <div class="form-group">
                            <label for="edit_title">Titre :</label>
                            <input type="text" id="edit_title" name="title" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_category">Catégorie :</label>
                            <select id="edit_category" name="category" class="form-control" required>
                                <?php foreach ($categories as $key => $value): ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_access_level">Niveau d'accès :</label>
                            <select id="edit_access_level" name="access_level" class="form-control" required>
                                <?php foreach ($access_levels as $key => $value): ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_description">Description :</label>
                            <textarea id="edit_description" name="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn-admin">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    </section>

    <footer>
        <hr width="100%" />
        <div class="text-center">
            <small class="large-screen-only">© 2025 Educachien Engis-Fagnes ASBL. Tous droits réservés.</small>
            <small class="small-screen-only">© 2025 Educachien Engis-Fagnes ASBL.<br/>Tous droits réservés.</small>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    function editDocument(id, title, category, access_level, description) {
        // Charger les données du document dans le modal
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_title').value = title;
        document.getElementById('edit_category').value = category;
        document.getElementById('edit_access_level').value = access_level;
        document.getElementById('edit_description').value = description || '';
        
        // Ouvrir le modal
        var modal = new bootstrap.Modal(document.getElementById('editModal'));
        modal.show();
    }
    </script>
</body>
</html> 