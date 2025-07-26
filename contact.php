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
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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

    <title>Educachien Engis-Fagnes - Contact</title>
    <meta name="description" content="Educachien Engis-Fagnes - Contactez nous" />
    <meta name="keywords" content="chien,chiens,éducation,positive,club,canin,canine,Engis,Fagnes,Engis-Fagnes,dressage,chiot,Educachien,éducachien,club d'éducaction canin,club canin,éducation positive,méthode naturelle,CCEF,Club canin Engis-Fagnes,Club canin Saint-Georges" />
    <meta name="author" content="Educachien" />
    <meta name="copyright" content="© 2023 Educachien Engis-Fagnes ASBL" />
</head>

<body>
    <!-- Container pour le header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Image de fond -->
    <div class="landing-image">
        <img id="landingImage" src="assets/images/background/contact.jpg"/>
    </div>
    <br />
    <br />

    <!-- Contenu principal -->
    <section class="section container-fluid">
        <article>
            <h2 class="title text-center small-screen-only">EDUCACHIEN<br>Engis-Fagnes ASBL</h2>
            <h2 class="title text-center large-screen-only">EDUCACHIEN Engis-Fagnes ASBL</h2>
            <br />
        </article>
        <article>
            <div class="row">
                <div class="col-md-4">
                    <div class="text-center column">
                        <h4>Téléphone</h4>
                        0495/281.749
                        <br />
                        <a href="tel:0495/281.749" class="contact_phone" alt="0495/281.749">
                            <i class="bi bi-telephone-fill fs-5"></i>
                        </a>
                        <span class="mx-3"></span>
                        <a href="sms:0495/281.749" class="contact_sms" alt="0495/281.749">
                            <i class="bi bi-chat-fill fs-5"></i>
                        </a>
                        <br />
                        <small>pas de numéro masqué</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center column">
                        <h4>Adresse du club</h4>
                        <div>
                            Rue des Alunières 18 A<br>
                            4480 ENGIS
                        </div>
                        <a href="http://maps.apple.com/?q=Educachien&ll=50.59025,5.40076&z=21" class="map-icon" target="_new">
                            <img src="assets/images/applemaps.png">
                        </a>
                        <span class="mx-2"></span>
                        <a href="https://goo.gl/maps/XJ2RKSy55N2Kzhcx6?coh=178573&entry=tt" class="map-icon" target="_new">
                            <img src="assets/images/gmaps.png">
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center column">
                        <h4>Email</h4>
                        contact@educachien.club
                        <br />
                        <a href="mailto:contact@educachien.club?subject=" class="contact_email" alt="contact@educachien.club">
                            <i class="bi bi-envelope-fill fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>
            <p class="text-center mt-4">
                Affilié à l'URCSH sous le Num 1173<br>
                Numéro d'entreprise : 0.866.364.111<br>
                IBAN : BE24 0015 8758 3438<br><br>
                <span class="large-screen-only">Siège social : Rue des Alunières 14, 4480 ENGIS</span>
                <span class="small-screen-only">Siège social :<br>Rue des Alunières 14, 4480 ENGIS</span>
            </p>
            <div class="text-center">
                <a href="/assets/vcard.vcf" class="contact_vcard vcard">
                    <i class="bi bi-person-vcard-fill fs-5"></i>
                </a>
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