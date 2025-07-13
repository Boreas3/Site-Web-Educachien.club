# Proposition d'amélioration de l'architecture

## Problèmes actuels

### 1. Pages de galeries en dur
- Chaque galerie a sa propre page HTML
- Duplication massive de code
- Maintenance difficile

### 2. Organisation des assets
- Dossiers avec caractères spéciaux
- Scripts dispersés
- Configuration mélangée avec logique

## Solution proposée

### 1. Système de galeries dynamique
```
/photos/
  ├── index.html (liste des galeries)
  └── gallery/
      └── index.html (galerie dynamique)
```

### 2. Organisation des assets
```
/assets/
  ├── images/
  │   ├── galleries/
  │   │   ├── jeux-sans-frontieres-2023/
  │   │   ├── grand-nettoyage-2025/
  │   │   └── sortie-bus-24-juin-2025/
  │   ├── background/
  │   ├── icons/
  │   └── logo/
  ├── scripts/
  │   ├── components/
  │   ├── galleries/
  │   └── utils/
  └── styles/
      ├── components/
      └── pages/
```

### 3. Structure des scripts
```
/assets/scripts/
  ├── components/
  │   ├── header.js
  │   └── footer.js
  ├── galleries/
  │   ├── config.js (toutes les configurations)
  │   ├── viewer.js (visionneuse photo)
  │   └── manager.js (gestion des galeries)
  └── utils/
      ├── date-utils.js
      └── dom-utils.js
```

## Avantages

1. **Maintenabilité** : Un seul fichier pour toutes les galeries
2. **Performance** : Moins de fichiers à charger
3. **SEO** : URLs propres (/photos/gallery/jeux-sans-frontieres-2023)
4. **Évolutivité** : Facile d'ajouter de nouvelles galeries
5. **Organisation** : Structure claire et logique

## Migration

1. Créer la nouvelle structure
2. Migrer les galeries existantes
3. Mettre à jour les URLs
4. Supprimer les anciens fichiers
5. Mettre à jour les sitemaps

## URLs finales

- `/photos/` - Liste des galeries
- `/photos/gallery/jeux-sans-frontieres-2023` - Galerie spécifique
- `/photos/gallery/grand-nettoyage-2025` - Galerie spécifique 