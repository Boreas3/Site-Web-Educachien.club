#!/usr/bin/env node

/**
 * EDUCACHIEN CLUB - PAGE MIGRATION SCRIPT
 * =======================================
 * Script pour migrer les pages existantes vers la nouvelle structure
 */

const fs = require('fs');
const path = require('path');

// Configuration
const TEMPLATE_PATH = './template.html';
const OLD_PAGES_DIR = '../www';
const NEW_PAGES_DIR = './';

// Mapping des pages et leurs configurations
const PAGE_CONFIGS = {
    'cours.html': {
        title: 'Cours',
        description: 'Educachien Engis-Fagnes - Cours',
        navItem: 'nav-item-cours',
        canonical: 'cours.html'
    },
    'horaires.html': {
        title: 'Horaires',
        description: 'Educachien Engis-Fagnes - Horaires',
        navItem: 'nav-item-horaires',
        canonical: 'horaires.html'
    },
    'activites.html': {
        title: 'Activités',
        description: 'Educachien Engis-Fagnes - Activités',
        navItem: 'nav-item-activites',
        canonical: 'activites.html'
    },
    'documents.html': {
        title: 'Documents',
        description: 'Educachien Engis-Fagnes - Documents',
        navItem: 'nav-item-documents',
        canonical: 'documents.html'
    },
    'links.html': {
        title: 'Liens',
        description: 'Educachien Engis-Fagnes - Liens',
        navItem: 'nav-item-links',
        canonical: 'links.html'
    },
    'photos.html': {
        title: 'Galeries photos',
        description: 'Educachien Engis-Fagnes - Galeries photos',
        navItem: 'nav-item-photos',
        canonical: 'photos.html'
    },
    'contact.html': {
        title: 'Contact',
        description: 'Educachien Engis-Fagnes - Contact',
        navItem: 'nav-item-contact',
        canonical: 'contact.html'
    }
};

/**
 * Lit le contenu d'un fichier
 */
function readFile(filePath) {
    try {
        return fs.readFileSync(filePath, 'utf8');
    } catch (error) {
        console.error(`Erreur lors de la lecture de ${filePath}:`, error.message);
        return null;
    }
}

/**
 * Écrit le contenu dans un fichier
 */
function writeFile(filePath, content) {
    try {
        fs.writeFileSync(filePath, content, 'utf8');
        console.log(`✅ Fichier créé: ${filePath}`);
    } catch (error) {
        console.error(`❌ Erreur lors de l'écriture de ${filePath}:`, error.message);
    }
}

/**
 * Extrait le contenu principal d'une page HTML existante
 */
function extractMainContent(htmlContent) {
    // Recherche du contenu entre les balises <section> ou <main>
    const sectionMatch = htmlContent.match(/<section[^>]*>([\s\S]*?)<\/section>/i);
    if (sectionMatch) {
        return sectionMatch[1];
    }
    
    // Fallback: recherche du contenu entre <body> et </body>
    const bodyMatch = htmlContent.match(/<body[^>]*>([\s\S]*?)<\/body>/i);
    if (bodyMatch) {
        // Supprimer la navigation et le footer
        let content = bodyMatch[1];
        content = content.replace(/<header[\s\S]*?<\/header>/gi, '');
        content = content.replace(/<footer[\s\S]*?<\/footer>/gi, '');
        content = content.replace(/<nav[\s\S]*?<\/nav>/gi, '');
        return content;
    }
    
    return null;
}

/**
 * Crée une nouvelle page à partir du template
 */
function createPageFromTemplate(pageName, config) {
    console.log(`\n🔄 Migration de ${pageName}...`);
    
    // Lire le template
    const templateContent = readFile(TEMPLATE_PATH);
    if (!templateContent) {
        console.error(`❌ Impossible de lire le template ${TEMPLATE_PATH}`);
        return;
    }
    
    // Lire l'ancienne page
    const oldPagePath = path.join(OLD_PAGES_DIR, pageName);
    const oldPageContent = readFile(oldPagePath);
    if (!oldPageContent) {
        console.error(`❌ Impossible de lire l'ancienne page ${oldPagePath}`);
        return;
    }
    
    // Extraire le contenu principal
    const mainContent = extractMainContent(oldPageContent);
    if (!mainContent) {
        console.error(`❌ Impossible d'extraire le contenu principal de ${pageName}`);
        return;
    }
    
    // Remplacer les placeholders dans le template
    let newContent = templateContent
        .replace(/PAGE_TITLE/g, config.title)
        .replace(/PAGE_DESCRIPTION/g, config.description)
        .replace(/PAGE_NAME/g, config.canonical)
        .replace(/NAV_ITEM_ID/g, config.navItem)
        .replace(/PAGE_HEADING/g, config.title)
        .replace(/<article class="content-section">[\s\S]*?<\/article>/i, 
                `<article class="content-section">\n                ${mainContent.trim()}\n            </article>`);
    
    // Écrire la nouvelle page
    const newPagePath = path.join(NEW_PAGES_DIR, pageName);
    writeFile(newPagePath, newContent);
}

/**
 * Fonction principale
 */
function main() {
    console.log('🚀 Début de la migration des pages...\n');
    
    // Vérifier que le template existe
    if (!fs.existsSync(TEMPLATE_PATH)) {
        console.error(`❌ Le template ${TEMPLATE_PATH} n'existe pas`);
        process.exit(1);
    }
    
    // Migrer chaque page
    for (const [pageName, config] of Object.entries(PAGE_CONFIGS)) {
        createPageFromTemplate(pageName, config);
    }
    
    console.log('\n✅ Migration terminée !');
    console.log('\n📝 Prochaines étapes:');
    console.log('1. Vérifier que toutes les pages ont été créées correctement');
    console.log('2. Tester les pages dans un navigateur');
    console.log('3. Ajuster le contenu si nécessaire');
    console.log('4. Supprimer les anciens fichiers une fois satisfait');
}

// Exécuter le script
if (require.main === module) {
    main();
}

module.exports = {
    createPageFromTemplate,
    extractMainContent,
    PAGE_CONFIGS
}; 