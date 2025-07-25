<?php
session_start();
require_once 'includes/config.php';
initSession();
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

/* Styles pour les onglets d'années (comme activites.html) */
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

/* Styles pour les galeries */
.gallery-item a {
  color: rgb(158, 0, 1);
  font-weight: 500;
  transition: color 0.3s ease;
}

.gallery-item a:hover {
  color: rgb(120, 0, 1);
  text-decoration: underline !important;
}

/* Styles pour le thème sombre */
@media (prefers-color-scheme: dark) {
  .gallery-item a {
    color: #fff;
  }
  
  .gallery-item a:hover {
    color: #ccc;
  }
}

/* Responsive pour mobile */
@media (max-width: 768px) {
  .nav-tabs .nav-link {
    font-size: 14px;
    padding: 8px 12px;
  }
  
  .gallery-item {
    margin-bottom: 8px;
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

    <title>Educachien Engis-Fagnes - Photos</title>
    <meta name="description" content="Educachien Engis-Fagnes - Galeries photos" />
    <meta name="keywords" content="chien,chiens,éducation,positive,club,canin,canine,Engis,Fagnes,Engis-Fagnes,dressage,chiot,Educachien,éducachien,club d'éducaction canin,club canin,éducation positive,méthode naturelle,CCEF,Club canin Engis-Fagnes,Club canin Saint-Georges" />
    <meta name="author" content="Educachien" />
    <meta name="copyright" content="© 2023 Educachien Engis-Fagnes ASBL" />
</head>

<body>
    <!-- Container pour le header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Image de fond -->
    <div class="landing-image">
        <img id="landingImage" src="assets/images/background/photos.jpg"/>
    </div>
    <br />
    <br />

    <!-- Contenu principal -->
    <section class="section container-fluid text-justify">
        <article>
            <div>
                <h2 class="title text-center">Galeries photos</h2>
                <br />
                <div
                    id="galleriesList"
                    style="padding-left: 22px; padding-right: 22px"
                ></div>
            </div>
        </article>

        <script src="assets/scripts/photos/gallery-config.js?v=1.7.3&nocache=1"></script>

        <script src="assets/scripts/photos/galleries.js?v=1.7.3&nocache=1"></script>
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