# Système de Sécurité Renforcé - ÉducaChien Club

## Vue d'ensemble

Le système d'authentification a été renforcé pour améliorer la sécurité tout en gardant la simplicité d'utilisation. Au lieu d'un simple identifiant, les utilisateurs doivent maintenant fournir deux informations personnelles.

## 🔐 Nouveau système d'authentification

### Double authentification
1. **Identifiant de membre** (numéro unique)
2. **Nom du chien** (information personnelle)

### Avantages de sécurité
- ✅ **Double facteur** : Deux informations à connaître
- ✅ **Personnel** : Le nom du chien est difficile à deviner
- ✅ **Simple** : Pas de mots de passe complexes à retenir
- ✅ **Sécurisé** : Protection contre les attaques par force brute
- ✅ **Contexte** : Information pertinente pour un club canin

## 📋 Fonctionnement

### Connexion
1. L'utilisateur saisit son identifiant de membre
2. L'utilisateur saisit le nom de son chien
3. Le système vérifie que les deux correspondent dans la base de données
4. Si correct, l'utilisateur est connecté

### Base de données
```sql
SELECT user_id, name, dog_name, role 
FROM members 
WHERE user_id = ? AND dog_name = ?
```

## 🛡️ Mesures de sécurité

### Protection contre les attaques
- **Force brute** : Deux champs à deviner au lieu d'un
- **Dictionnaire** : Le nom du chien n'est pas dans les dictionnaires
- **Social engineering** : Information personnelle difficile à obtenir

### Validation des données
- **Sanitisation** : Toutes les entrées sont nettoyées
- **Préparation des requêtes** : Protection contre l'injection SQL
- **Messages d'erreur génériques** : Ne révèlent pas quelle information est incorrecte

## 📝 Interface utilisateur

### Formulaire de connexion
- Champ "Identifiant" (obligatoire)
- Champ "Nom du chien" (obligatoire)
- Case "Se souvenir de moi" (optionnel)
- Message d'erreur générique : "Identifiant ou nom du chien incorrect"

### Messages informatifs
- Explication du système de sécurité
- Indication que deux informations sont requises

## 🔧 Configuration

### Base de données
La table `members` doit contenir :
- `user_id` : Identifiant unique du membre
- `name` : Nom du membre
- `dog_name` : Nom du chien
- `role` : Rôle (member, admin, etc.)

### Ajout d'un membre
Via l'interface d'administration (`admin-members.php`) :
1. Identifiant de membre
2. Nom du membre
3. Nom du chien
4. Rôle

## 🧪 Tests

### Fichiers de test disponibles
- `test-login-secure.php` : Test du système de connexion
- `login.php` : Page de connexion avec nouveau système
- `admin-members.php` : Gestion des membres

### Test de connexion
1. Aller sur `login.php`
2. Saisir un identifiant valide
3. Saisir le nom du chien correspondant
4. Vérifier la connexion

## 📊 Avantages par rapport à l'ancien système

| Aspect | Ancien système | Nouveau système |
|--------|----------------|-----------------|
| **Sécurité** | Faible (1 facteur) | Élevée (2 facteurs) |
| **Simplicité** | Très simple | Simple |
| **Mémorisation** | Facile | Facile |
| **Résistance aux attaques** | Faible | Élevée |
| **Contexte** | Générique | Adapté au club |

## 🔄 Migration

### Pour les utilisateurs existants
- Aucune action requise côté utilisateur
- Les administrateurs doivent ajouter les noms des chiens dans la base de données
- Le système reste compatible avec les cookies existants

### Pour les nouveaux membres
- Saisir l'identifiant et le nom du chien lors de l'inscription
- Utiliser ces deux informations pour se connecter

## 🚨 Bonnes pratiques

### Pour les administrateurs
- Vérifier l'exactitude des noms de chiens
- Maintenir la confidentialité des informations
- Utiliser des identifiants uniques

### Pour les utilisateurs
- Ne pas partager les informations de connexion
- Utiliser des noms de chiens exacts
- Se déconnecter après utilisation

## 📞 Support

En cas de problème :
1. Vérifier l'exactitude de l'identifiant
2. Vérifier l'exactitude du nom du chien
3. Contacter l'administrateur si nécessaire
4. Utiliser le système de récupération si disponible 