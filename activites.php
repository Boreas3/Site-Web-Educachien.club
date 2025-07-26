<?php
session_start();
require_once 'includes/config.php';
initSession();
require_once 'includes/functions.php';

// Récupérer les activités
$activitesAVenir = getActivitesAVenir();
$activitesPassees = getActivitesPassees();
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

/* Styles pour les onglets d'activités */
.nav-tabs .nav-link {
  color: #aaa !important;
  border: none !important;
  border-bottom: 2px solid transparent !important;
}

.nav-tabs .nav-link:hover {
  color: #000 !important;
  border-bottom: 2px solid #000 !important;
}

.nav-tabs .nav-link.active {
  color: #000 !important;
  background-color: transparent !important;
  border-bottom: 2px solid #000 !important;
}

/* Styles pour le thème sombre */
@media (prefers-color-scheme: dark) {
  .nav-tabs .nav-link:hover {
    color: #fff !important;
    border-bottom: 2px solid #fff !important;
  }

  .nav-tabs .nav-link.active {
    color: #fff !important;
    background-color: transparent !important;
    border-bottom: 2px solid #fff !important;
  }
}

.tab-content {
  margin-top: 20px !important;
}

/* Styles pour les événements */
.event-item {
  margin-bottom: 10px;
}

.accordion-button {
  background-color: #f8f9fa;
  border: 1px solid #dee2e6;
}

.accordion-button:not(.collapsed) {
  background-color: #e7f3ff;
  color: #0c63e4;
}

.text-red {
  color: #dc3545;
}

.event-date-column {
  width: 200px;
  vertical-align: top;
}

.event-title-column {
  vertical-align: top;
}

/* Styles pour les activités annulées */
.cancelled-activity .accordion-button {
  background-color: #f8f9fa;
  opacity: 0.8;
}

.cancelled-activity .accordion-button:hover {
  background-color: #e9ecef;
}

.cancelled-activity .badge {
  font-size: 0.75em;
  padding: 0.25em 0.5em;
}

/* Styles pour les activités annulées en mode sombre */
@media (prefers-color-scheme: dark) {
  .cancelled-activity .accordion-button {
    background-color: #000 !important;
    color: #fff !important;
    border-color: #333 !important;
  }
  
  .cancelled-activity .accordion-button:hover {
    background-color: #111 !important;
    color: #fff !important;
  }
  
  .cancelled-activity .accordion-button:not(.collapsed) {
    background-color: #000 !important;
    color: #fff !important;
  }
  
  .cancelled-activity .text-red {
    color: #fff !important;
  }
  
  /* Styles pour les activités normales en mode sombre */
  .accordion-button {
    background-color: #000 !important;
    color: #fff !important;
    border-color: #333 !important;
  }
  
  .accordion-button:hover {
    background-color: #111 !important;
    color: #fff !important;
  }
  
  .accordion-button:not(.collapsed) {
    background-color: #000 !important;
    color: #fff !important;
  }
  
  .text-red {
    color: #fff !important;
  }
  
  /* Styles pour les onglets en mode sombre */
  .nav-tabs .nav-link {
    color: #aaa !important;
  }
  
  .nav-tabs .nav-link:hover {
    color: #fff !important;
    border-bottom: 2px solid #fff !important;
  }
  
  .nav-tabs .nav-link.active {
    color: #fff !important;
    background-color: transparent !important;
    border-bottom: 2px solid #fff !important;
  }
}
</style>

    <!-- Montserrat font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    
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

    <title>Educachien Engis-Fagnes - Activités</title>
    <meta name="description" content="Educachien Engis-Fagnes - Les activités du club" />
    <meta name="keywords" content="chien,chiens,éducation,positive,club,canin,canine,Engis,Fagnes,Engis-Fagnes,dressage,chiot,Educachien,éducachien,club d'éducaction canin,club canin,éducation positive,méthode naturelle,CCEF,Club canin Engis-Fagnes,Club canin Saint-Georges" />
    <meta name="author" content="Educachien" />
    <meta name="copyright" content="© 2023 Educachien Engis-Fagnes ASBL" />
</head>

<body>
    <?php include 'includes/header.php'; ?>
    
    <!-- Image de fond -->
    <div class="landing-image">
        <img id="landingImage" src="assets/images/background/activites.jpg"/>
    </div>
    <br />
    <br />

    <!-- Contenu principal -->
    <section class="section container-fluid text-justify">
        <article>
            <h2 class="title text-center">Les activités du club</h2>
            <br />
        </article>
        <article>
            <div id="activites-container">
                <!-- Onglets -->
                <ul class="nav nav-tabs" id="activitesTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="avenir-tab" data-bs-toggle="tab" data-bs-target="#avenir" type="button" role="tab" aria-controls="avenir" aria-selected="true">
                            À venir (<?php echo count($activitesAVenir); ?>)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="passees-tab" data-bs-toggle="tab" data-bs-target="#passees" type="button" role="tab" aria-controls="passees" aria-selected="false">
                            Passées (<?php echo count($activitesPassees); ?>)
                        </button>
                    </li>
                </ul>
                
                <!-- Contenu des onglets -->
                <div class="tab-content" id="activitesTabsContent">
                    <!-- Onglet À venir -->
                    <div class="tab-pane fade show active" id="avenir" role="tabpanel" aria-labelledby="avenir-tab">
                        <div class="mt-4">
                            <?php if (empty($activitesAVenir)): ?>
                                <div class="alert alert-info">
                                    <p class="mb-0">Aucune activité à venir pour le moment.</p>
                                </div>
                            <?php else: ?>
                                <div class="accordion" id="accordionAvenir">
                                    <?php foreach ($activitesAVenir as $index => $activite): ?>
                                        <?php echo generateActiviteAccordionHTML($activite, 'avenir', $index); ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Onglet Passées -->
                    <div class="tab-pane fade" id="passees" role="tabpanel" aria-labelledby="passees-tab">
                        <div class="mt-4">
                            <?php if (empty($activitesPassees)): ?>
                                <div class="alert alert-info">
                                    <p class="mb-0">Aucune activité passée à afficher.</p>
                                </div>
                            <?php else: ?>
                                <div class="accordion" id="accordionPassees">
                                    <?php foreach ($activitesPassees as $index => $activite): ?>
                                        <?php echo generateActiviteAccordionHTML($activite, 'passees', $index); ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
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