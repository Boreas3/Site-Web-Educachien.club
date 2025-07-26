<?php
// Initialiser la session et restaurer depuis le cookie si nécessaire
if (!function_exists('initSession')) {
    require_once __DIR__ . '/config.php';
}
initSession();

// Déterminer la page active pour la navigation
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$menu_items = [
    'index' => 'nav-item-home',
    'club' => 'nav-item-about',
    'cours' => 'nav-item-cours',
    'horaires' => 'nav-item-horaires',
    'activites' => 'nav-item-activites',
    'formations' => 'nav-item-formations',
    'documents' => 'nav-item-documents',
    'links' => 'nav-item-links',
    'photos' => 'nav-item-photos',
    /*'contact' => 'nav-item-contact'*/
];

$active_menu = $menu_items[$current_page] ?? '';
?>

<!-- CSS pour la gestion des cookies -->
<!-- <link rel="stylesheet" href="assets/scripts/cookie-manager.css"> -->

<!-- JavaScript pour la gestion des cookies -->
<!-- <script src="assets/scripts/cookie-manager.js"></script> -->

<!-- Script pour ajouter le lien politique de cookies dans le footer -->
<!-- <script src="assets/scripts/footer-cookies.js"></script> -->

<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/" id="nav-item-home">
                <img src="assets/images/logo.png" class="nav-logo" alt="Logo" /> Educachien Engis-Fagnes
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav me-auto">
                    <!-- Liens toujours visibles -->
                    <li class="nav-item <?php echo $active_menu === 'nav-item-about' ? 'active' : ''; ?>" id="nav-item-about">
                        <a class="nav-link" href="club.php">Le club</a>
                    </li>
                    <li class="nav-item <?php echo $active_menu === 'nav-item-cours' ? 'active' : ''; ?>" id="nav-item-cours">
                        <a class="nav-link" href="cours.php">Cours</a>
                    </li>
                    <li class="nav-item <?php echo $active_menu === 'nav-item-horaires' ? 'active' : ''; ?>" id="nav-item-horaires">
                        <a class="nav-link" href="horaires.php">Horaires</a>
                    </li>
                    <li class="nav-item <?php echo $active_menu === 'nav-item-activites' ? 'active' : ''; ?>" id="nav-item-activites">
                        <a class="nav-link" href="activites.php">Activités</a>
                    </li>
                    
                    <li class="nav-item <?php echo $active_menu === 'nav-item-formations' ? 'active' : ''; ?>" id="nav-item-formations">
                        <a class="nav-link" href="formations.php">Formations</a>
                    </li>
                    
                    <!-- Dropdown "Plus..." - apparaît seulement sur écrans moyens (pas en mobile) -->
                    <li class="nav-item dropdown d-none d-lg-block d-xxl-none">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Plus ...
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item <?php echo $active_menu === 'nav-item-documents' ? 'active' : ''; ?>" id="nav-item-documents" href="documents.php">Documents</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?php echo $active_menu === 'nav-item-links' ? 'active' : ''; ?>" id="nav-item-links" href="links.php">Liens</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?php echo $active_menu === 'nav-item-photos' ? 'active' : ''; ?>" id="nav-item-photos" href="photos.php">Galeries photos</a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="https://www.facebook.com/groups/741063934986589/" target="_blank">
                                    <img src="assets/images/fb_icon.png" class="fb" alt="Facebook" />
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Liens visibles en mobile et très grands écrans -->
                    <li class="nav-item d-block d-lg-none d-xxl-block <?php echo $active_menu === 'nav-item-documents' ? 'active' : ''; ?>" id="nav-item-documents">
                        <a class="nav-link" href="documents.php">Documents</a>
                    </li>
                    <li class="nav-item d-block d-lg-none d-xxl-block <?php echo $active_menu === 'nav-item-links' ? 'active' : ''; ?>" id="nav-item-links">
                        <a class="nav-link" href="links.php">Liens</a>
                    </li>
                    <li class="nav-item d-block d-lg-none d-xxl-block <?php echo $active_menu === 'nav-item-photos' ? 'active' : ''; ?>" id="nav-item-photos">
                        <a class="nav-link" href="photos.php">Photos</a>
                    </li>
                    <!-- <li class="nav-item d-block d-lg-none d-xxl-block <?php echo $active_menu === 'nav-item-contact' ? 'active' : ''; ?>" id="nav-item-contact">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li> -->
                </ul>
                
                <ul class="navbar-nav">
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="member.php">
                                <span style="color: yellow;">👤 <?php echo $_SESSION['name']; ?></span>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Espace Membre</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item <?php echo $active_menu === 'nav-item-contact' ? 'active' : ''; ?>" id="nav-item-contact">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item d-block d-lg-none d-xxl-block" id="nav-item-facebook">
                        <a class="nav-link" href="https://www.facebook.com/groups/741063934986589/" target="_blank">
                            <img src="assets/images/fb_icon.png" class="fb" alt="Facebook" />
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header> 