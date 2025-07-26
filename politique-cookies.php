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
</style>

    <!-- Montserrat font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />

    <!-- moment.js -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="assets/scripts/moment-locale.js?v=1.7.1"></script>

    <script src="assets/scripts/create-ics.js?v=1.7.1"></script>
    <script src="assets/scripts/tables.js?v=1.7.1"></script>

    <!-- CSS pour la gestion des cookies -->
    <link rel="stylesheet" href="assets/scripts/cookie-manager.css">

    <!-- JavaScript pour la gestion des cookies -->
    <script src="assets/scripts/cookie-manager.js"></script>

    <!-- Script pour ajouter le lien politique de cookies dans le footer -->
    <script src="assets/scripts/footer-cookies.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" type="text/css" href="main.css?v=1.7.1" />

    <link rel="icon" href="assets/images/favicon.png" sizes="any" type="image/png" />
    <link rel="apple-touch-icon" href="assets/images/logo.png" />

    <title>Politique de Cookies - Educachien Engis-Fagnes</title>
    <meta name="description" content="Politique de cookies du site Educachien Engis-Fagnes ASBL. Découvrez comment nous utilisons les cookies et comment les gérer.">
    <meta name="keywords" content="politique cookies, cookies site web, gestion cookies, Educachien Engis-Fagnes, club canin" />
    <meta name="author" content="Educachien Engis-Fagnes ASBL" />
    <meta name="copyright" content="© 2025 Educachien Engis-Fagnes ASBL" />
    
    <!-- Open Graph -->
    <meta property="og:title" content="Politique de Cookies - Educachien Engis-Fagnes">
    <meta property="og:description" content="Politique de cookies du site Educachien Engis-Fagnes ASBL.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.educachien.club/politique-cookies.php">
    <meta property="og:image" content="https://www.educachien.club/assets/images/logo.png" />
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Politique de Cookies - Educachien Engis-Fagnes" />
    <meta name="twitter:description" content="Politique de cookies du site Educachien Engis-Fagnes ASBL." />
    <meta name="twitter:image" content="https://www.educachien.club/assets/images/logo.png" />
    
    <link rel="canonical" href="https://www.educachien.club/politique-cookies.php">
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
            <h2 class="text-center">Politique de Cookies</h2>
            <br />
            <p class="text-center">Dernière mise à jour : <?php echo date('d/m/Y'); ?></p>
            <br />
            <hr style="width:200px;margin:auto">
            <br />
            <br />
        </article>
        
        <article>
            <h4>Qu'est-ce qu'un cookie ?</h4>
            <p class="text-justify">Un cookie est un petit fichier texte stocké sur votre ordinateur ou appareil mobile lorsque vous visitez un site web. Les cookies sont largement utilisés pour faire fonctionner les sites web ou les faire fonctionner plus efficacement, ainsi que pour fournir des informations aux propriétaires du site.</p>
            
            <h4>Comment utilisons-nous les cookies ?</h4>
            <p class="text-justify">Sur le site Educachien Engis-Fagnes, nous utilisons les cookies pour :</p>
            <ul>
                <li><strong>Cookies de session :</strong> Ces cookies sont temporaires et sont supprimés lorsque vous fermez votre navigateur. Ils nous aident à maintenir votre session active pendant votre visite sur notre site.</li>
                <li><strong>Cookies de préférences :</strong> Ces cookies mémorisent vos choix et préférences (comme la langue, la région, etc.) pour améliorer votre expérience utilisateur.</li>
                <li><strong>Cookies de sécurité :</strong> Ces cookies nous aident à sécuriser votre connexion et à protéger contre les attaques malveillantes.</li>
                <li><strong>Cookies analytiques :</strong> Ces cookies nous permettent de comprendre comment les visiteurs utilisent notre site, ce qui nous aide à améliorer nos services.</li>
            </ul>
            
            <h4>Types de cookies que nous utilisons</h4>
            
            <h5>Cookies essentiels</h5>
            <p class="text-justify">Ces cookies sont nécessaires au fonctionnement du site web et ne peuvent pas être désactivés dans nos systèmes. Ils ne stockent généralement aucune information personnelle identifiable.</p>
            <ul>
                <li><strong>Session de connexion :</strong> Maintient votre session active lorsque vous vous connectez à votre espace membre</li>
                <li><strong>Sécurité :</strong> Protège contre les attaques CSRF et autres menaces de sécurité</li>
            </ul>
            
            <h5>Cookies de performance</h5>
            <p class="text-justify">Ces cookies nous permettent de compter les visites et les sources de trafic afin de mesurer et d'améliorer les performances de notre site.</p>
            <ul>
                <li><strong>Google Analytics :</strong> Nous utilisons Google Analytics pour analyser l'utilisation de notre site et améliorer nos services</li>
            </ul>
            
            <h5>Cookies de fonctionnalité</h5>
            <p class="text-justify">Ces cookies permettent au site web de mémoriser les choix que vous faites et de fournir des fonctionnalités améliorées et plus personnalisées.</p>
            <ul>
                <li><strong>Préférences d'affichage :</strong> Mémorise vos préférences d'affichage et de navigation</li>
                <li><strong>Gestion des cookies :</strong> Mémorise vos choix concernant l'acceptation des cookies</li>
            </ul>
            
            <h4>Gestion des cookies</h4>
            <p class="text-justify">Vous pouvez contrôler et/ou supprimer des cookies comme vous le souhaitez. Vous pouvez supprimer tous les cookies déjà présents sur votre ordinateur et configurer la plupart des navigateurs pour les empêcher d'en enregistrer.</p>
            
            <h5>Comment désactiver les cookies</h5>
            <p class="text-justify">Vous pouvez configurer votre navigateur pour refuser tous les cookies ou pour indiquer quand un cookie est envoyé. Cependant, si vous n'acceptez pas les cookies, vous ne pourrez peut-être pas utiliser certaines parties de notre site.</p>
            
            <h5>Paramètres par navigateur</h5>
            <ul>
                <li><strong>Chrome :</strong> Paramètres > Confidentialité et sécurité > Cookies et autres données de sites</li>
                <li><strong>Firefox :</strong> Options > Confidentialité et sécurité > Cookies et données de sites</li>
                <li><strong>Safari :</strong> Préférences > Confidentialité > Cookies et données de sites web</li>
                <li><strong>Edge :</strong> Paramètres > Cookies et autorisations de sites > Cookies et données de sites</li>
            </ul>
            
            <h4>Cookies tiers</h4>
            <p class="text-justify">Notre site peut également utiliser des cookies provenant de tiers, notamment :</p>
            <ul>
                <li><strong>Google Analytics :</strong> Pour analyser l'utilisation de notre site</li>
                <li><strong>Bootstrap :</strong> Pour le bon fonctionnement de l'interface utilisateur</li>
            </ul>
            
            <h4>Mise à jour de cette politique</h4>
            <p class="text-justify">Nous pouvons mettre à jour cette politique de cookies de temps à autre. Nous vous encourageons à consulter régulièrement cette page pour rester informé de la façon dont nous utilisons les cookies.</p>
            
            <h4>Contact</h4>
            <p class="text-justify">Si vous avez des questions concernant notre politique de cookies, n'hésitez pas à nous contacter :</p>
            <ul>
                <li>Par email : <a href="mailto:contact@educachien.club">contact@educachien.club</a></li>
                <li>Via notre page <a href="contact.php">Contact</a></li>
            </ul>
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
