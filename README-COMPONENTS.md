# Système de Composants - Educachien Engis-Fagnes

Ce système permet d'éviter la répétition du code header/footer dans chaque page HTML en utilisant des composants réutilisables.

## Fonctionnalités

- **Injection immédiate** : Le header est généré et injecté directement en JavaScript
- **Navigation responsive** : Menu hamburger pour mobile et dropdown pour desktop
- **Gestion des états actifs** : Le menu de la page courante est automatiquement marqué comme actif
- **Performance optimisée** : Aucun flash visuel grâce à l'injection directe

## Structure des fichiers

```
components/
├── header.html          # Navigation principale (obsolète - maintenant généré en JS)
└── footer.html          # Pied de page

assets/scripts/
└── components.js        # Système de chargement des composants
```

## Utilisation

### 1. Dans votre page HTML

```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Page</title>
    
    <!-- CSS de navigation -->
    <link rel="stylesheet" href="assets/css/navigation.css">
    
    <!-- CSS principal -->
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <!-- Container pour le header -->
    <div id="header-container"></div>
    
    <!-- Contenu de votre page -->
    <main>
        <h1>Mon contenu</h1>
        <p>Contenu de la page...</p>
    </main>
    
    <!-- Container pour le footer -->
    <div id="footer-container"></div>
    
    <!-- Scripts -->
    <script src="assets/scripts/components.js"></script>
    <script>
        // Initialiser la page avec le système de composants
        document.addEventListener('DOMContentLoaded', function() {
            initPage('nav-item-ma-page');
        });
    </script>
</body>
</html>
```

### 2. IDs des menus disponibles

- `nav-item-home` - Page d'accueil
- `nav-item-about` - Le club
- `nav-item-cours` - Cours
- `nav-item-horaires` - Horaires
- `nav-item-activites` - Activités
- `nav-item-documents` - Documents
- `nav-item-links` - Liens
- `nav-item-photos` - Galeries photos
- `nav-item-contact` - Contact

## Fonctions disponibles

### `initPage(selectedMenu)`
Fonction principale pour initialiser une page. Elle :
- Injecte immédiatement le header HTML généré en JavaScript
- Charge le footer en arrière-plan
- Initialise la navigation responsive
- Évite complètement les flashs visuels

### `componentLoader.injectHeader(selectedMenu)`
Injecte le header directement sans attente de chargement de fichiers.

### `componentLoader.generateHeaderHTML(selectedMenu)`
Génère le HTML du header avec le menu sélectionné marqué comme actif.

## Avantages

1. **Pas de répétition de code** : Header centralisé en JavaScript
2. **Maintenance facile** : Modifications dans un seul fichier
3. **Performance optimale** : Pas de flash visuel
4. **Navigation responsive** : Fonctionne sur tous les appareils
5. **États actifs automatiques** : Le menu de la page courante est marqué

## Exemple de page complète

Voir `template.html` pour un exemple complet d'utilisation du système. 