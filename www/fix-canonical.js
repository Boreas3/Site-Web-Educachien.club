#!/usr/bin/env node

/**
 * EDUCACHIEN CLUB - FIX CANONICAL URLS
 * ====================================
 * Script pour corriger les URLs canoniques avec double .html
 */

const fs = require('fs');
const path = require('path');

// Pages à corriger
const PAGES = [
    'cours.html',
    'horaires.html',
    'activites.html',
    'documents.html',
    'links.html',
    'photos.html',
    'contact.html'
];

/**
 * Corrige les URLs canoniques dans un fichier
 */
function fixCanonicalUrl(filePath) {
    try {
        let content = fs.readFileSync(filePath, 'utf8');
        
        // Corriger le double .html dans les URLs canoniques
        content = content.replace(
            /href="https:\/\/www\.educachien\.club\/([^.]+)\.html\.html"/g,
            'href="https://www.educachien.club/$1.html"'
        );
        
        fs.writeFileSync(filePath, content, 'utf8');
        console.log(`✅ Canonical URL corrigée: ${filePath}`);
        
    } catch (error) {
        console.error(`❌ Erreur lors de la correction de ${filePath}:`, error.message);
    }
}

/**
 * Fonction principale
 */
function main() {
    console.log('🔧 Correction des URLs canoniques...\n');
    
    PAGES.forEach(page => {
        fixCanonicalUrl(page);
    });
    
    console.log('\n✅ Correction terminée !');
}

// Exécuter le script
if (require.main === module) {
    main();
}

module.exports = {
    fixCanonicalUrl,
    PAGES
}; 