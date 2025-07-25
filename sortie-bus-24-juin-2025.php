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

/* Styles pour la galerie photos */
.gallery-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.gallery-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
  margin-top: 30px;
}

.gallery-item {
  position: relative;
  overflow: hidden;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  transition: transform 0.3s ease;
  cursor: pointer;
}

.gallery-item:hover {
  transform: scale(1.02);
}

.gallery-item img {
  width: 100%;
  height: 250px;
  object-fit: cover;
  display: block;
}

.back-button {
  display: inline-block;
  margin-bottom: 20px;
  padding: 10px 20px;
  background-color: rgb(158, 0, 1);
  color: white;
  text-decoration: none;
  border-radius: 5px;
  transition: background-color 0.3s ease;
}

.back-button:hover {
  background-color: rgb(120, 0, 1);
  color: white;
  text-decoration: none;
}

/* Visionneuse photo modale */
.photo-viewer {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.9);
  backdrop-filter: blur(5px);
}

.photo-viewer.active {
  display: flex;
  align-items: center;
  justify-content: center;
}

.viewer-content {
  position: relative;
  max-width: 90%;
  max-height: 90%;
  text-align: center;
}

.viewer-image {
  max-width: 100%;
  max-height: 90vh;
  object-fit: contain;
  border-radius: 8px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
}

.viewer-close {
  position: absolute;
  top: -40px;
  right: 0;
  color: white;
  font-size: 30px;
  font-weight: bold;
  cursor: pointer;
  background: none;
  border: none;
  padding: 10px;
  border-radius: 50%;
  transition: background-color 0.3s ease;
}

.viewer-close:hover {
  background-color: rgba(255, 255, 255, 0.2);
}

.viewer-nav {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  color: white;
  font-size: 40px;
  font-weight: bold;
  cursor: pointer;
  background: rgba(0, 0, 0, 0.5);
  border: none;
  padding: 20px 15px;
  border-radius: 50%;
  transition: background-color 0.3s ease;
  user-select: none;
}

.viewer-nav:hover {
  background-color: rgba(0, 0, 0, 0.8);
}

.viewer-prev {
  left: 20px;
}

.viewer-next {
  right: 20px;
}

.viewer-counter {
  position: absolute;
  bottom: -40px;
  left: 50%;
  transform: translateX(-50%);
  color: white;
  font-size: 16px;
  background-color: rgba(0, 0, 0, 0.7);
  padding: 8px 16px;
  border-radius: 20px;
}

/* Responsive pour mobile */
@media (max-width: 768px) {
  .viewer-nav {
    font-size: 30px;
    padding: 15px 10px;
  }
  
  .viewer-prev {
    left: 10px;
  }
  
  .viewer-next {
    right: 10px;
  }
  
  .viewer-close {
    top: -30px;
    font-size: 25px;
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

    <title>Educachien Engis-Fagnes - Galerie Photos</title>
    <meta name="description" content="Educachien Engis-Fagnes - Galerie photos" />
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
        <div id="gallery-content">
            <!-- Le contenu de la galerie sera injecté ici par JavaScript -->
        </div>

        <script src="assets/scripts/photos/gallery-config.js?v=1.7.2"></script>
        <script src="assets/scripts/photos/gallery-template.js?v=1.7.2"></script>

        <script>
            // Détecter l'ID de la galerie depuis l'URL
            function getGalleryIdFromUrl() {
                const path = window.location.pathname;
                const filename = path.split('/').pop();
                return filename.replace('.php', '');
            }

            // Initialiser la galerie
            document.addEventListener('DOMContentLoaded', function() {
                const galleryId = getGalleryIdFromUrl();
                const config = getGalleryConfig(galleryId);
                
                if (config) {
                    // Créer la galerie avec la configuration
                    const gallery = createPhotoGallery(config);
                    
                    // Injecter le HTML de la galerie
                    const galleryContent = document.getElementById('gallery-content');
                    galleryContent.innerHTML = gallery.generateGalleryHTML();
                    
                    // Initialiser la visionneuse
                    gallery.initPhotoViewer();
                    
                    // Mettre à jour le titre de la page
                    document.title = `Educachien Engis-Fagnes - ${config.title}`;
                } else {
                    // Galerie non trouvée
                    document.getElementById('gallery-content').innerHTML = `
                        <div class="gallery-container">
                            <a href="photos.php" class="back-button">← Retour aux galeries</a>
                            <h2 class="title text-center">Galerie non trouvée</h2>
                            <p class="text-center">Cette galerie n'existe pas ou a été supprimée.</p>
                        </div>
                    `;
                }
            });
        </script>
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