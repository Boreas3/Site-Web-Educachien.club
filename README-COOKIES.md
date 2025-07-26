# Système de Cookies d'Authentification - ÉducaChien Club

## Vue d'ensemble

Le système d'authentification a été amélioré pour permettre aux utilisateurs de rester connectés même après avoir fermé leur navigateur. Cette fonctionnalité utilise des cookies sécurisés en plus des sessions PHP traditionnelles.

## Fonctionnalités

### ✅ Connexion persistante
- Les utilisateurs peuvent choisir de rester connectés pendant 30 jours
- Option "Se souvenir de moi" dans le formulaire de connexion
- Reconnexion automatique lors de la prochaine visite

### 🔒 Sécurité renforcée
- Cookies signés avec HMAC-SHA256
- Protection contre la falsification
- Expiration automatique après 30 jours
- Cookies HttpOnly et Secure (HTTPS)

### 🎯 Compatibilité
- Fonctionne avec le système de sessions existant
- Déconnexion manuelle disponible
- Pas d'impact sur les utilisateurs existants

## Comment ça fonctionne

### 1. Connexion
Lorsqu'un utilisateur se connecte :
- Une session PHP est créée (comme avant)
- Si "Se souvenir de moi" est coché, un cookie sécurisé est créé
- Le cookie contient les informations d'identification chiffrées

### 2. Vérification de connexion
La fonction `isLoggedIn()` vérifie :
- D'abord la session PHP (priorité)
- Si pas de session, vérifie le cookie
- Si cookie valide, restaure la session automatiquement

### 3. Déconnexion
Lors de la déconnexion :
- La session PHP est détruite
- Le cookie d'authentification est supprimé

## Configuration

### Variables dans `includes/config.php`
```php
define('COOKIE_NAME', 'educachien_auth');           // Nom du cookie
define('COOKIE_SECRET', 'educachien_secret_key_2025'); // Clé de signature
define('COOKIE_DURATION', 30 * 24 * 60 * 60);     // 30 jours en secondes
```

### ⚠️ Important pour la production
- Changez `COOKIE_SECRET` pour une clé unique et sécurisée
- Utilisez HTTPS en production pour les cookies Secure

## Fonctions disponibles

### `createAuthCookie($user_id, $name, $role)`
Crée un cookie d'authentification sécurisé

### `verifyAuthCookie()`
Vérifie et récupère les données du cookie

### `deleteAuthCookie()`
Supprime le cookie d'authentification

### `isLoggedIn()` (modifiée)
Vérifie la connexion via session ou cookie

## Interface utilisateur

### Page de connexion (`login.php`)
- Case à cocher "Se souvenir de moi"
- Style personnalisé cohérent avec le design
- Support du mode sombre

### Espace membre (`member.php`)
- Lien de déconnexion existant
- Fonctionne avec le nouveau système

## Test du système

Un fichier de test `test-cookies.php` est disponible pour :
- Vérifier l'état de connexion
- Afficher les informations de débogage
- Tester la validité des cookies

## Sécurité

### Mesures de protection
1. **Signature HMAC** : Empêche la falsification
2. **Expiration** : Cookies automatiquement expirés
3. **HttpOnly** : Protection contre XSS
4. **Secure** : Transmission HTTPS uniquement
5. **Validation** : Vérification de l'intégrité

### Bonnes pratiques
- Les cookies ne contiennent que les données minimales nécessaires
- La clé de signature est séparée des données
- Gestion propre de la déconnexion

## Migration

Le système est rétrocompatible :
- Les utilisateurs existants ne sont pas affectés
- Les sessions PHP continuent de fonctionner
- Aucune modification de la base de données requise

## Support

Pour toute question ou problème :
1. Vérifiez le fichier `test-cookies.php`
2. Consultez les logs d'erreur PHP
3. Vérifiez la configuration des cookies dans le navigateur 