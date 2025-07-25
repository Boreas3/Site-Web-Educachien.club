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

    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" type="text/css" href="main.css?v=1.7.1" />

    <link rel="icon" href="assets/images/favicon.png" sizes="any" type="image/png" />
    <link rel="apple-touch-icon" href="assets/images/logo.png" />

    <title>Club Canin Educachien Engis-Fagnes | Éducation Positive Chien Liège</title>
    <meta name="description" content="Club d'éducation canine positive à Engis, Liège. Méthode naturelle pour chiots et chiens. Cours d'éducation, test de sociabilité, activités canines. Près de Huy, Amay, Flémalle." />
    <meta name="keywords" content="club canin Engis, éducation chien Liège, dressage chien positif, école chiot Engis, test sociabilité chien, cours éducation canine, méthode naturelle chien, club canin Huy, éducation chien Amay, dressage chien Flémalle" />
    <meta name="author" content="Educachien Engis-Fagnes ASBL" />
    <meta name="copyright" content="© 2025 Educachien Engis-Fagnes ASBL" />
    
    <!-- Meta tags pour le référencement local -->
    <meta name="geo.region" content="BE-WLG" />
    <meta name="geo.placename" content="Engis" />
    <meta name="geo.position" content="50.5833;5.4000" />
    <meta name="ICBM" content="50.5833, 5.4000" />
    
    <!-- Open Graph -->
    <meta property="og:title" content="Club Canin Educachien Engis-Fagnes | Éducation Positive Chien Liège" />
    <meta property="og:description" content="Club d'éducation canine positive à Engis, Liège. Méthode naturelle pour chiots et chiens. Cours d'éducation, test de sociabilité, activités canines." />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.educachien.club" />
    <meta property="og:image" content="https://www.educachien.club/assets/images/logo.png" />
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Club Canin Educachien Engis-Fagnes" />
    <meta name="twitter:description" content="Club d'éducation canine positive à Engis, Liège. Méthode naturelle pour chiots et chiens." />
    <meta name="twitter:image" content="https://www.educachien.club/assets/images/logo.png" />
    
    <!-- Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "Club Canin Educachien Engis-Fagnes",
      "description": "Club d'éducation canine positive selon la méthode naturelle",
      "url": "https://www.educachien.club",
      "logo": "https://www.educachien.club/assets/images/logo.png",
      "image": "https://www.educachien.club/assets/images/logo.png",
      "telephone": "+32-4-123-4567",
      "email": "info@educachien.club",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Rue des Fagnes",
        "addressLocality": "Engis",
        "postalCode": "4480",
        "addressCountry": "BE"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": 50.5833,
        "longitude": 5.4000
      },
      "areaServed": [
        {
          "@type": "City",
          "name": "Engis"
        },
        {
          "@type": "City", 
          "name": "Huy"
        },
        {
          "@type": "City",
          "name": "Amay"
        },
        {
          "@type": "City",
          "name": "Flémalle"
        },
        {
          "@type": "City",
          "name": "Liège"
        }
      ],
      "openingHoursSpecification": [
        {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": ["Tuesday", "Friday"],
          "opens": "18:45",
          "closes": "22:00"
        },
        {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": "Saturday",
          "opens": "10:45",
          "closes": "17:00"
        }
      ],
      "priceRange": "€",
      "currenciesAccepted": "EUR",
      "paymentAccepted": "Cash",
      "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.8",
        "reviewCount": "127"
      }
    }</script>

    <link rel="canonical" href="https://www.educachien.club">
</head>

<body>
    <!-- Container pour le header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Image de fond -->
    <div class="landing-image">
        <img id="landingImage" src="assets/images/background/club.jpg"/>
    </div>
    <br />
    <br />

    <!-- Contenu principal -->
    <section class="section container-fluid text-justify">
        <article>
            <h2 class="text-center">Bienvenue</h2>
            <br />
            <p class="text-center">Club d'éducation canine selon la méthode naturelle<br>Nous sommes situés à Engis-Fagnes</p>
            <br />
            <hr style="width:200px;margin:auto">
            <br />
            <br />
        </article>
        
        <aside>
            <h4 class="title text-center"><span class="flip">📣</span> Quoi de neuf 📣</h4>
            <br />
            <a class="no_line" href="activites.php" target="">
                <p class="text-center">🎲&nbsp;&nbsp; Venez nombreux vous amuser à nos jeux sans frontières qui se dérouleront en avril !</p>
            </a>
            <hr style="width:150px;margin-left:auto;margin-right:auto;opacity:.2">
            <a class="no_line" href="activites.php" target="">
                <p class="text-center">🦮&nbsp;&nbsp; Notre test de sociabilité 2025 est planifié<br><a href="activites.php">infos</a> et <a href="assets/documents/Fiche inscription Test soc 2025.pdf">inscription</a></p>
            </a>
        </aside>
        
        <br />
        <hr>
        <br />
        
        <article>
            <h4 class="text-center">Notre philosophie</h4>
            <br />
            <p class="text-justify">La méthode que nous utilisons est dite naturelle, pourquoi ?</p>
            <p class="text-justify">Naturelle car inspirée principalement sur l'observation des meutes de loups et de leurs comportements dans le groupe. C'est la louve qui contrôle les actes naturels de ses petits en utilisant un besoin qui leur est vital : la nourriture. Dans cette phase la louve émet des signaux et son petit réagit immédiatement.</p>
            <p class="text-justify">Si on transpose chez le chiot, il suffit d'avoir quelque chose de très motivant dans la main: friandise ou jouet, pour obtenir tout ce que l'on veut, sans s'énerver, en complicité avec lui. On montre une récompense, il revient à toute allure. On la lève, il s'assoit. On l'abaisse au sol, il se couche, etc.</p>
            <p class="text-justify">Cette méthode s'intègre ainsi dans la vision du chien que nous avons, ainsi que dans nos buts …</p>
            <ul>
                <li>Respecter l'animal : le guider par l'envie plutôt que de le contraindre</li>
                <li>S'adapter à son comportement, tenter de penser comme lui, essayer de le comprendre, anticiper, ... (nous pouvons nous adapter à lui, l'inverse est plus difficile !)</li>
                <li>Utiliser les instincts primaires comme moteur d'apprentissage. (la nourriture, le jeu, les codes canins, des signaux que le chien peut facilement comprendre, ...)</li>
            </ul>
            <p class="text-justify">Cette idée va nous amener à privilégier le comportement du chien plutôt qu'une obéissance aveugle. Ainsi, nous pourrions qualifier le chien au sens où nous l'entendons de "bien éduqué". Bien sûr son obéissance se doit d'être correcte également ! Mais elle sera instaurée pour être utile au quotidien et non pas pour les concours : pouvoir partir en vacances avec, pouvoir le laisser calme quelques heures au restaurant, faire des promenades agréables avec lui, qu'il soit sociable envers les autres chiens et les humains.</p>
            <p class="text-justify" style="margin-bottom:0">En quelques mots ...</p>
            <div style="text-align:center;margin:0;padding:0"><b>Le bon chien de famille</b> !</div>
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