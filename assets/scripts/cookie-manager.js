// Gestionnaire de cookies RGPD pour Educachien
class CookieManager {
    constructor() {
        this.cookiePreferences = {
            necessary: true, // Toujours activé
            analytics: false,
            marketing: false,
            preferences: false
        };
        
        this.init();
    }
    
    init() {
        console.log('Initializing CookieManager...');
        
        // Charger les préférences existantes
        this.loadPreferences();
        
        // Afficher la bannière si les préférences ne sont pas définies
        if (!this.hasUserConsent()) {
            console.log('Showing cookie banner...');
            this.showCookieBanner();
        } else {
            console.log('User has already consented, not showing banner');
        }
        
        // Appliquer les préférences
        this.applyPreferences();
    }
    
    // Vérifier si l'utilisateur a déjà donné son consentement
    hasUserConsent() {
        const hasConsent = localStorage.getItem('cookieConsent') !== null;
        console.log('Cookie consent check:', hasConsent);
        return hasConsent;
    }
    
    // Charger les préférences depuis le localStorage et les cookies
    loadPreferences() {
        // Essayer de charger depuis localStorage
        const saved = localStorage.getItem('cookieConsent');
        if (saved) {
            try {
                this.cookiePreferences = { ...this.cookiePreferences, ...JSON.parse(saved) };
            } catch (e) {
                console.error('Erreur lors du chargement des préférences cookies:', e);
            }
        }
        
        // Essayer de charger depuis le cookie PHP
        const cookies = document.cookie.split(';');
        for (let cookie of cookies) {
            const [name, value] = cookie.trim().split('=');
            if (name === 'cookieConsent' && value) {
                try {
                    const decodedValue = decodeURIComponent(value);
                    const cookieData = JSON.parse(decodedValue);
                    this.cookiePreferences = { ...this.cookiePreferences, ...cookieData };
                } catch (e) {
                    console.error('Erreur lors du chargement des préférences depuis le cookie:', e);
                }
                break;
            }
        }
    }
    
    // Sauvegarder les préférences
    savePreferences() {
        console.log('Saving cookie preferences:', this.cookiePreferences);
        
        // Sauvegarder dans localStorage pour le JavaScript
        localStorage.setItem('cookieConsent', JSON.stringify(this.cookiePreferences));
        
        // Sauvegarder dans un cookie PHP pour le serveur
        const cookieValue = JSON.stringify(this.cookiePreferences);
        const expires = new Date();
        expires.setFullYear(expires.getFullYear() + 1); // Expire dans 1 an
        
        document.cookie = `cookieConsent=${encodeURIComponent(cookieValue)}; expires=${expires.toUTCString()}; path=/; SameSite=Strict`;
        
        console.log('Preferences saved to localStorage and cookie');
        this.applyPreferences();
    }
    
    // Appliquer les préférences
    applyPreferences() {
        // Cookies nécessaires - toujours activés
        if (this.cookiePreferences.necessary) {
            this.enableNecessaryCookies();
        }
        
        // Cookies d'analyse
        if (this.cookiePreferences.analytics) {
            this.enableAnalyticsCookies();
        } else {
            this.disableAnalyticsCookies();
        }
        
        // Cookies marketing
        if (this.cookiePreferences.marketing) {
            this.enableMarketingCookies();
        } else {
            this.disableMarketingCookies();
        }
        
        // Cookies de préférences
        if (this.cookiePreferences.preferences) {
            this.enablePreferenceCookies();
        } else {
            this.disablePreferenceCookies();
        }
        
        // Log pour debug (à retirer en production)
        console.log('Préférences cookies appliquées:', this.cookiePreferences);
    }
    
    // Activer les cookies nécessaires
    enableNecessaryCookies() {
        // Session PHP, authentification, etc.
        // Ces cookies sont essentiels au fonctionnement du site
    }
    
    // Activer les cookies d'analyse
    enableAnalyticsCookies() {
        // Google Analytics, statistiques de visite, etc.
        if (typeof gtag !== 'undefined') {
            gtag('consent', 'update', {
                'analytics_storage': 'granted'
            });
        }
    }
    
    // Désactiver les cookies d'analyse
    disableAnalyticsCookies() {
        if (typeof gtag !== 'undefined') {
            gtag('consent', 'update', {
                'analytics_storage': 'denied'
            });
        }
    }
    
    // Activer les cookies marketing
    enableMarketingCookies() {
        // Publicités, tracking publicitaire, etc.
        if (typeof gtag !== 'undefined') {
            gtag('consent', 'update', {
                'ad_storage': 'granted'
            });
        }
    }
    
    // Désactiver les cookies marketing
    disableMarketingCookies() {
        if (typeof gtag !== 'undefined') {
            gtag('consent', 'update', {
                'ad_storage': 'denied'
            });
        }
    }
    
    // Activer les cookies de préférences
    enablePreferenceCookies() {
        // Préférences utilisateur, thème, langue, etc.
    }
    
    // Désactiver les cookies de préférences
    disablePreferenceCookies() {
        // Supprimer les cookies de préférences non essentiels
        this.deleteCookie('theme_preference');
        this.deleteCookie('font_size');
        this.deleteCookie('language_preference');
        this.deleteCookie('display_mode');
    }
    
    // Fonction utilitaire pour supprimer un cookie
    deleteCookie(name) {
        document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=' + window.location.hostname + ';';
    }
    
    // Supprimer tous les cookies tiers
    deleteThirdPartyCookies() {
        // Supprimer les cookies Google Analytics
        this.deleteCookie('_ga');
        this.deleteCookie('_gid');
        this.deleteCookie('_gat');
        this.deleteCookie('_ga_' + window.location.hostname.replace(/\./g, '_'));
        
        // Supprimer les cookies Facebook
        this.deleteCookie('_fbp');
        this.deleteCookie('_fbc');
        
        // Supprimer les cookies marketing
        this.deleteCookie('adwords');
        this.deleteCookie('facebook_pixel');
        
        // Supprimer les cookies de préférences
        this.deleteCookie('user_preferences');
        this.deleteCookie('cookie_consent');
        this.deleteCookie('cookieConsent');
        
        // Supprimer le cookie d'authentification PHP
        this.deleteCookie('educachien_auth');
        this.deleteCookie('PHPSESSID');
    }
    
    // Afficher la bannière de cookies
    showCookieBanner() {
        console.log('Creating cookie banner...');
        
        // Vérifier si la bannière existe déjà
        if (document.getElementById('cookie-banner')) {
            console.log('Banner already exists, not creating another one');
            return;
        }
        
        const banner = document.createElement('div');
        banner.id = 'cookie-banner';
        banner.innerHTML = `
            <div class="cookie-banner-content">
                <div class="cookie-banner-text">
                    <h4>🍪 Gestion des cookies</h4>
                    <p>Nous utilisons des cookies pour améliorer votre expérience sur notre site. 
                    Vous pouvez choisir quels types de cookies autoriser.</p>
                </div>
                <div class="cookie-banner-actions">
                    <button class="btn-cookie btn-cookie-accept-all" onclick="cookieManager.acceptAll()">
                        Accepter tout
                    </button>
                    <button class="btn-cookie btn-cookie-customize" onclick="cookieManager.showPreferences()">
                        Personnaliser
                    </button>
                    <button class="btn-cookie btn-cookie-reject-all" onclick="cookieManager.rejectAll()">
                        Refuser tout
                    </button>
                </div>
            </div>
        `;
        document.body.appendChild(banner);
        console.log('Cookie banner created and added to DOM');
    }
    
    // Afficher le modal de préférences
    showPreferences() {
        const modal = document.createElement('div');
        modal.id = 'cookie-preferences-modal';
        modal.innerHTML = `
            <div class="cookie-modal-overlay">
                <div class="cookie-modal-content">
                    <div class="cookie-modal-header">
                        <h3>🍪 Préférences de cookies</h3>
                        <button class="cookie-modal-close" onclick="cookieManager.closePreferences()">&times;</button>
                    </div>
                    <div class="cookie-modal-body">
                        <div class="cookie-category">
                            <div class="cookie-category-header">
                                <label class="cookie-category-toggle">
                                    <input type="checkbox" id="cookie-necessary" checked disabled>
                                    <span class="cookie-category-title">Cookies nécessaires</span>
                                </label>
                            </div>
                            <p class="cookie-category-description">
                                Ces cookies sont essentiels au fonctionnement du site (session, authentification).
                                Ils ne peuvent pas être désactivés.
                            </p>
                        </div>
                        
                        <div class="cookie-category">
                            <div class="cookie-category-header">
                                <label class="cookie-category-toggle">
                                    <input type="checkbox" id="cookie-analytics" ${this.cookiePreferences.analytics ? 'checked' : ''}>
                                    <span class="cookie-category-title">Cookies d'analyse</span>
                                </label>
                            </div>
                            <p class="cookie-category-description">
                                Ces cookies nous aident à comprendre comment vous utilisez notre site 
                                pour l'améliorer (Google Analytics, statistiques).
                            </p>
                        </div>
                        
                        <div class="cookie-category">
                            <div class="cookie-category-header">
                                <label class="cookie-category-toggle">
                                    <input type="checkbox" id="cookie-marketing" ${this.cookiePreferences.marketing ? 'checked' : ''}>
                                    <span class="cookie-category-title">Cookies marketing</span>
                                </label>
                            </div>
                            <p class="cookie-category-description">
                                Ces cookies sont utilisés pour vous proposer des contenus publicitaires 
                                pertinents et mesurer l'efficacité des campagnes.
                            </p>
                        </div>
                        
                        <div class="cookie-category">
                            <div class="cookie-category-header">
                                <label class="cookie-category-toggle">
                                    <input type="checkbox" id="cookie-preferences" ${this.cookiePreferences.preferences ? 'checked' : ''}>
                                    <span class="cookie-category-title">Cookies de préférences</span>
                                </label>
                            </div>
                            <p class="cookie-category-description">
                                Ces cookies mémorisent vos choix (langue, thème, paramètres d'affichage) 
                                pour améliorer votre expérience.
                            </p>
                        </div>
                    </div>
                    <div class="cookie-modal-footer">
                        <button class="btn-cookie btn-cookie-save" onclick="cookieManager.savePreferencesFromModal()">
                            Enregistrer mes choix
                        </button>
                        <button class="btn-cookie btn-cookie-cancel" onclick="cookieManager.closePreferences()">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        
        // Masquer la bannière pendant que le modal est ouvert
        const banner = document.getElementById('cookie-banner');
        if (banner) {
            banner.style.display = 'none';
        }
    }
    
    // Fermer le modal de préférences
    closePreferences() {
        const modal = document.getElementById('cookie-preferences-modal');
        if (modal) {
            modal.remove();
        }
        
        // Réafficher la bannière si elle existe
        const banner = document.getElementById('cookie-banner');
        if (banner) {
            banner.style.display = 'block';
        }
    }
    
    // Sauvegarder les préférences depuis le modal
    savePreferencesFromModal() {
        this.cookiePreferences.analytics = document.getElementById('cookie-analytics').checked;
        this.cookiePreferences.marketing = document.getElementById('cookie-marketing').checked;
        this.cookiePreferences.preferences = document.getElementById('cookie-preferences').checked;
        
        // Si l'utilisateur refuse tous les cookies optionnels, supprimer aussi les cookies d'auth
        if (!this.cookiePreferences.analytics && !this.cookiePreferences.marketing && !this.cookiePreferences.preferences) {
            this.deleteAuthCookies();
        }
        
        this.savePreferences();
        this.closePreferences();
        this.hideBanner();
    }
    
    // Accepter tous les cookies
    acceptAll() {
        this.cookiePreferences.analytics = true;
        this.cookiePreferences.marketing = true;
        this.cookiePreferences.preferences = true;
        this.savePreferences();
        this.hideBanner();
    }
    
    // Refuser tous les cookies (sauf nécessaires)
    rejectAll() {
        this.cookiePreferences.analytics = false;
        this.cookiePreferences.marketing = false;
        this.cookiePreferences.preferences = false;
        
        // Supprimer tous les cookies tiers existants
        this.deleteThirdPartyCookies();
        
        // Supprimer les cookies d'authentification si l'utilisateur refuse tout
        this.deleteAuthCookies();
        
        // Sauvegarder les préférences
        this.savePreferences();
        
        // Masquer la bannière
        this.hideBanner();
        
        // Afficher une confirmation
        this.showRejectionConfirmation();
    }
    
    // Supprimer les cookies d'authentification via AJAX
    deleteAuthCookies() {
        fetch('logout-cookies.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=delete_auth_cookies'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Cookies d\'authentification supprimés');
                // Rediriger vers la page d'accueil après suppression
                setTimeout(() => {
                    window.location.href = 'index.php';
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Erreur lors de la suppression des cookies:', error);
        });
    }
    
    // Vérifier si l'utilisateur est connecté
    checkAuthStatus() {
        return fetch('logout-cookies.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=check_auth_status'
        })
        .then(response => response.json())
        .then(data => {
            return data.logged_in || false;
        })
        .catch(error => {
            console.error('Erreur lors de la vérification du statut:', error);
            return false;
        });
    }
    
    // Afficher une confirmation de refus
    showRejectionConfirmation() {
        const confirmation = document.createElement('div');
        confirmation.id = 'cookie-rejection-confirmation';
        confirmation.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #dc3545;
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 10001;
            animation: slideInRight 0.3s ease-out;
            max-width: 400px;
        `;
        confirmation.innerHTML = `
            <div style="display: flex; align-items: flex-start; gap: 10px;">
                <span>⚠️</span>
                <div>
                    <div style="font-weight: bold; margin-bottom: 5px;">Tous les cookies refusés</div>
                    <div style="font-size: 0.9rem; opacity: 0.9;">
                        Vous serez déconnecté dans quelques secondes pour respecter vos préférences.
                    </div>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" 
                        style="background: none; border: none; color: white; font-size: 18px; cursor: pointer; margin-left: 10px; flex-shrink: 0;">
                    &times;
                </button>
            </div>
        `;
        document.body.appendChild(confirmation);
        
        // Supprimer automatiquement après 3 secondes
        setTimeout(() => {
            if (confirmation.parentElement) {
                confirmation.remove();
            }
        }, 3000);
    }
    
    // Masquer la bannière
    hideBanner() {
        const banner = document.getElementById('cookie-banner');
        if (banner) {
            banner.remove();
        }
    }
    
    // Obtenir les préférences actuelles
    getPreferences() {
        return { ...this.cookiePreferences };
    }
    
    // Vérifier si un type de cookie est autorisé
    isAllowed(cookieType) {
        return this.cookiePreferences[cookieType] || false;
    }
}

// Initialiser le gestionnaire de cookies
let cookieManager;
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing CookieManager...');
    
    // Éviter l'initialisation multiple
    if (window.cookieManagerInitialized) {
        console.log('CookieManager already initialized, skipping...');
        return;
    }
    
    cookieManager = new CookieManager();
    window.cookieManagerInitialized = true;
}); 