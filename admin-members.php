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
                case 'add':
                    $user_id = sanitize($_POST['user_id']);
                    $name = sanitize($_POST['name']);
                    $dog_name = sanitize($_POST['dog_name']);
                    $role = sanitize($_POST['role']);
                    
                    // Vérifier si l'identifiant existe déjà
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM members WHERE user_id = ?");
                    $stmt->execute([$user_id]);
                    if ($stmt->fetchColumn() > 0) {
                        $error = "L'identifiant '$user_id' existe déjà";
                    } else {
                        $stmt = $pdo->prepare("INSERT INTO members (user_id, name, dog_name, role) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$user_id, $name, $dog_name, $role]);
                        $message = "Membre '$name' ajouté avec succès !";
                    }
                    break;
                    
                case 'delete':
                    $user_id = sanitize($_POST['user_id']);
                    if ($user_id === '2305') {
                        $error = "Impossible de supprimer le compte admin principal";
                    } else {
                        $stmt = $pdo->prepare("DELETE FROM members WHERE user_id = ?");
                        $stmt->execute([$user_id]);
                        $message = "Membre supprimé avec succès !";
                    }
                    break;
                    
                case 'update':
                    $user_id = sanitize($_POST['user_id']);
                    $name = sanitize($_POST['name']);
                    $dog_name = sanitize($_POST['dog_name']);
                    $role = sanitize($_POST['role']);
                    
                    // Empêcher la modification du compte admin principal
                    if ($user_id === '2305' && $role !== 'admin') {
                        $error = "Impossible de modifier le rôle du compte admin principal";
                    } else {
                        $stmt = $pdo->prepare("UPDATE members SET name = ?, dog_name = ?, role = ? WHERE user_id = ?");
                        $stmt->execute([$name, $dog_name, $role, $user_id]);
                        $message = "Membre '$name' mis à jour avec succès !";
                    }
                    break;
            }
        } catch (Exception $e) {
            $error = "Erreur de base de données : " . $e->getMessage();
        }
    }
}

// Gestion du filtrage
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$filter_type = isset($_GET['filter_type']) ? sanitize($_GET['filter_type']) : 'all';

// Charger les membres existants depuis la base de données avec filtrage
$members = [];
try {
    $pdo = getDBConnection();
    
    if (!empty($search)) {
        // Construire la requête selon le type de filtre
        switch ($filter_type) {
            case 'user_id':
                $stmt = $pdo->prepare("SELECT user_id, name, dog_name, role FROM members WHERE user_id LIKE ? ORDER BY name");
                $stmt->execute(['%' . $search . '%']);
                break;
            case 'name':
                $stmt = $pdo->prepare("SELECT user_id, name, dog_name, role FROM members WHERE name LIKE ? ORDER BY name");
                $stmt->execute(['%' . $search . '%']);
                break;
            case 'dog_name':
                $stmt = $pdo->prepare("SELECT user_id, name, dog_name, role FROM members WHERE dog_name LIKE ? ORDER BY name");
                $stmt->execute(['%' . $search . '%']);
                break;
            default:
                // Recherche dans tous les champs
                $stmt = $pdo->prepare("SELECT user_id, name, dog_name, role FROM members WHERE user_id LIKE ? OR name LIKE ? OR dog_name LIKE ? ORDER BY name");
                $stmt->execute(['%' . $search . '%', '%' . $search . '%', '%' . $search . '%']);
                break;
        }
    } else {
        // Pas de filtre, récupérer tous les membres
        $stmt = $pdo->query("SELECT user_id, name, dog_name, role FROM members ORDER BY name");
    }
    
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = "Erreur lors du chargement des membres : " . $e->getMessage();
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
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: rgb(158, 0, 1);
    box-shadow: 0 0 0 2px rgba(158, 0, 1, 0.2);
}

.members-list {
    display: grid;
    gap: 15px;
}

.member-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid rgb(158, 0, 1);
}

.member-info h3 {
    margin: 0 0 5px 0;
    color: rgb(158, 0, 1);
}

.member-info p {
    margin: 2px 0;
    color: #666;
}

/* Styles pour le formulaire de filtrage */
.filter-section {
    background: #f8f9fa !important;
    border: 1px solid #dee2e6 !important;
    border-radius: 8px !important;
    padding: 20px !important;
    margin-bottom: 25px !important;
}

.filter-section .form-label {
    font-weight: 500 !important;
    color: #333 !important;
    margin-bottom: 8px !important;
}

.filter-section .form-control {
    border: 1px solid #ced4da !important;
    border-radius: 5px !important;
    padding: 8px 12px !important;
    font-size: 14px !important;
}

.filter-section .form-control:focus {
    border-color: rgb(158, 0, 1) !important;
    box-shadow: 0 0 0 0.2rem rgba(158, 0, 1, 0.25) !important;
}

.filter-section .btn {
    padding: 8px 16px !important;
    font-size: 14px !important;
    border-radius: 5px !important;
}

.filter-section .btn-primary {
    background-color: rgb(158, 0, 1) !important;
    border-color: rgb(158, 0, 1) !important;
}

.filter-section .btn-primary:hover {
    background-color: #8b0001 !important;
    border-color: #8b0001 !important;
}

.filter-section .btn-secondary {
    background-color: #6c757d !important;
    border-color: #6c757d !important;
}

.filter-section .btn-secondary:hover {
    background-color: #5a6268 !important;
    border-color: #545b62 !important;
}


.member-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
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

.alert-error {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

/* Mode sombre */
@media (prefers-color-scheme: dark) {
    .admin-section {
        background: #1a1a1a;
        color: #fff;
    }
    
    .member-item {
        background: #2a2a2a;
    }
    
    .form-group input,
    .form-group select {
        background: #2a2a2a;
        color: #fff;
        border-color: #444;
    }
    
    .member-info p {
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

    <title>Gestion des Membres - Admin</title>
    <meta name="description" content="Gestion des membres du Club Canin Educachien Engis-Fagnes" />
</head>

<body>
    <!-- Container pour le header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Contenu principal -->
    <section class="section container-fluid text-justify">
        <div class="admin-header">
            <h1>Gestion des Membres</h1>
            <a href="member.php" class="btn-secondary">Retour à l'espace membre</a>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="mt-4">
                <a href="admin-activities.php" class="btn btn-secondary">Gestion des activités</a>
                <a href="admin-documents.php" class="btn btn-secondary">Gestion des documents</a>
                <a href="admin-annulations.php" class="btn btn-secondary">Gestion des annulations</a>
                <a href="index.php" class="btn btn-secondary">Retour à l'accueil</a>
        </div>
        <div class="admin-content">
            <div class="admin-section">
                <h2>Ajouter un nouveau membre</h2>
                <form method="POST" class="admin-form">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="form-group">
                        <label for="user_id">Identifiant :</label>
                        <input type="text" id="user_id" name="user_id" required 
                               placeholder="Ex: 1234">
                    </div>
                    
                    <div class="form-group">
                        <label for="name">Nom du propriétaire :</label>
                        <input type="text" id="name" name="name" required 
                               placeholder="Nom du propriétaire">
                    </div>
                    
                    <div class="form-group">
                        <label for="dog_name">Nom du chien :</label>
                        <input type="text" id="dog_name" name="dog_name" required 
                               placeholder="Nom du chien">
                    </div>
                    
                    <div class="form-group">
                        <label for="role">Rôle :</label>
                        <select id="role" name="role" required>
                            <option value="member">Membre</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn-admin">Ajouter le membre</button>
                </form>
            </div>
            
            <div class="admin-section">
                <h2>Membres existants</h2>
                
                <!-- Formulaire de filtrage -->
                <div class="filter-section" style="margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Rechercher :</label>
                            <input type="text" id="search" name="search" class="form-control" 
                                   value="<?php echo htmlspecialchars($search); ?>" 
                                   placeholder="Entrez votre recherche...">
                        </div>
                        <div class="col-md-3">
                            <label for="filter_type" class="form-label">Filtrer par :</label>
                            <select id="filter_type" name="filter_type" class="form-control">
                                <option value="all" <?php echo $filter_type === 'all' ? 'selected' : ''; ?>>Tous les champs</option>
                                <option value="user_id" <?php echo $filter_type === 'user_id' ? 'selected' : ''; ?>>Numéro de membre</option>
                                <option value="name" <?php echo $filter_type === 'name' ? 'selected' : ''; ?>>Nom du maître</option>
                                <option value="dog_name" <?php echo $filter_type === 'dog_name' ? 'selected' : ''; ?>>Nom du chien</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-admin">Filtrer</button>
                                <a href="admin-members.php" class="btn btn-danger">Réinitialiser</a>
                            </div>
                        </div>
                    </form>
                </div>
                
                <?php if (!empty($search)): ?>
                    <div class="alert alert-info">
                        <strong>Résultats de recherche :</strong> 
                        <?php echo count($members); ?> membre(s) trouvé(s) pour "<?php echo htmlspecialchars($search); ?>"
                        <?php if ($filter_type !== 'all'): ?>
                            (filtre : <?php echo $filter_type === 'user_id' ? 'Numéro de membre' : ($filter_type === 'name' ? 'Nom du maître' : 'Nom du chien'); ?>)
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (empty($members)): ?>
                    <p>Aucun membre trouvé dans la base de données.</p>
                <?php else: ?>
                <div class="members-list">
                    <?php foreach ($members as $member): ?>
                    <div class="member-item">
                        <div class="member-info">
                            <h3><?php echo htmlspecialchars($member['name']); ?></h3>
                            <p><strong>ID :</strong> <?php echo htmlspecialchars($member['user_id']); ?></p>
                            <p><strong>Chien :</strong> <?php echo htmlspecialchars($member['dog_name']); ?></p>
                            <p><strong>Rôle :</strong> <?php echo ucfirst(htmlspecialchars($member['role'])); ?></p>
                        </div>
                        <div class="member-actions">
                            <button class="btn-admin" onclick="editMember('<?php echo $member['user_id']; ?>', '<?php echo addslashes($member['name']); ?>', '<?php echo addslashes($member['dog_name']); ?>', '<?php echo $member['role']; ?>')">
                                Modifier
                            </button>
                            <?php if ($member['user_id'] !== '2305'): ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="user_id" value="<?php echo $member['user_id']; ?>">
                                <button type="submit" class="btn-danger" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce membre ?')">
                                    Supprimer
                                </button>
                            </form>
                            <?php else: ?>
                            <span class="btn-secondary" style="opacity: 0.6; cursor: not-allowed;">Admin Principal</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            
        </div>
    </section>

    <!-- Modal pour modifier un membre -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier le membre</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editForm">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="user_id" id="edit_user_id">
                        
                        <div class="form-group">
                            <label for="edit_name">Nom du propriétaire :</label>
                            <input type="text" id="edit_name" name="name" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_dog_name">Nom du chien :</label>
                            <input type="text" id="edit_dog_name" name="dog_name" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_role">Rôle :</label>
                            <select id="edit_role" name="role" class="form-control" required>
                                <option value="member">Membre</option>
                                <option value="admin">Administrateur</option>
                            </select>
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

    <footer>
        <hr width="100%" />
        <div class="text-center">
            <small class="large-screen-only">© 2025 Educachien Engis-Fagnes ASBL. Tous droits réservés.</small>
            <small class="small-screen-only">© 2025 Educachien Engis-Fagnes ASBL.<br/>Tous droits réservés.</small>
        </div>
    </footer>

    <script src="assets/scripts/tables.js?v=<?php echo time(); ?>"></script>
    
    <script>
    // Amélioration de l'expérience utilisateur pour le filtrage
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const filterType = document.getElementById('filter_type');
        const filterForm = document.querySelector('.filter-section form');
        
        // Auto-submit du formulaire quand on change le type de filtre
        filterType.addEventListener('change', function() {
            if (searchInput.value.trim() !== '') {
                filterForm.submit();
            }
        });
        
        // Focus sur le champ de recherche au chargement
        if (searchInput.value === '') {
            searchInput.focus();
        }
        
        // Raccourci clavier : Ctrl+F pour focus sur la recherche
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'f') {
                e.preventDefault();
                searchInput.focus();
            }
        });
    });
    
    // Fonction pour éditer un membre
    function editMember(userId, name, dogName, role) {
        // Charger les données du membre dans le modal
        document.getElementById('edit_user_id').value = userId;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_dog_name').value = dogName;
        document.getElementById('edit_role').value = role;
        
        // Ouvrir le modal
        var modal = new bootstrap.Modal(document.getElementById('editModal'));
        modal.show();
    }
    </script>
</body>
</html> 