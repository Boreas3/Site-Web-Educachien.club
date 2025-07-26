# Système de Gestion des Documents - ÉducaChien Club

## Vue d'ensemble

Le système de gestion des documents permet aux administrateurs d'uploader, organiser et gérer tous les documents du club de manière centralisée et sécurisée.

## 📁 Structure des documents

### Organisation par catégories
```
assets/documents/
├── 01-formulaires/          # Formulaires d'inscription
├── 02-tests-sociabilite/    # Tests de sociabilité
├── 03-plans-entrainement/   # Plans d'entraînement
├── 04-reglements/           # Règlements et consignes
├── 05-animations/           # Programmes d'animations
└── 06-divers/              # Autres documents
```

### Catégories disponibles
- **Formulaires** : Formulaires d'inscription, demandes
- **Tests de sociabilité** : Documentation des tests
- **Plans d'entraînement** : Exercices et méthodes
- **Règlements** : Consignes et règles du club
- **Animations** : Programmes d'activités
- **Divers** : Autres documents

## 🔐 Niveaux d'accès

### Public
- Accessible à tous les visiteurs
- Exemple : Formulaires d'inscription généraux

### Membres
- Accessible aux membres connectés
- Exemple : Plans d'entraînement, tests de sociabilité

### Administrateurs
- Accessible uniquement aux administrateurs
- Exemple : Documents internes, brouillons

### Archivé
- **Non visible** dans l'espace membre ni sur la page publique
- Conservé dans la base de données pour référence
- Accessible uniquement aux administrateurs via l'interface d'administration
- Exemple : Anciens formulaires, documents obsolètes

## 🚀 Fonctionnalités

### Upload de documents
- **Types acceptés** : PDF, DOC, DOCX, TXT, JPG, PNG
- **Taille maximale** : 10MB
- **Nommage automatique** : Timestamp + nom original
- **Organisation automatique** : Par catégorie

### Gestion des documents
- **Modification** : Titre, catégorie, niveau d'accès, description
- **Suppression** : Fichier physique + base de données
- **Visualisation** : Prévisualisation des documents
- **Recherche** : Par catégorie et niveau d'accès

### Interface d'administration
- **Formulaire d'upload** : Interface intuitive
- **Liste organisée** : Groupement par catégorie
- **Badges visuels** : Catégorie et niveau d'accès
- **Actions rapides** : Voir, modifier, supprimer

## 📊 Base de données

### Table `documents`
```sql
CREATE TABLE documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    filename VARCHAR(255) NOT NULL,
    filepath VARCHAR(500) NOT NULL,
    category VARCHAR(100) NOT NULL,
    access_level ENUM('public', 'member', 'admin', 'archived') DEFAULT 'member',
    description TEXT,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    file_size INT,
    file_type VARCHAR(50),
    active BOOLEAN DEFAULT TRUE
);
```

## 🔧 Installation

### 1. Créer la table
```bash
# Visiter setup-documents-table.php pour créer la table et importer les documents existants
```

### 2. Configurer les permissions
```bash
# S'assurer que le dossier assets/documents/ est accessible en écriture
chmod 755 assets/documents/
chmod 755 assets/documents/*/
```

### 3. Tester l'upload
```bash
# Aller sur admin-documents.php et tester l'upload d'un document
```

## 📝 Utilisation

### Pour les administrateurs

#### Upload d'un document
1. Aller sur `admin-documents.php`
2. Remplir le formulaire d'upload :
   - **Titre** : Nom descriptif du document
   - **Catégorie** : Sélectionner la catégorie appropriée
   - **Niveau d'accès** : Public, Membres ou Administrateurs
   - **Fichier** : Sélectionner le fichier à uploader
   - **Description** : Description optionnelle
3. Cliquer sur "Uploader le document"

#### Gestion des documents existants
- **Voir** : Ouvrir le document dans un nouvel onglet
- **Modifier** : Changer les métadonnées (titre, catégorie, etc.)
- **Supprimer** : Supprimer définitivement le document

### Pour les utilisateurs

#### Accès aux documents
- **Public** : Accessible depuis la page Documents
- **Membres** : Accessible depuis l'espace membre
- **Administrateurs** : Accessible depuis l'interface d'administration

## 🛡️ Sécurité

### Protection des fichiers
- **Types autorisés** : Vérification des extensions
- **Taille limitée** : Maximum 10MB par fichier
- **Nommage sécurisé** : Timestamp pour éviter les conflits
- **Sanitisation** : Nettoyage des noms de fichiers

### Gestion des accès
- **Authentification** : Vérification du rôle administrateur
- **Autorisations** : Contrôle des niveaux d'accès
- **Validation** : Vérification des données soumises

## 📋 Bonnes pratiques

### Organisation
- **Nommage** : Utiliser des noms descriptifs
- **Catégorisation** : Choisir la bonne catégorie
- **Description** : Ajouter une description claire
- **Niveau d'accès** : Définir le bon niveau

### Maintenance
- **Nettoyage** : Supprimer les documents obsolètes
- **Mise à jour** : Maintenir les informations à jour
- **Sauvegarde** : Sauvegarder régulièrement les documents

## 🔍 Dépannage

### Problèmes courants

#### Upload échoue
- Vérifier les permissions du dossier
- Vérifier la taille du fichier (max 10MB)
- Vérifier le type de fichier

#### Document non visible
- Vérifier le niveau d'accès
- Vérifier que l'utilisateur est connecté
- Vérifier que le fichier existe physiquement

#### Erreur de base de données
- Vérifier la connexion à la base de données
- Vérifier que la table `documents` existe
- Vérifier les permissions de la base de données

## 📞 Support

### Fichiers de test
- `setup-documents-table.php` : Configuration initiale
- `admin-documents.php` : Interface d'administration
- `test-documents.php` : Test du système

### Logs
- Vérifier les logs PHP pour les erreurs d'upload
- Vérifier les logs de la base de données
- Vérifier les permissions des fichiers

## 🔄 Migration

### Documents existants
Les documents existants ont été automatiquement :
- Organisés dans les bonnes catégories
- Importés dans la base de données
- Configurés avec les bons niveaux d'accès

### Compatibilité
- Le système est rétrocompatible
- Les anciens liens continuent de fonctionner
- Les nouveaux documents utilisent le nouveau système 