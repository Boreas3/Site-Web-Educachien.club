<?php
session_start();
require_once 'includes/config.php';

// Initialiser la session et restaurer depuis le cookie si nécessaire
initSession();

// Si l'utilisateur est déjà connecté, le rediriger vers l'espace membre
if (isLoggedIn()) {
    header('Location: member.php');
    exit();
}

$error = '';

// Fonction pour vérifier si on peut créer le cookie d'authentification
function canCreateAuthCookie() {
    // Vérifier si l'utilisateur a donné son consentement pour les cookies
    if (isset($_COOKIE['cookieConsent'])) {
        $consent = json_decode($_COOKIE['cookieConsent'], true);
        if ($consent && isset($consent['preferences']) && $consent['preferences']) {
            return true;
        }
    }
    
    // Si pas de consentement explicite, ne pas créer le cookie
    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = sanitize($_POST['user_id']);
    $dog_name = sanitize($_POST['dog_name']);
    $remember_me = isset($_POST['remember_me']) && $_POST['remember_me'] === 'on';
    
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT user_id, name, dog_name, role FROM members WHERE user_id = ? AND dog_name = ?");
        $stmt->execute([$user_id, $dog_name]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Créer la session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            
            // Créer le cookie d'authentification seulement si "Se souvenir de moi" est coché ET si l'utilisateur a accepté les cookies
            if ($remember_me && canCreateAuthCookie()) {
                createAuthCookie($user['user_id'], $user['name'], $user['role']);
            }
            
            header('Location: member.php');
            exit();
        } else {
            $error = 'Identifiant ou nom du chien incorrect';
        }
    } catch (Exception $e) {
        $error = 'Erreur de connexion à la base de données';
    }
}
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
  padding-left: 15px !important;
  padding-right: 15px !important;
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
  max-width: 1200px !important;
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

/* Styles pour le formulaire de login */
.login-form {
    max-width: 400px;
    margin: 50px auto;
    padding: 30px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #333;
}

.form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
}

.form-group input:focus {
    outline: none;
    border-color: rgb(158, 0, 1);
    box-shadow: 0 0 0 2px rgba(158, 0, 1, 0.2);
}

.btn-login {
    background-color: rgb(158, 0, 1);
    color: yellow;
    border: none;
    padding: 12px 30px;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    width: 100%;
}

.btn-login:hover {
    background-color: #8b0001;
    color: yellow;
}

.alert-error {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
}

.login-info {
    margin-top: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 5px;
    text-align: center;
}

/* Styles pour la case à cocher personnalisée */
.checkbox-label {
    display: flex !important;
    align-items: center !important;
    cursor: pointer !important;
    font-size: 14px !important;
    color: #666 !important;
    margin-bottom: 0 !important;
    user-select: none !important;
}

.checkbox-label input[type="checkbox"] {
    display: none !important;
    opacity: 0 !important;
    position: absolute !important;
    z-index: -1 !important;
}

.checkmark {
    width: 20px !important;
    height: 20px !important;
    border: 2px solid #ddd !important;
    border-radius: 4px !important;
    margin-right: 12px !important;
    position: relative !important;
    background: #fff !important;
    transition: all 0.2s ease !important;
    flex-shrink: 0 !important;
    display: block !important;
}

.checkbox-label input[type="checkbox"]:checked + .checkmark {
    background: rgb(158, 0, 1) !important;
    border-color: rgb(158, 0, 1) !important;
}

.checkbox-label input[type="checkbox"]:checked + .checkmark:after {
    content: '✓' !important;
    position: absolute !important;
    left: 50% !important;
    top: 50% !important;
    transform: translate(-50%, -50%) !important;
    color: white !important;
    font-size: 14px !important;
    font-weight: bold !important;
    line-height: 1 !important;
}

.checkbox-label:hover .checkmark {
    border-color: rgb(158, 0, 1) !important;
    box-shadow: 0 0 0 2px rgba(158, 0, 1, 0.2) !important;
}

.checkbox-label:focus-within .checkmark {
    border-color: rgb(158, 0, 1) !important;
    box-shadow: 0 0 0 2px rgba(158, 0, 1, 0.2) !important;
}

/* Mode sombre */
@media (prefers-color-scheme: dark) {
    .login-form {
        background: #1a1a1a;
        color: #fff;
    }
    
    .form-group input {
        background: #2a2a2a;
        color: #fff;
        border-color: #444;
    }
    .form-group .field-title {
    color: yellow;
  }
  
    
    .login-info {
        background: #2a2a2a;
        color: #fff;
    }
    
    .checkbox-label {
        color: #ccc !important;
    }
    
    .checkmark {
        background: #2a2a2a !important;
        border-color: #444 !important;
    }
    
    .checkbox-label:hover .checkmark {
        border-color: rgb(158, 0, 1) !important;
        box-shadow: 0 0 0 2px rgba(158, 0, 1, 0.3) !important;
    }
}
</style>

    <!-- Montserrat font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" type="text/css" href="main.css?v=1.7.1" />

    <link rel="icon" href="assets/images/favicon.png" sizes="any" type="image/png" />
    <link rel="apple-touch-icon" href="assets/images/logo.png" />

    <title>Connexion - ÉducaChien Club</title>
    <meta name="description" content="Connexion à l'espace membre du Club Canin Educachien Engis-Fagnes" />
</head>

<body>
    <!-- Container pour le header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Contenu principal -->
    <section class="section container-fluid text-justify">
        <div class="login-form">
            <h2 class="text-center">Connexion Membre</h2>
            
            <?php if ($error): ?>
                <div class="alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="user_id" class="field-title">Numéro d'identifiant (4 chiffres) :</label>
                    <input type="text" id="user_id" name="user_id" required 
                           placeholder="numéro à 4 chiffres (ex: 0010)" 
                           pattern="[0-9]{4}" 
                           maxlength="4" 
                           title="Veuillez entrer un numéro à 4 chiffres">
                </div>
                
                <div class="form-group">
                    <label for="dog_name" class="field-title">Nom du chien :</label>
                    <input type="text" id="dog_name" name="dog_name" required 
                           placeholder="nom de votre chien">
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember_me" id="remember_me">
                        <span class="checkmark"></span>
                        Se souvenir de moi (rester connecté 30 jours)
                    </label>
                </div>
                
                <button type="submit" class="btn-login">Se connecter</button>
            </form>
            
            <div class="login-info">
                <p>Accédez à votre espace membre pour consulter les documents réservés et gérer les informations du club.</p>
            </div>
        </div>
    </section>

    <footer>
        <hr width="100%" />
        <div class="text-center">
            <small class="large-screen-only">© 2025 Educachien Engis-Fagnes ASBL. Tous droits réservés.</small>
            <small class="small-screen-only">© 2025 Educachien Engis-Fagnes ASBL.<br/>Tous droits réservés.</small>
        </div>
    </footer>

    <script src="assets/scripts/tables.js?v=<?php echo time(); ?>"></script>
</body>
</html> 