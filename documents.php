<?php
session_start();
require_once 'includes/config.php';
initSession();

// Récupérer les documents publics depuis la base de données
$documents = [];
try {
    $pdo = getDBConnection();
    // Récupérer seulement les documents publics (pas archivés)
    $stmt = $pdo->prepare("SELECT * FROM documents WHERE access_level = 'public' AND active = 1 AND access_level != 'archived' ORDER BY category, title");
    $stmt->execute();
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // En cas d'erreur, on continue avec les documents statiques
}

// Organiser les documents par catégorie
$documents_by_category = [];
foreach ($documents as $doc) {
    $category = $doc['category'];
    if (!isset($documents_by_category[$category])) {
        $documents_by_category[$category] = [];
    }
    $documents_by_category[$category][] = $doc;
}

// Noms des catégories pour l'affichage
$category_names = [
    'formulaires' => 'Fiches d\'inscription',
    'tests' => 'Tests de sociabilité',
    'entrainements' => 'Plans d\'entraînement',
    'reglements' => 'Règlements',
    'animations' => 'Animations',
    'divers' => 'Autres documents'
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
  padding-left: 22px !important;
  padding-right: 22px !important;
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
  max-width: 800px !important;
  margin: 0 auto !important;
  padding: 0 22px !important;
  padding-top: 100px !important;
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


.cloture {

color: #dc3545 !important;

font-weight: bold !important;

background-color: #f8d7da !important;

padding: 2px 6px !important;

border-radius: 4px !important;

font-size: 0.9em !important;

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

    <title>Educachien Engis-Fagnes - Documents</title>
    <meta name="description" content="Educachien Engis-Fagnes - Documents à télécharger" />
    <meta name="keywords" content="chien,chiens,éducation,positive,club,canin,canine,Engis,Fagnes,Engis-Fagnes,dressage,chiot,Educachien,éducachien,club d'éducaction canin,club canin,éducation positive,méthode naturelle,CCEF,Club canin Engis-Fagnes,Club canin Saint-Georges" />
    <meta name="author" content="Educachien" />
    <meta name="copyright" content="© 2023 Educachien Engis-Fagnes ASBL" />
</head>

<body>
    <!-- Container pour le header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Image de fond -->
    <div class="landing-image">
        <img id="landingImage" src="assets/images/background/document.jpg"/>
    </div>
    <br />
    <br />

    <!-- Contenu principal -->
    <section class="section container-fluid text-justify">
        <article>
            <h2 class="title text-center">Documents</h2>
            <br />
            <?php if (!empty($documents_by_category)): ?>
                <div class="row">
                    <?php foreach ($documents_by_category as $category => $docs): ?>
                        <div class="col-md-6">
                            <h4><?php echo $category_names[$category] ?? ucfirst($category); ?></h4>
                            <ul>
                                <?php foreach ($docs as $doc): ?>
                                    <li><a href="<?php echo $doc['filepath']; ?>" target="_blank"><?php echo htmlspecialchars($doc['title']); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <!-- Fallback vers les documents statiques si la base de données n'est pas disponible -->
                <div class="row">
                    <div class="col-md-6">
                        <h4>Fiches d'inscription</h4>
                        <ul>
                            <li><a href="assets/documents/01-formulaires/Fiche inscription.pdf" target="_blank">Fiche inscription générale</a></li>
                            
                            <li><s><a href="assets/documents/01-formulaires/Fiche inscription Test soc 2023.pdf" target="_blank">Fiche inscription Test sociabilité 2023</a></s> <span class="cloture">inscriptions clôturées</span></li>

                            <li><s><a href="assets/documents/01-formulaires/Fiche inscription Test soc 2024.pdf" target="_blank">Fiche inscription Test sociabilité 2024</a></s> <span class="cloture">inscriptions clôturées</span></li>

                            <li><s><a href="assets/documents/01-formulaires/Fiche inscription Test soc 2025.pdf" target="_blank">Fiche inscription Test sociabilité 2025</a></s> <span class="cloture">inscriptions clôturées</span></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h4>Animations</h4>
                        <ul>
                            <li><a href="assets/documents/05-animations/Animations-2023-hiver.pdf" target="_blank">Animations hiver 2023</a></li>
                            <li><a href="assets/documents/05-animations/Animations-2024-fin-année.pdf" target="_blank">Animations fin d'année 2024</a></li>
                        </ul>
                        <h4>Autres documents</h4>
                        <ul>
                            <li><a href="assets/documents/06-divers/TS-2024.pdf" target="_blank">Test sociabilité 2024</a></li>
                            <li><a href="assets/documents/06-divers/Muselière.pdf" target="_blank">Muselière</a></li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </article>
    </section>

    <footer>
        <hr width="100%" />
        <div class="text-center">
            <small class="large-screen-only">© 2025 Educachien Engis-Fagnes ASBL. Tous droits réservés.</small>
            <small class="small-screen-only">© 2025 Educachien Engis-Fagnes ASBL.<br/>Tous droits réservés.</small>
        </div>
    </footer>


</body>
</html> 