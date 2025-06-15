/**
 * EDUCACHIEN CLUB - COMPONENTS JAVASCRIPT FILE
 * ============================================
 * Handles reusable UI components
 */

/**
 * Load and inject the header component
 */
function loadHeader() {
    const headerElement = document.getElementById('header');
    if (headerElement) {
        headerElement.innerHTML = `
            <nav class="navbar fixed-top navbar-expand-lg">
                <div class="container-fluid">
                    <a class="navbar-brand" href="index.html" id="nav-item-home">
                        <img src="assets/images/logo.png" class="nav-logo" alt="Educachien Logo"> 
                        Educachien Engis-Fagnes
                    </a>
                    <button class="navbar-toggler navbar-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNavDropdown">
                        <ul class="navbar-nav">
                            <li class="nav-item" id="nav-item-about">
                                <a class="nav-link" href="club.html">Le club</a>
                            </li>
                            <li class="nav-item" id="nav-item-cours">
                                <a class="nav-link" href="cours.html">Cours</a>
                            </li>
                            <li class="nav-item" id="nav-item-horaires">
                                <a class="nav-link" href="horaires.html">Horaires</a>
                            </li>
                            <li class="nav-item" id="nav-item-activites">
                                <a class="nav-link" href="activites.html">Activités</a>
                            </li>
                            <li class="nav-item small-screen-only" id="nav-item-documents">
                                <a class="nav-link" href="documents.html">Documents</a>
                            </li>
                            <li class="nav-item small-screen-only" id="nav-item-links">
                                <a class="nav-link" href="links.html">Liens</a>
                            </li>
                            <li class="nav-item small-screen-only" id="nav-item-photos">
                                <a class="nav-link" href="photos.html">Galeries photos</a>
                            </li>
                            <li class="nav-item dropdown large-screen-only">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Plus ...
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" id="nav-item-documents" href="documents.html">Documents</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" id="nav-item-links" href="links.html">Liens</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" id="nav-item-photos" href="photos.html">Photos</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="https://www.facebook.com/groups/4900129550052576" target="_new">
                                            <img src="assets/images/fb_icon.png" class="fb" alt="Facebook">
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="navbar-nav">
                            <li class="nav-item" id="nav-item-contact">
                                <a class="nav-link nav-link-no-margin-right" href="contact.html">Contact</a>
                            </li>
                            <li class="nav-item small-screen-only" id="nav-item-facebook">
                                <a class="nav-link" href="https://www.facebook.com/groups/4900129550052576" target="_new">
                                    <img src="assets/images/fb_icon.png" class="fb" alt="Facebook">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        `;
    }
}

/**
 * Load and inject the footer component
 */
function loadFooter() {
    const footerElement = document.getElementById('footer');
    if (footerElement) {
        footerElement.innerHTML = `
            <hr width="100%">
            <div class="text-center">
                <small class="large-screen-only">© 2023 Educachien Engis-Fagnes ASBL. Tous droits réservés.</small>
                <small class="small-screen-only">© 2023 Educachien Engis-Fagnes ASBL.<br>Tous droits réservés.</small>
            </div>
        `;
    }
}

/**
 * Initialize all components
 */
function initializeComponents() {
    loadHeader();
    loadFooter();
    
    // Set active navigation based on current page
    setActiveNavigationForCurrentPage();
}

/**
 * Set active navigation based on current page
 */
function setActiveNavigationForCurrentPage() {
    const currentPage = getCurrentPage();
    let activeMenuId = "nav-item-home";
    
    // Map page names to navigation IDs
    const pageToMenuMap = {
        'index.html': 'nav-item-home',
        'club.html': 'nav-item-about',
        'cours.html': 'nav-item-cours',
        'horaires.html': 'nav-item-horaires',
        'activites.html': 'nav-item-activites',
        'documents.html': 'nav-item-documents',
        'links.html': 'nav-item-links',
        'photos.html': 'nav-item-photos',
        'contact.html': 'nav-item-contact'
    };
    
    if (pageToMenuMap[currentPage]) {
        activeMenuId = pageToMenuMap[currentPage];
    }
    
    // Remove active class from all nav items
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        item.classList.remove('active');
    });
    
    // Add active class to current page's nav item
    const activeElement = document.getElementById(activeMenuId);
    if (activeElement) {
        activeElement.classList.add('active');
    }
}

/**
 * Get current page name
 */
function getCurrentPage() {
    const path = window.location.pathname;
    const page = path.split('/').pop() || 'index.html';
    return page;
}

/**
 * Create a loading spinner component
 */
function createLoadingSpinner() {
    return `
        <div class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
        </div>
    `;
}

/**
 * Show loading spinner
 */
function showLoading(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.innerHTML = createLoadingSpinner();
    }
}

/**
 * Hide loading spinner
 */
function hideLoading(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.innerHTML = '';
    }
}

// Initialize components when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeComponents();
});

// Export functions for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        loadHeader,
        loadFooter,
        initializeComponents,
        setActiveNavigationForCurrentPage,
        createLoadingSpinner,
        showLoading,
        hideLoading
    };
} 