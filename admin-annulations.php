<?php
session_start();
require_once 'includes/config.php';
initSession();

// Vérifier si l'utilisateur est admin
if (!isAdmin()) {
    header('Location: login.php');
    exit();
}

$message = '';
$error = '';

// Traitement des actions POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = getDBConnection();
        
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'add':
                    $critere_type = sanitize($_POST['critere_type']);
                    $critere_valeur = sanitize($_POST['critere_valeur']);
                    $date_debut = !empty($_POST['date_debut']) ? $_POST['date_debut'] : null;
                    $date_fin = !empty($_POST['date_fin']) ? $_POST['date_fin'] : null;
                    $message_annulation = sanitize($_POST['message']);
                    
                    // Vérifier si la règle existe déjà
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM annulations WHERE critere_type = ? AND critere_valeur = ?");
                    $stmt->execute([$critere_type, $critere_valeur]);
                    
                    if ($stmt->fetchColumn() == 0) {
                        $stmt = $pdo->prepare("INSERT INTO annulations (critere_type, critere_valeur, date_debut, date_fin, message) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([$critere_type, $critere_valeur, $date_debut, $date_fin, $message_annulation]);
                        $message = "✅ Règle d'annulation ajoutée avec succès";
                    } else {
                        $error = "❌ Une règle avec ces critères existe déjà";
                    }
                    break;
                    
                case 'update':
                    $id = (int)$_POST['id'];
                    $critere_type = sanitize($_POST['critere_type']);
                    $critere_valeur = sanitize($_POST['critere_valeur']);
                    $date_debut = !empty($_POST['date_debut']) ? $_POST['date_debut'] : null;
                    $date_fin = !empty($_POST['date_fin']) ? $_POST['date_fin'] : null;
                    $message_annulation = sanitize($_POST['message']);
                    
                    $stmt = $pdo->prepare("UPDATE annulations SET critere_type = ?, critere_valeur = ?, date_debut = ?, date_fin = ?, message = ? WHERE id = ?");
                    $stmt->execute([$critere_type, $critere_valeur, $date_debut, $date_fin, $message_annulation, $id]);
                    $message = "✅ Règle d'annulation mise à jour avec succès";
                    break;
                    
                case 'delete':
                    $id = (int)$_POST['id'];
                    $stmt = $pdo->prepare("DELETE FROM annulations WHERE id = ?");
                    $stmt->execute([$id]);
                    $message = "✅ Règle d'annulation supprimée avec succès";
                    break;
            }
        }
    } catch (Exception $e) {
        $error = "❌ Erreur : " . $e->getMessage();
    }
}

// Récupérer toutes les règles d'annulation
try {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM annulations ORDER BY critere_type, critere_valeur");
    $stmt->execute();
    $annulations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Récupérer les types d'activité depuis la base de données
    $stmt = $pdo->prepare("SELECT DISTINCT type FROM activites WHERE type IS NOT NULL AND type != '' ORDER BY type");
    $stmt->execute();
    $types_activite = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (Exception $e) {
    $error = "❌ Erreur lors de la récupération des données : " . $e->getMessage();
    $annulations = [];
    $types_activite = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Gestion des Annulations - Admin</title>
    
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

.annulation-card {
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid rgb(158, 0, 1);
    padding: 15px;
    margin-bottom: 15px;
}

.critere-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
    background-color: #e3f2fd;
    color: #1976d2;
    margin-bottom: 5px;
}

.date-range {
    font-size: 14px;
    color: #666;
    margin-top: 5px;
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

.alert-error {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.btn-action {
    margin: 2px;
    font-size: 14px;
    padding: 5px 10px;
}

/* Mode sombre */
@media (prefers-color-scheme: dark) {
    .admin-section {
        background: #1a1a1a;
        color: #fff;
    }
    
    .annulation-card {
        background: #2a2a2a;
    }
    
    .form-group input,
    .form-group textarea,
    .form-group select {
        background: #2a2a2a;
        color: #fff;
        border-color: #444;
    }
    
    .date-range {
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

    <title>Gestion des Annulations - Admin</title>
    <meta name="description" content="Gestion des critères d'annulation du Club Canin Educachien Engis-Fagnes" />
<body>
    <!-- Container pour le header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Contenu principal -->
    <section class="section container-fluid text-justify">
        <div class="admin-header">
            <h1>Gestion des Critères d'Annulation</h1>
            <a href="member.php" class="btn-secondary">Retour à l'espace membre</a>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <div class="mt-4">
                <a href="admin-members.php" class="btn-secondary">Gestion des membres</a>
                <a href="admin-activities.php" class="btn-secondary">Gestion des activités</a>
                <a href="admin-documents.php" class="btn-secondary">Gestion des documents</a>
                <a href="index.php" class="btn-secondary">Retour à l'accueil</a>
        </div>
        <div class="admin-content">
            <div class="admin-section">
                <h2>Ajouter une nouvelle règle d'annulation</h2>
            <form method="POST" class="row g-3">
                <input type="hidden" name="action" value="add">
                
                <div class="col-md-6">
                    <label for="critere_valeur" class="form-label">Type d'activité</label>
                    <select name="critere_valeur" id="critere_valeur" class="form-select" required>
                        <option value="">Choisir un type d'activité...</option>
                        <?php foreach ($types_activite as $type): ?>
                            <option value="<?php echo htmlspecialchars($type); ?>"><?php echo htmlspecialchars($type); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="critere_type" value="type">
                </div>
                
                <div class="col-md-2">
                    <label for="date_debut" class="form-label">Date début (optionnel)</label>
                    <input type="date" name="date_debut" id="date_debut" class="form-control">
                </div>
                
                <div class="col-md-2">
                    <label for="date_fin" class="form-label">Date fin (optionnel)</label>
                    <input type="date" name="date_fin" id="date_fin" class="form-control">
                </div>
                
                <div class="col-md-2">
                    <label for="message" class="form-label">Message d'annulation</label>
                    <textarea name="message" id="message" class="form-control" rows="2" required 
                              placeholder="Message affiché lors de l'annulation"></textarea>
                </div>
                
                <div class="col-12">
                    <button type="submit" class="btn-admin">Ajouter la règle</button>
                </div>
            </form>
        </div>
        
            </div>
            
            <div class="admin-section">
                <h2>Règles d'annulation existantes (<?php echo count($annulations); ?>)</h2>
            
            <?php if (empty($annulations)): ?>
                <div class="alert alert-info">Aucune règle d'annulation configurée.</div>
            <?php else: ?>
                <?php foreach ($annulations as $annulation): ?>
                    <div class="annulation-card">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <span class="critere-badge">Type d'activité</span>
                                <strong><?php echo htmlspecialchars($annulation['critere_valeur']); ?></strong>
                            </div>
                            
                            <div class="col-md-4">
                                <?php if ($annulation['date_debut'] || $annulation['date_fin']): ?>
                                    <div class="date-range">
                                        <?php if ($annulation['date_debut']): ?>
                                            Du: <?php echo htmlspecialchars($annulation['date_debut']); ?>
                                        <?php endif; ?>
                                        <?php if ($annulation['date_fin']): ?>
                                            Au: <?php echo htmlspecialchars($annulation['date_fin']); ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-3">
                                <small><?php echo htmlspecialchars($annulation['message']); ?></small>
                            </div>
                            
                            <div class="col-md-2">
                                <button class="btn-admin btn-action" 
                                        onclick="editAnnulation(<?php echo htmlspecialchars(json_encode($annulation)); ?>)">
                                    Modifier
                                </button>
                                <button class="btn-danger btn-action" 
                                        onclick="deleteAnnulation(<?php echo $annulation['id']; ?>, '<?php echo htmlspecialchars($annulation['critere_valeur']); ?>')">
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>
    
    <!-- Modal pour modifier -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier la règle d'annulation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editForm">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" id="edit_id">
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit_critere_valeur" class="form-label">Type d'activité</label>
                                <select name="critere_valeur" id="edit_critere_valeur" class="form-select" required>
                                    <option value="">Choisir un type d'activité...</option>
                                    <?php foreach ($types_activite as $type): ?>
                                        <option value="<?php echo htmlspecialchars($type); ?>"><?php echo htmlspecialchars($type); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="critere_type" value="type">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="edit_date_debut" class="form-label">Date début (optionnel)</label>
                                <input type="date" name="date_debut" id="edit_date_debut" class="form-control">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="edit_date_fin" class="form-label">Date fin (optionnel)</label>
                                <input type="date" name="date_fin" id="edit_date_fin" class="form-control">
                            </div>
                            
                            <div class="col-12">
                                <label for="edit_message" class="form-label">Message d'annulation</label>
                                <textarea name="message" id="edit_message" class="form-control" rows="3" required></textarea>
                            </div>
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
    
    <script>
        function editAnnulation(annulation) {
            document.getElementById('edit_id').value = annulation.id;
            document.getElementById('edit_critere_valeur').value = annulation.critere_valeur;
            document.getElementById('edit_date_debut').value = annulation.date_debut || '';
            document.getElementById('edit_date_fin').value = annulation.date_fin || '';
            document.getElementById('edit_message').value = annulation.message;
            
            // Vérifier si l'option existe dans le select, sinon l'ajouter
            const select = document.getElementById('edit_critere_valeur');
            let optionExists = false;
            for (let option of select.options) {
                if (option.value === annulation.critere_valeur) {
                    optionExists = true;
                    break;
                }
            }
            if (!optionExists && annulation.critere_valeur) {
                const newOption = document.createElement('option');
                newOption.value = annulation.critere_valeur;
                newOption.textContent = annulation.critere_valeur;
                select.appendChild(newOption);
            }
            
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }
        
        function deleteAnnulation(id, critere) {
            if (confirm('Êtes-vous sûr de vouloir supprimer la règle "' + critere + '" ?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="${id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        // Types d'activité depuis la base de données
        const typesActivite = <?php echo json_encode($types_activite); ?>;
    </script>
</body>
</html> 