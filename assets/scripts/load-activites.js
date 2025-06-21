/**
 * Script pour charger les activités depuis l'API PHP
 * Remplace le contenu statique par des données dynamiques
 */

class ActivitesLoader {
    constructor() {
        this.apiUrl = 'api/activites.php';
        this.container = document.getElementById('activites-container');
        this.loadingElement = null;
        this.errorElement = null;
    }

    /**
     * Affiche un message de chargement
     */
    showLoading() {
        this.loadingElement = document.createElement('div');
        this.loadingElement.className = 'text-center p-4';
        this.loadingElement.innerHTML = `
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="mt-2">Chargement des activités...</p>
        `;
        this.container.appendChild(this.loadingElement);
    }

    /**
     * Cache le message de chargement
     */
    hideLoading() {
        if (this.loadingElement) {
            this.loadingElement.remove();
            this.loadingElement = null;
        }
    }

    /**
     * Affiche un message d'erreur
     */
    showError(message) {
        this.errorElement = document.createElement('div');
        this.errorElement.className = 'alert alert-danger m-3';
        this.errorElement.innerHTML = `
            <strong>Erreur :</strong> ${message}
            <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="activitesLoader.retry()">
                Réessayer
            </button>
        `;
        this.container.appendChild(this.errorElement);
    }

    /**
     * Cache le message d'erreur
     */
    hideError() {
        if (this.errorElement) {
            this.errorElement.remove();
            this.errorElement = null;
        }
    }

    /**
     * Réessaie de charger les activités
     */
    retry() {
        this.hideError();
        this.loadActivites();
    }

    /**
     * Charge les activités depuis l'API
     */
    async loadActivites() {
        try {
            this.showLoading();
            this.hideError();

            const response = await fetch(this.apiUrl);
            
            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status}`);
            }

            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.error || 'Erreur lors du chargement des données');
            }

            this.renderActivites(data.data);
            
        } catch (error) {
            console.error('Erreur lors du chargement des activités:', error);
            this.showError('Impossible de charger les activités. Vérifiez votre connexion à la base de données.');
        } finally {
            this.hideLoading();
        }
    }

    /**
     * Génère l'ID unique pour une activité
     */
    generateUniqueId(activite) {
        return activite.unique_id || `event_${activite.id}`;
    }

    /**
     * Formate le contenu HTML d'une activité
     */
    formatActiviteHTML(activite) {
        const uniqueId = this.generateUniqueId(activite);
        const collapseId = `collapse_${uniqueId}`;
        
        // Formatage des modalités avec des listes si nécessaire
        let modalitesHTML = activite.modalites || '';
        if (modalitesHTML.includes('•') || modalitesHTML.includes('-')) {
            modalitesHTML = modalitesHTML.replace(/(?:^|\n)([•\-]\s*)/g, '<li>$1');
            modalitesHTML = modalitesHTML.replace(/(\n|$)/g, '</li>$1');
            modalitesHTML = `<ul>${modalitesHTML}</ul>`;
        }

        return `
            <div id="div-event-${uniqueId}">
                <script>
                    var date = moment("${activite.date_debut_iso}");
                    var fromNow = date.fromNow();
                    var dateString = date.format("D MMMM YYYY");
                    var dateDay = date.format("dddd");
                </script>
                <div class="event-item">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="false" aria-controls="${collapseId}">
                        <table class="text-red">
                            <tr>
                                <td class="event-date-column">
                                    <span id="${uniqueId}_date"></span><br>
                                    <small id="${uniqueId}_delai"></small>
                                    <script>
                                        document.getElementById("${uniqueId}_date").innerHTML = dateString;
                                        document.getElementById("${uniqueId}_delai").innerHTML = "(" + dateDay + ", " + fromNow + ")";
                                    </script>
                                </td>
                                <td class="event-title-column">${activite.titre}</td>
                            </tr>
                        </table>
                    </button>
                    <div class="collapse" id="${collapseId}">
                        <div class="card card-body" style="border:0">
                            ${activite.description ? `<p>${activite.description}</p>` : ''}
                            ${activite.lieu ? `<span><b>Où :&nbsp;</b><br class="small-screen-only">${activite.lieu}</span><br>` : ''}
                            ${activite.paf ? `<span><b>PAF :&nbsp;</b><br class="small-screen-only">${activite.paf}</span><br>` : ''}
                            ${activite.modalites ? `<span><b>Modalités :&nbsp;</b>${modalitesHTML}</span><br>` : ''}
                            <a href="#" class="calendar" id="calendar_link_${uniqueId}_" rel="noopener" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-plus" viewBox="0 0 16 16">
                                    <path d="M8 7a.5.5 0 0 1 .5.5V9H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V10H6a.5.5 0 0 1 0-1h1.5V7.5A.5.5 0 0 1 8 7z"/>
                                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                </svg> &nbsp; Ajouter à mon agenda
                            </a>
                            <script>
                                createFile(
                                    "${uniqueId}_", 
                                    "${activite.date_debut_iso}", 
                                    "${activite.date_fin_iso}", 
                                    "${activite.titre.replace(/"/g, '\\"')}", 
                                    "${(activite.description || '').replace(/"/g, '\\"')}", 
                                    "Rue des Alunières 18, 4480 ENGIS"
                                );
                            </script>
                        </div>
                    </div>
                </div>
                <div style="height:8px"></div>
            </div>
        `;
    }

    /**
     * Affiche les activités dans le conteneur
     */
    renderActivites(activites) {
        if (!activites || activites.length === 0) {
            this.container.innerHTML = `
                <div class="text-center p-4">
                    <p>Aucune activité programmée pour le moment.</p>
                </div>
            `;
            return;
        }

        // Vider le conteneur
        this.container.innerHTML = '';

        // Ajouter chaque activité
        activites.forEach(activite => {
            const activiteHTML = this.formatActiviteHTML(activite);
            this.container.insertAdjacentHTML('beforeend', activiteHTML);
        });

        // Appliquer les filtres et le tri après le rendu
        this.applyFilters();
    }

    /**
     * Applique les filtres et le tri (fonction existante)
     */
    applyFilters() {
        // Cette fonction utilise le code existant de filtrage
        if (typeof filterEvents === 'function') {
            filterEvents();
        }
    }
}

// Initialisation du chargeur d'activités
let activitesLoader;

// Attendre que le DOM soit chargé
document.addEventListener('DOMContentLoaded', function() {
    // Créer le conteneur pour les activités s'il n'existe pas
    let container = document.getElementById('activites-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'activites-container';
        
        // Insérer le conteneur après le titre
        const title = document.querySelector('h2.title');
        if (title) {
            title.parentNode.insertBefore(container, title.nextSibling);
        }
    }

    // Initialiser le chargeur
    activitesLoader = new ActivitesLoader();
    
    // Charger les activités
    activitesLoader.loadActivites();
}); 