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

/* Styles pour les boutons de basculement */
.btn-outline-warning.active {
  background-color: #ffc107;
  border-color: #ffc107;
  color: #000;
}

.btn-outline-info.active {
  background-color: #0dcaf0;
  border-color: #0dcaf0;
  color: #000;
}

.btn-outline-warning:hover {
  background-color: #ffc107;
  border-color: #ffc107;
  color: #000;
}

.btn-outline-info:hover {
  background-color: #0dcaf0;
  border-color: #0dcaf0;
  color: #000;
}

/* Styles pour les horaires - style minimal comme les autres pages */
.schedule-section {
  margin: 30px 0;
}

.schedule-section h3 {
  margin-bottom: 25px;
  font-weight: bold;
}

.schedule-section h4 {
  font-weight: 600;
  margin-bottom: 10px;
}

.schedule-section h5 {
  font-weight: 500;
  margin-bottom: 15px;
  color: #6c757d;
}

.schedule-period {
  font-size: 0.9em;
  color: #6c757d;
  font-style: italic;
  margin-top: 30px;
  text-align: center;
  border-top: 1px solid #dee2e6;
  padding-top: 15px;
}

/* Styles pour le thème sombre */
@media (prefers-color-scheme: dark) {
  .schedule-section h5 {
    color: #a0aec0;
  }
  
  .schedule-period {
    color: #a0aec0;
    border-top-color: #4a5568;
  }
  
  .btn-outline-warning.active {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
  }
  
  .btn-outline-info.active {
    background-color: #0dcaf0;
    border-color: #0dcaf0;
    color: #000;
  }
  
  .btn-outline-warning:hover {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
  }
  
  .btn-outline-info:hover {
    background-color: #0dcaf0;
    border-color: #0dcaf0;
    color: #000;
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

    <title>Educachien Engis-Fagnes - Horaires</title>
    <meta name="description" content="Educachien Engis-Fagnes - Les horaires du club" />
    <meta name="keywords" content="chien,chiens,éducation,positive,club,canin,canine,Engis,Fagnes,Engis-Fagnes,dressage,chiot,Educachien,éducachien,club d'éducaction canin,club canin,éducation positive,méthode naturelle,CCEF,Club canin Engis-Fagnes,Club canin Saint-Georges" />
    <meta name="author" content="Educachien" />
    <meta name="copyright" content="© 2023 Educachien Engis-Fagnes ASBL" />
</head>

<body>
    <!-- Container pour le header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Image de fond -->
    <div class="landing-image">
        <img id="landingImage" src="assets/images/background/cours2.jpg"/>
    </div>
    <br />
    <br />

    <!-- Contenu principal -->
    <section class="section container-fluid text-justify">
        <article>
            <div class="section section-team text-center">
                <div class="container">
                    <h2 class="title text-center">Les horaires</h2>
                    <br />
                    
                    <!-- Boutons de basculement -->
                    <div class="text-center mb-4">
                        <button class="btn btn-outline-warning me-2" id="show-summer" onclick="showSchedule('summer')">
                            Horaires d'été
                        </button>
                        <button class="btn btn-outline-info" id="show-winter" onclick="showSchedule('winter')">
                            Horaires d'hiver
                        </button>
                    </div>
                    
                    <!-- Horaires d'été -->
                    <div class="schedule-section" id="summer-schedule">
                        <h3>Horaires d'été</h3>
                        <div class="team">
                            <div class="row">
                                <div class="col-md-3">
                                    <h4>Education</h4>
                                    <h5>Chiots 2 à 8 mois</h5>
                                    <br />
                                    <b>mardi</b> et <b>vendredi</b><br />
                                    de 20h15 à 21h<br />
                                    <br />
                                    <br />
                                </div>
                                <div class="col-md-3">
                                    <h4>Education</h4>
                                    <h5>Chiens 8 mois et +</h5>
                                    <br />
                                    <b>mardi</b> et <b>vendredi</b><br />
                                    de 20h15 à 21h<br />
                                    <br />
                                    <b>samedi</b><br />
                                    de 10h à 11h<br />
                                    <br />
                                    <br />
                                </div>
                                <div class="col-md-3">
                                    <h4>Agility</h4>
                                    <h5>Chiens 12 mois et +</h5>
                                    <br />
                                    <b>samedi</b><br />
                                    de 9h à 11h<br />
                                    <!-- <small>⚠︎ Plus de nouvelles inscriptions pour le moment</small><br /> -->
                                    <br />
                                    <br />
                                </div>
                                <div class="col-md-3">
                                    <h4>Hooper</h4>
                                    <h5>Bonne connaissance des ordres de base nécessaire (positions, rappel et reste)</h5>
                                    <br />
                                    <b>samedi</b><br />
                                    de 9h à 11h<br />
                                    <br />
                                    <br />
                                </div>
                            </div>
                        </div>
                        <div class="schedule-period">
                            Horaires d'été = du 1er juillet au 1er octobre
                        </div>
                    </div>
                    
                    <!-- Horaires d'hiver -->
                    <div class="schedule-section" id="winter-schedule">
                        <h3>Horaires d'hiver</h3>
                        <div class="team">
                            <div class="row">
                                <div class="col-md-3">
                                    <h4>Education</h4>
                                    <h5>Chiots 2 à 8 mois</h5>
                                    <br />
                                    <b>mardi</b> et <b>vendredi</b><br />
                                    de 19h à 20h<br />
                                    <br />
                                    <br />
                                </div>
                                <div class="col-md-3">
                                    <h4>Education</h4>
                                    <h5>Chiens 8 mois et +</h5>
                                    <br />
                                    <b>mardi</b> et <b>vendredi</b><br />
                                    de 20h15 à 21h<br />
                                    <br />
                                    <b>samedi</b><br />
                                    de 11h à 12h<br />
                                    <br />
                                    <br />
                                </div>
                                <div class="col-md-3">
                                    <h4>Agility</h4>
                                    <h5>Chiens 12 mois et +</h5>
                                    <br />
                                    <b>samedi</b><br />
                                    de 9h à 11h<br />
                                    de 14h à 16h<br />
                                    <!-- <small>⚠︎ Plus de nouvelles inscriptions pour le moment</small><br /> -->
                                    <br />
                                    <br />
                                </div>
                                <div class="col-md-3">
                                    <h4>Hooper</h4>
                                    <h5>Bonne connaissance des ordres de base nécessaire (positions, rappel et reste)</h5>
                                    <br />
                                    <b>samedi</b><br />
                                    de 9h à 11h<br />
                                    <br />
                                    <br />
                                </div>
                            </div>
                        </div>
                        <div class="schedule-period">
                            Horaires d'hiver = du 1er octobre au 1er juillet
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

    <script>
        // Fonction pour déterminer la période actuelle
        function getCurrentPeriod() {
            const now = new Date();
            const currentMonth = now.getMonth() + 1; // getMonth() retourne 0-11
            const currentDay = now.getDate();
            
            // Été : du 1er juillet au 1er octobre
            if ((currentMonth === 7) || (currentMonth === 8) || (currentMonth === 9) || 
                (currentMonth === 10 && currentDay === 1)) {
                return 'summer';
            }
            // Hiver : du 1er octobre au 1er juillet
            else {
                return 'winter';
            }
        }
        
        // Fonction pour afficher un horaire spécifique
        function showSchedule(period) {
            const summerSchedule = document.getElementById('summer-schedule');
            const winterSchedule = document.getElementById('winter-schedule');
            const summerBtn = document.getElementById('show-summer');
            const winterBtn = document.getElementById('show-winter');
            
            if (period === 'summer') {
                summerSchedule.style.display = 'block';
                winterSchedule.style.display = 'none';
                summerBtn.classList.add('active');
                winterBtn.classList.remove('active');
            } else {
                summerSchedule.style.display = 'none';
                winterSchedule.style.display = 'block';
                summerBtn.classList.remove('active');
                winterBtn.classList.add('active');
            }
        }
        
        // Afficher automatiquement l'horaire de la période actuelle
        document.addEventListener('DOMContentLoaded', function() {
            const currentPeriod = getCurrentPeriod();
            showSchedule(currentPeriod);
        });
    </script>
</body>
</html> 