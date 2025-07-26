// Script pour ajouter le lien vers la politique de cookies dans le footer
document.addEventListener('DOMContentLoaded', function() {
    // Chercher le footer existant
    const footer = document.querySelector('footer');
    if (footer) {
        // Chercher la div de copyright existante
        const copyrightDiv = footer.querySelector('.text-center');
        if (copyrightDiv) {
            // Créer le lien vers la politique de cookies
            const cookieLink = document.createElement('div');
            cookieLink.style.marginTop = '10px';
            cookieLink.style.fontSize = '0.9rem';
            cookieLink.innerHTML = `
                <a href="politique-cookies.php" style="color: #666; text-decoration: none;">
                    🍪 Politique de cookies
                </a>
            `;
            
            // Ajouter le lien après le copyright
            copyrightDiv.appendChild(cookieLink);
        }
    }
}); 