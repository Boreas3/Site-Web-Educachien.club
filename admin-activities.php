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
                    $titre = sanitize($_POST['titre']);
                    $date = sanitize($_POST['date']);
                    $heure_debut = sanitize($_POST['heure_debut']);
                    $heure_fin = sanitize($_POST['heure_fin']);
                    $description = sanitize($_POST['description']);
                    $lieu = sanitize($_POST['lieu']);
                    $prix = sanitize($_POST['prix']);
                    $modalites = sanitize($_POST['modalites']);
                    $type = sanitize($_POST['type']);
                    
                    $stmt = $pdo->prepare("INSERT INTO activites (titre, date, heure_debut, heure_fin, description, lieu, prix, modalites, type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$titre, $date, $heure_debut, $heure_fin, $description, $lieu, $prix, $modalites, $type]);
                    
                    $message = "Activité '$titre' ajoutée avec succès !";
                    break;
                    
                case 'edit':
                    $id = sanitize($_POST['id']);
                    $titre = sanitize($_POST['titre']);
                    $date = sanitize($_POST['date']);
                    $heure_debut = sanitize($_POST['heure_debut']);
                    $heure_fin = sanitize($_POST['heure_fin']);
                    $description = sanitize($_POST['description']);
                    $lieu = sanitize($_POST['lieu']);
                    $prix = sanitize($_POST['prix']);
                    $modalites = sanitize($_POST['modalites']);
                    $type = sanitize($_POST['type']);
                    
                    $stmt = $pdo->prepare("UPDATE activites SET titre = ?, date = ?, heure_debut = ?, heure_fin = ?, description = ?, lieu = ?, prix = ?, modalites = ?, type = ? WHERE id = ?");
                    $stmt->execute([$titre, $date, $heure_debut, $heure_fin, $description, $lieu, $prix, $modalites, $type, $id]);
                    
                    $message = "Activité '$titre' modifiée avec succès !";
                    break;
                    
                case 'delete':
                    $id = sanitize($_POST['id']);
                    
                    $stmt = $pdo->prepare("DELETE FROM activites WHERE id = ?");
                    $stmt->execute([$id]);
                    
                    $message = "Activité supprimée avec succès !";
                    break;
            }
        } catch (Exception $e) {
            $error = "Erreur de base de données : " . $e->getMessage();
        }
    }
}

// Charger les activités existantes depuis la base de données
$activities = [];
try {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT id, titre, date, heure_debut, heure_fin, description, lieu, prix, modalites, type FROM activites ORDER BY date DESC");
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = "Erreur lors du chargement des activités : " . $e->getMessage();
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
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: rgb(158, 0, 1);
    box-shadow: 0 0 0 2px rgba(158, 0, 1, 0.2);
}

.activities-list {
    display: grid;
    gap: 15px;
}

.activity-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid rgb(158, 0, 1);
}

.activity-info h3 {
    margin: 0 0 5px 0;
    color: rgb(158, 0, 1);
}

.activity-info p {
    margin: 5px 0;
    color: #666;
}

.activity-actions {
    display: flex;
    gap: 10px;
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
    
    .activity-item {
        background: #2a2a2a;
    }
    
    .form-group input,
    .form-group textarea {
        background: #2a2a2a;
        color: #fff;
        border-color: #444;
    }
    
    .activity-info p {
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

    <title>Gestion des Activités - Admin</title>
    <meta name="description" content="Gestion des activités du Club Canin Educachien Engis-Fagnes" />
</head>

<body>
    <!-- Container pour le header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Contenu principal -->
    <section class="section container-fluid text-justify">
        <div class="admin-header">
            <h1>Gestion des Activités</h1>
            <a href="member.php" class="btn-secondary">Retour à l'espace membre</a>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        <div class="mt-4">
                <a href="admin-members.php" class="btn btn-secondary">Gestion des membres</a>
                <a href="admin-documents.php" class="btn btn-secondary">Gestion des documents</a>
                <a href="admin-annulations.php" class="btn btn-secondary">Gestion des annulations</a>
                <a href="index.php" class="btn btn-secondary">Retour à l'accueil</a>
        </div>
        <div class="admin-content">
            <div class="admin-section">
                <h2>Ajouter une nouvelle activité</h2>
                <form method="POST" class="admin-form">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="form-group">
                        <label for="titre">Titre :</label>
                        <input type="text" id="titre" name="titre" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="date">Date :</label>
                        <input type="date" id="date" name="date" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="heure_debut">Heure de début :</label>
                        <input type="time" id="heure_debut" name="heure_debut" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="heure_fin">Heure de fin :</label>
                        <input type="time" id="heure_fin" name="heure_fin" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description :</label>
                        <textarea id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="lieu">Lieu :</label>
                        <input type="text" id="lieu" name="lieu">
                    </div>
                    
                    <div class="form-group">
                        <label for="prix">Prix :</label>
                        <input type="text" id="prix" name="prix" placeholder="ex: 5€/personne">
                    </div>
                    
                    <div class="form-group">
                        <label for="modalites">Modalités :</label>
                        <textarea id="modalites" name="modalites" rows="3" placeholder="Conditions de participation..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="type">Type d'activité :</label>
                        <select id="type" name="type" required>
                            <option value="">Sélectionner un type</option>
                            <option value="balade">Balade éducative</option>
                            <option value="formation">Formation</option>
                            <option value="evenement">Événement</option>
                            <option value="test">Test de sociabilité</option>
                            <option value="travaux">Travaux</option>
                            <option value="jeux">Jeux</option>
                            <option value="demo">Démonstration</option>
                            <option value="salon">Salon</option>
                            <option value="fermeture">Fermeture</option>
                            <option value="rassemblement">Rassemblement</option>
                            <option value="training">Training</option>
                            <option value="conference">Conférence</option>
                            <option value="ag">Assemblée générale</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn-admin">Ajouter l'activité</button>
                </form>
            </div>
            
            <div class="admin-section">
                <h2>Activités existantes</h2>
                <?php if (empty($activities)): ?>
                    <p>Aucune activité trouvée dans la base de données.</p>
                <?php else: ?>
                <div class="activities-list">
                    <?php foreach ($activities as $activity): ?>
                    <div class="activity-item">
                        <div class="activity-info">
                            <h3><?php echo htmlspecialchars($activity['titre']); ?></h3>
                            <p><strong>Date :</strong> <?php echo htmlspecialchars($activity['date']); ?></p>
                            <p><strong>Heures :</strong> <?php echo htmlspecialchars(substr($activity['heure_debut'], 0, 5)); ?> - <?php echo htmlspecialchars(substr($activity['heure_fin'], 0, 5)); ?></p>
                            <p><strong>Lieu :</strong> <?php echo htmlspecialchars($activity['lieu']); ?></p>
                            <p><strong>Prix :</strong> <?php echo htmlspecialchars($activity['prix']); ?></p>
                            <p><strong>Type :</strong> <?php echo htmlspecialchars($activity['type']); ?></p>
                            <?php if (!empty($activity['description'])): ?>
                                <p><strong>Description :</strong> <?php echo htmlspecialchars($activity['description']); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($activity['modalites'])): ?>
                                <p><strong>Modalités :</strong> <?php echo htmlspecialchars($activity['modalites']); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="activity-actions">
                            <button type="button" class="btn-secondary" 
                                    onclick="openEditModal(<?php echo htmlspecialchars(json_encode($activity)); ?>)">
                                Modifier
                            </button>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $activity['id']; ?>">
                                <button type="submit" class="btn-danger" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette activité ?')">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
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

    <!-- Modal d'édition -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Modifier l'activité</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" id="edit_id">
                        
                        <div class="form-group">
                            <label for="edit_titre">Titre :</label>
                            <input type="text" id="edit_titre" name="titre" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_date">Date :</label>
                            <input type="date" id="edit_date" name="date" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_heure_debut">Heure de début :</label>
                            <input type="time" id="edit_heure_debut" name="heure_debut" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_heure_fin">Heure de fin :</label>
                            <input type="time" id="edit_heure_fin" name="heure_fin" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_description">Description :</label>
                            <textarea id="edit_description" name="description" rows="3"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_lieu">Lieu :</label>
                            <input type="text" id="edit_lieu" name="lieu">
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_prix">Prix :</label>
                            <input type="text" id="edit_prix" name="prix" placeholder="ex: 5€/personne">
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_modalites">Modalités :</label>
                            <textarea id="edit_modalites" name="modalites" rows="3" placeholder="Conditions de participation..."></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_type">Type d'activité :</label>
                            <select id="edit_type" name="type" required>
                                <option value="">Sélectionner un type</option>
                                <option value="balade">Balade éducative</option>
                                <option value="formation">Formation</option>
                                <option value="evenement">Événement</option>
                                <option value="test">Test de sociabilité</option>
                                <option value="travaux">Travaux</option>
                                <option value="jeux">Jeux</option>
                                <option value="demo">Démonstration</option>
                                <option value="salon">Salon</option>
                                <option value="fermeture">Fermeture</option>
                                <option value="rassemblement">Rassemblement</option>
                                <option value="training">Training</option>
                                <option value="conference">Conférence</option>
                                <option value="ag">Assemblée générale</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn-admin">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function openEditModal(activity) {
        // Remplir les champs du modal avec les données de l'activité
        document.getElementById('edit_id').value = activity.id;
        document.getElementById('edit_titre').value = activity.titre;
        document.getElementById('edit_date').value = activity.date;
        document.getElementById('edit_heure_debut').value = activity.heure_debut.substring(0, 5);
        document.getElementById('edit_heure_fin').value = activity.heure_fin.substring(0, 5);
        document.getElementById('edit_description').value = activity.description || '';
        document.getElementById('edit_lieu').value = activity.lieu || '';
        document.getElementById('edit_prix').value = activity.prix || '';
        document.getElementById('edit_modalites').value = activity.modalites || '';
        document.getElementById('edit_type').value = activity.type;
        
        // Ouvrir le modal
        var modal = new bootstrap.Modal(document.getElementById('editModal'));
        modal.show();
    }
    </script>

    <script src="assets/scripts/tables.js?v=<?php echo time(); ?>"></script>
</body>
</html> 