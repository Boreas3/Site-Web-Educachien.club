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

/* Styles pour les cartes de formations */
.formations-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 20px;
  margin-top: 30px;
}

.formation-card {
  margin-bottom: 20px;
}

.formation-card .card {
  height: 100%;
  border: 1px solid rgba(0, 0, 0, 0.125);
  transition: transform 0.2s ease-in-out;
  background-color: #fff;
}

.formation-card .card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.formation-card .card-title {
  color: rgb(158, 0, 1);
  font-weight: bold;
  margin-bottom: 15px;
  font-size: 18px;
}

.formation-card .list-unstyled li {
  margin-bottom: 8px;
  padding-left: 10px;
  font-size: 14px;
}

/* Styles pour le mode sombre */
@media (prefers-color-scheme: dark) {
  .formation-card .card {
    border: 1px solid rgba(255, 255, 255, 0.25);
    background-color: #1a1a1a;
  }
  
  .formation-card .card-title {
    color: yellow;
  }
  
  .formation-card .list-unstyled li {
    color: #fff;
  }
}

/* Responsive design */
@media (max-width: 768px) {
  .formations-grid {
    grid-template-columns: 1fr;
  }
}

/* Styles pour les catégories de formations */
.formation-category {
  margin-bottom: 40px;
}

.formation-category h3 {
  color: rgb(158, 0, 1);
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 20px;
  padding-bottom: 10px;
  border-bottom: 2px solid rgb(158, 0, 1);
}

@media (prefers-color-scheme: dark) {
  .formation-category h3 {
    color: yellow;
    border-bottom-color: yellow;
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

    <title>Educachien Engis-Fagnes - Formations en Éducation Canine</title>
    <meta name="description" content="Découvrez notre sélection de formations spécialisées en éducation canine dispensées par des professionnels reconnus." />
    <meta name="keywords" content="formations éducation canine, comportement chien, moniteurs formés, éducation positive" />
    <meta name="author" content="Educachien" />
    <meta name="copyright" content="© 2025 Educachien Engis-Fagnes ASBL" />
</head>

<body>
    <!-- Container pour le header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Image de fond -->
    <div class="landing-image">
        <img id="landingImage" src="assets/images/background/banner_nos_formations.png"/>
    </div>
    <br />
    <br />

    <!-- Contenu principal -->
    <section class="section container-fluid">
        <article>
            <div>
                <h2 class="title text-center">Formations des Moniteurs/Trices</h2>
                <br />
                <p class="text-center">
                    Découvrez les formations spécialisées en éducation canine que nos moniteurs/trices 
                    ont suivies auprès de professionnels reconnus dans le domaine.
                </p>
                <br />
                <hr style="width:200px;margin:auto">
                <br />
                <br />
            </div>
        </article>

        <article>
            <!-- ===== FORMATIONS FONDAMENTALES ===== -->
            <div class="formation-category">
                <h3>🎓 Formations Fondamentales</h3>
                <div class="formations-grid">
                    <!-- SRSH -->
                    <div class="formation-card">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">SRSH</h5>
                                <ul class="list-unstyled">
                                    <li>• Brevet d'éducateur canin</li>
                                    <li>• Formation en agility et sports canins</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Langage Canin Lupi Therapet -->
                    <div class="formation-card">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Lupi</h5>
                                <ul class="list-unstyled">
                                    <li>• Compréhension du langage canin et signaux corporels</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Philippe Grass -->
                    <div class="formation-card">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Philippe Grass</h5>
                                <ul class="list-unstyled">
                                    <li>• No méthode</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== COMPORTEMENT ET THÉRAPIE ===== -->
            <div class="formation-category">
                <h3>🧠 Comportement et Thérapie</h3>
                <div class="formations-grid">
                    <!-- Mélanie Joseph -->
                    <div class="formation-card">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Mélanie Joseph</h5>
                                <ul class="list-unstyled">
                                    <li>• Gestion des chiens réactifs</li>
                                    <li>• Techniques de désensibilisation</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Karine Faucher -->
                    <div class="formation-card">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Karine Faucher</h5>
                                <ul class="list-unstyled">
                                    <li>• Diagnostic et traitement des troubles comportementaux</li>
                                    <li>• Techniques de recherche et pistage (mantrailing)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Feeling dog -->
                    <div class="formation-card">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Feeling dog</h5>
                                <ul class="list-unstyled">
                                    <li>• Évaluation comportementale complète</li>
                                    <li>• Outils de diagnostic comportemental</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Nicolas Grevendinger -->
                    <div class="formation-card">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Nicolas Grevendinger</h5>
                                <ul class="list-unstyled">
                                    <li>• Réhabilitation des chiens de refuge</li>
                                    <li>• Techniques d'adaptation comportementale</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== ÉDUCATION ET APPRENTISSAGE ===== -->
            <div class="formation-category">
                <h3>📚 Éducation et Apprentissage</h3>
                <div class="formations-grid">
                    <!-- Mélanie Heuschen -->
                    <div class="formation-card">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Mélanie Heuschen</h5>
                                <ul class="list-unstyled">
                                    <li>• Guide d'acquisition et d'éducation du chiot</li>
                                    <li>• Détection précoce des troubles comportementaux</li>
                                    <li>• Orientation vers les spécialistes vétérinaires</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Bertrand Beuns -->
                    <div class="formation-card">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Bertrand Beuns</h5>
                                <ul class="list-unstyled">
                                    <li>• Techniques de marche en laisse</li>
                                    <li>• Apprentissages fondamentaux et positions</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Le monde d'Emi -->
                    <div class="formation-card">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Le monde d'Emi</h5>
                                <ul class="list-unstyled">
                                    <li>• Techniques d'ancrage du rappel</li>
                                    <li>• Méthodes de renforcement du rappel</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Novanimalia -->
                    <div class="formation-card">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Novanimalia</h5>
                                <ul class="list-unstyled">
                                    <li>• Méthodes d'apprentissage par le jeu</li>
                                    <li>• Techniques d'éducation positive ludique</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== GESTION ET BIEN-ÊTRE ===== -->
            <div class="formation-category">
                <h3>💆 Gestion et Bien-être</h3>
                <div class="formations-grid">
                    <!-- Elsa Weiss -->
                    <div class="formation-card">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Elsa Weiss</h5>
                                <ul class="list-unstyled">
                                    <li>• Techniques de gestion de groupe</li>
                                    <li>• Dynamique sociale en éducation canine</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Audrey Ventura -->
                    <div class="formation-card">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Audrey Ventura</h5>
                                <ul class="list-unstyled">
                                    <li>• Gestion émotionnelle du chien</li>
                                    <li>• Développement de l'auto-contrôle</li>
                                    <li>• Maîtrise des patrons moteurs</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Dog spirit by catimini -->
                    <div class="formation-card">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Dog spirit by catimini</h5>
                                <ul class="list-unstyled">
                                    <li>• Bienfaits des activités masticatoires</li>
                                    <li>• Techniques de massage canin</li>
                                    <li>• Soins et bien-être du chien</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== SPÉCIALISATIONS ===== -->
            <div class="formation-category">
                <h3>🏥 Spécialisations</h3>
                <div class="formations-grid">
                    <!-- Secourisme canin -->
                    <div class="formation-card">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Christine Dufour</h5>
                                <ul class="list-unstyled">
                                    <li>• Formation secourisme canin Bgbm</li>
                                    <li>• Bons Gestes Bons Moments - premiers soins canins</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Hoops dog -->
                    <div class="formation-card">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Hoops dog</h5>
                                <ul class="list-unstyled">
                                    <li>• Éducation des chiens sourds</li>
                                    <li>• Techniques de communication adaptées</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== FORMATIONS CONTINUES ===== -->
            <div class="formation-category">
                <h3>🔄 Formations Continues</h3>
                <div class="formations-grid">
                    <!-- Esprit dog -->
                    <div class="formation-card">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Esprit dog</h5>
                                <ul class="list-unstyled">
                                    <li>• Formation continue en éducation canine</li>
                                    <li>• Mise à jour des techniques pédagogiques</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Hooper -->
                    <div class="formation-card">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Belgian Nadac</h5>
                                <ul class="list-unstyled">
                                    <li>• Formation continue en Hooper - niveaux débutant à avancé</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>

        <!-- Informations supplémentaires -->
        <article class="mt-5">
            <div class="alert alert-info">
                <h5 class="alert-heading">ℹ️ Informations importantes</h5>
                <p class="mb-0">
                    Ces formations ont été suivies par nos moniteurs/trices auprès de professionnels externes. 
                    Elles témoignent de notre engagement à maintenir un haut niveau de compétence 
                    en éducation canine positive.
                </p>
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