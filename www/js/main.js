/**
 * EDUCACHIEN CLUB - MAIN JAVASCRIPT FILE
 * ======================================
 * Core functionality for the Educachien website
 */

// Global variables
let selectedMenu = "nav-item-home";

/**
 * Initialize the website
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Educachien website initialized');
    
    // Set active navigation item
    setActiveNavigation();
    
    // Initialize any additional functionality
    initializeAdditionalComponents();
});

/**
 * Set the active navigation item based on current page
 */
function setActiveNavigation() {
    const element = document.getElementById(selectedMenu);
    if (element) {
        element.classList.add("active");
    }
}

/**
 * Initialize additional components (separate from components.js)
 */
function initializeAdditionalComponents() {
    // Add any additional initialization here
    console.log('Additional components initialized');
}

/**
 * Utility function to get current page name
 */
function getCurrentPage() {
    const path = window.location.pathname;
    const page = path.split('/').pop() || 'index.html';
    return page;
}

/**
 * Utility function to set page title
 */
function setPageTitle(title) {
    document.title = title + ' - Educachien Engis-Fagnes';
}

/**
 * Utility function to handle smooth scrolling
 */
function smoothScrollTo(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

/**
 * Utility function to handle mobile menu toggle
 */
function toggleMobileMenu() {
    const navbarCollapse = document.getElementById('navbarNavDropdown');
    if (navbarCollapse) {
        navbarCollapse.classList.toggle('show');
    }
}

/**
 * Handle window resize events
 */
window.addEventListener('resize', function() {
    // Add any resize-specific functionality here
    console.log('Window resized');
});

/**
 * Handle scroll events
 */
window.addEventListener('scroll', function() {
    // Add any scroll-specific functionality here
    // For example, navbar background changes, etc.
});

// Export functions for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        setActiveNavigation,
        initializeAdditionalComponents,
        getCurrentPage,
        setPageTitle,
        smoothScrollTo,
        toggleMobileMenu
    };
} 