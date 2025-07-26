# Système de Login et Administration

## Vue d'ensemble

Le site ÉducaChien Club dispose maintenant d'un système de login permettant aux membres d'accéder à des documents réservés et aux administrateurs de gérer le contenu du site.

## Configuration initiale

### 1. Base de données
Avant d'utiliser le système, vous devez configurer la base de données :

1. Assurez-vous que MySQL est démarré
2. Vérifiez les paramètres de connexion dans `includes/config.php`
3. Exécutez le script de configuration : `setup-database.php`

### 2. Compte administrateur
Le compte admin principal a l'identifiant : **2305**

## Fonctionnalités

### Espace Membre
- **Accès aux documents réservés** : Fiches d'inscription, règlements, tests de sociabilité
- **Interface sécurisée** : Connexion requise pour accéder aux documents

### Espace Administration (Admin uniquement)
- **Gestion des activités** : Ajouter, modifier, supprimer des activités
- **Gestion des membres** : Ajouter de nouveaux membres avec leurs identifiants
- **Base de données** : Accès direct aux tables et données
- **Documents** : Gérer les documents accessibles aux membres

## Pages créées

### Pages publiques
- `login.php` - Page de connexion
- `logout.php` - Déconnexion

### Pages membres
- `member.php` - Espace membre avec accès aux documents

### Pages administration
- `admin-activities.php` - Gestion des activités
- `admin-members.php` - Gestion des membres
- `admin-database.php` - Accès à la base de données

### Configuration
- `includes/config.php` - Configuration de la base de données et utilisateurs
- `setup-database.php` - Script d'initialisation de la base de données

## Sécurité

- Sessions PHP sécurisées
- Validation des données d'entrée
- Protection contre les injections SQL
- Vérification des rôles utilisateur

## Ajout de nouveaux membres

1. Connectez-vous avec le compte admin (2305)
2. Allez dans "Gestion des Membres"
3. Ajoutez un nouveau membre avec :
   - Un identifiant unique (ex: 1234)
   - Nom du membre
   - Email
   - Rôle (membre ou admin)

## Base de données

### Tables créées
- `members` - Informations des membres
- `activities` - Activités du club
- `documents` - Documents accessibles aux membres

### Structure des tables
```sql
-- Table des membres
CREATE TABLE members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    role ENUM('member', 'admin') DEFAULT 'member',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des activités
CREATE TABLE activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des documents
CREATE TABLE documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    filename VARCHAR(255) NOT NULL,
    category VARCHAR(100),
    access_level ENUM('public', 'member', 'admin') DEFAULT 'member',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Navigation

Le header du site affiche maintenant :
- "Espace Membre" si l'utilisateur n'est pas connecté
- Le nom de l'utilisateur connecté avec un lien vers l'espace membre

## Développement futur

- Système de mots de passe
- Gestion des permissions plus fine
- Interface d'upload de documents
- Notifications par email
- Historique des connexions 