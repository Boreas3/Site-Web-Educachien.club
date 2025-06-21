# Installation du système de gestion des activités - Educachien

Ce guide vous explique comment installer et configurer le nouveau système de gestion des activités basé sur MySQL et PHP.

## Prérequis

- Serveur web local (XAMPP, MAMP, WAMP, ou serveur Apache + PHP)
- MySQL 5.7+ ou MariaDB 10.2+
- PHP 7.4+ avec extension PDO MySQL

## Installation

### 1. Configuration de la base de données

1. **Ouvrez votre gestionnaire MySQL** (phpMyAdmin, MySQL Workbench, ou ligne de commande)

2. **Exécutez le script SQL** :
   ```sql
   -- Ouvrez le fichier database.sql et exécutez son contenu
   -- Ou copiez-collez le contenu dans votre gestionnaire MySQL
   ```

3. **Vérifiez que la base de données est créée** :
   - Base de données : `educachien_db`
   - Table : `activites`

### 2. Configuration de la connexion

1. **Modifiez le fichier `config/database.php`** :
   ```php
   // Remplacez ces valeurs par vos paramètres locaux
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'educachien_db');
   define('DB_USER', 'root'); // Votre nom d'utilisateur MySQL
   define('DB_PASS', ''); // Votre mot de passe MySQL
   ```

2. **Testez la connexion** :
   - Décommentez la ligne `testDBConnection();` dans `config/database.php`
   - Accédez à `http://localhost/votre-projet/config/database.php`
   - Vous devriez voir "Connexion à la base de données réussie !"

### 3. Test de l'API

1. **Testez l'API directement** :
   - Accédez à `http://localhost/votre-projet/api/activites.php`
   - Vous devriez voir une réponse JSON avec les activités

2. **Testez avec des paramètres** :
   - `http://localhost/votre-projet/api/activites.php?limite=5`
   - `http://localhost/votre-projet/api/activites.php?type=balade`

### 4. Test de la page des activités

1. **Remplacez l'ancien fichier** :
   ```bash
   # Sauvegardez l'ancien fichier
   cp activites.html activites-backup.html
   
   # Remplacez par la nouvelle version
   cp activites-new.html activites.html
   ```

2. **Accédez à la page** :
   - `http://localhost/votre-projet/activites.html`
   - Les activités devraient se charger automatiquement depuis la base de données

## Structure des fichiers

```
www/
├── config/
│   └── database.php          # Configuration de la base de données
├── api/
│   └── activites.php         # API pour récupérer les activités
├── assets/scripts/
│   └── load-activites.js     # Script JavaScript pour charger les activités
├── activites.html            # Page des activités (modifiée)
├── activites-new.html        # Nouvelle version de la page
├── database.sql              # Script de création de la base de données
└── README-INSTALLATION.md    # Ce fichier
```

## Gestion des activités

### Ajouter une nouvelle activité

Via phpMyAdmin ou ligne de commande MySQL :

```sql
INSERT INTO activites (titre, description, date_debut, date_fin, lieu, paf, modalites, type_activite, public_cible) 
VALUES (
    'Nouvelle activité',
    'Description de l\'activité',
    '2025-01-15 14:00:00',
    '2025-01-15 16:00:00',
    'au club',
    '5€/personne',
    'Modalités de participation',
    'evenement',
    'tous'
);
```

### Modifier une activité

```sql
UPDATE activites 
SET titre = 'Nouveau titre', 
    description = 'Nouvelle description',
    date_debut = '2025-01-15 15:00:00'
WHERE id = 1;
```

### Désactiver une activité

```sql
UPDATE activites SET actif = FALSE WHERE id = 1;
```

## Types d'activités disponibles

- `balade` : Balades éducatives
- `formation` : Formations et initiations
- `evenement` : Événements spéciaux
- `travaux` : Travaux et maintenance
- `autre` : Autres types d'activités

## Publics cibles

- `tous` : Ouvert à tous
- `chiots` : Spécialement pour les chiots
- `adultes` : Pour les chiens adultes
- `membres` : Réservé aux membres du club

## Dépannage

### Erreur de connexion à la base de données

1. Vérifiez les paramètres dans `config/database.php`
2. Assurez-vous que MySQL est démarré
3. Vérifiez que l'utilisateur a les droits d'accès

### Erreur 500 sur l'API

1. Vérifiez les logs d'erreur PHP
2. Assurez-vous que l'extension PDO MySQL est activée
3. Vérifiez les permissions des fichiers

### Les activités ne se chargent pas

1. Ouvrez la console du navigateur (F12)
2. Vérifiez les erreurs JavaScript
3. Testez l'API directement dans le navigateur

### Problème de CORS

Si vous testez depuis un autre domaine :
1. Modifiez les headers CORS dans `api/activites.php`
2. Ou utilisez un serveur web local

## Sécurité

### En production

1. **Changez les identifiants de base de données** :
   ```php
   define('DB_USER', 'utilisateur_securise');
   define('DB_PASS', 'mot_de_passe_complexe');
   ```

2. **Créez un utilisateur MySQL dédié** :
   ```sql
   CREATE USER 'educachien_user'@'localhost' IDENTIFIED BY 'mot_de_passe_securise';
   GRANT SELECT ON educachien_db.activites TO 'educachien_user'@'localhost';
   FLUSH PRIVILEGES;
   ```

3. **Protégez les fichiers de configuration** :
   - Placez `config/` en dehors du répertoire web
   - Ou utilisez `.htaccess` pour bloquer l'accès direct

4. **Activez HTTPS** en production

## Support

Pour toute question ou problème :
1. Vérifiez ce guide de dépannage
2. Consultez les logs d'erreur
3. Testez chaque composant individuellement

## Migration depuis l'ancien système

L'ancien système avec les activités codées en dur dans le HTML est sauvegardé dans `activites-backup.html`. Vous pouvez revenir à l'ancien système en cas de problème en renommant les fichiers. 