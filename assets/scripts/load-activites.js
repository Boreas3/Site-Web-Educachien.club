/**
 * Script pour charger les activités depuis le fichier CSV
 * Affiche les activités dans des onglets séparés (À venir / Passées)
 */

// Variable globale pour stocker les activités
let activities = [];
// Variable globale pour stocker les critères d'annulation
let cancellationRules = [];

// Fonction pour parser le CSV
function parseCSV(csv) {
    const lines = csv.split('\n');
    const headers = lines[0].split(',').map(h => h.trim().replace(/"/g, ''));
    const result = [];
    
    for (let i = 1; i < lines.length; i++) {
        if (!lines[i].trim()) continue;
        
        let values = [];
        let current = '';
        let inQuotes = false;
        
        for (let j = 0; j < lines[i].length; j++) {
            const char = lines[i][j];
            
            if (char === '"') {
                inQuotes = !inQuotes;
            } else if (char === ',' && !inQuotes) {
                values.push(current.trim());
                current = '';
            } else {
                current += char;
            }
        }
        values.push(current.trim());
        
        const obj = {};
        headers.forEach((header, index) => {
            obj[header.trim()] = values[index] || '';
        });
        result.push(obj);
    }
    
    return result;
}

// Fonction pour charger les critères d'annulation depuis le fichier CSV
async function loadCancellationRules() {
    try {
        const response = await fetch('assets/data/annulations.csv');
        
        if (!response.ok) {
            console.warn('Fichier annulations.csv non trouvé, aucune règle d\'annulation appliquée');
            return;
        }
        
        const csvText = await response.text();
        cancellationRules = parseCSV(csvText);
    } catch (error) {
        console.warn('Erreur lors du chargement des règles d\'annulation:', error);
    }
}

// Fonction pour vérifier si une activité doit être annulée
function checkCancellation(activity) {
    for (const rule of cancellationRules) {
        if (rule.critere_type === 'type' && activity.type === rule.critere_valeur) {
            // Vérifier les conditions de date si elles sont définies
            let shouldCancel = true;
            
            if (rule.date_debut && rule.date_fin) {
                const activityDate = activity.date; // La date est déjà au format YYYY-MM-DD
                const startDate = rule.date_debut;
                const endDate = rule.date_fin;
                
                // Vérifier si l'activité est dans la période d'annulation
                shouldCancel = activityDate >= startDate && activityDate <= endDate;
            }
            
            if (shouldCancel) {
                return {
                    cancelled: true,
                    message: rule.message || 'ANNULÉ'
                };
            }
        }
    }
    return { cancelled: false };
}

// Fonction pour charger les activités depuis le fichier CSV
async function loadActivities() {
    try {
        // Charger d'abord les règles d'annulation
        await loadCancellationRules();
        
        const response = await fetch('assets/data/activites.csv');
        
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        
        const csvText = await response.text();
        activities = parseCSV(csvText);
        
        // Convertir les activités en format compatible avec le système existant
        activities = activities.map(activity => {
            const date = moment(activity.date + ' ' + activity.heure_debut, 'YYYY-MM-DD HH:mm');
            const endDate = moment(activity.date + ' ' + activity.heure_fin, 'YYYY-MM-DD HH:mm');
            
            // Vérifier si l'activité doit être annulée (passer la date brute)
            const cancellation = checkCancellation({
                date: activity.date,
                type: activity.type
            });
            
            return {
                id: activity.date.replace(/-/g, '_') + '_' + activity.titre.toLowerCase().replace(/[^a-z0-9]/g, ''),
                date: date,
                endDate: endDate,
                title: activity.titre,
                description: activity.description,
                location: activity.lieu,
                price: activity.prix,
                conditions: activity.modalites,
                type: activity.type,
                cancelled: cancellation.cancelled,
                cancellationMessage: cancellation.message
            };
        });
        
        // Trier les activités par date (plus récentes en premier)
        activities.sort((a, b) => b.date - a.date);
        
        displayActivities();
    } catch (error) {
        console.error('Erreur lors du chargement des activités:', error);
        document.getElementById('activites-container').innerHTML = '<p class="text-center">Erreur lors du chargement des activités.</p>';
    }
}

// Fonction pour afficher les activités
function displayActivities() {
    const container = document.getElementById('activites-container');
    if (!container) {
        console.error('Container activites-container non trouvé');
        return;
    }
    
    const now = moment();
    
    // Séparer les activités futures et passées
    const upcomingActivities = activities.filter(activity => activity.date.isAfter(now));
    const pastActivities = activities.filter(activity => activity.date.isBefore(now));
    
    // Trier les activités futures par date (plus proches en premier)
    upcomingActivities.sort((a, b) => a.date - b.date);
    
    // Trier les activités passées par date (plus récentes en premier)
    pastActivities.sort((a, b) => b.date - a.date);
    
    let html = `
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs mb-3" id="activitiesTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab" aria-controls="upcoming" aria-selected="true">
                            À venir (${upcomingActivities.length})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="past-tab" data-bs-toggle="tab" data-bs-target="#past" type="button" role="tab" aria-controls="past" aria-selected="false">
                            Passées (${pastActivities.length})
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content" id="activitiesTabContent">
                    <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                        ${generateActivitiesHTML(upcomingActivities)}
                    </div>
                    <div class="tab-pane fade" id="past" role="tabpanel" aria-labelledby="past-tab">
                        ${generateActivitiesHTML(pastActivities)}
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.innerHTML = html;
}

// Fonction pour générer le HTML des activités
function generateActivitiesHTML(activitiesList) {
    if (activitiesList.length === 0) {
        return '<p class="text-center mt-3">Aucune activité à afficher.</p>';
    }
    
    return activitiesList.map(activity => {
        const dateString = activity.date.format('D MMMM YYYY');
        const dayName = activity.date.format('dddd');
        const fromNow = activity.date.fromNow();
        const timeString = activity.date.format('HH:mm');
        const endTimeString = activity.endDate.format('HH:mm');
        
        // Appliquer le style d'annulation si nécessaire
        const cancelledClass = activity.cancelled ? 'cancelled-activity' : '';
        const cancelledStyle = activity.cancelled ? 'text-decoration: line-through; opacity: 0.6;' : '';
        const cancelledBadge = activity.cancelled ? `<span class="badge bg-danger ms-2">${activity.cancellationMessage}</span>` : '';
        
        let descriptionHTML = '';
        if (activity.description) {
            descriptionHTML = `<p>${activity.description}</p>`;
        }
        
        let locationHTML = '';
        if (activity.location) {
            locationHTML = `<span><b>Où :&nbsp;</b><br class="small-screen-only">${activity.location}</span><br>`;
        }
        
        let priceHTML = '';
        if (activity.price) {
            priceHTML = `<span><b>PAF :&nbsp;</b><br class="small-screen-only">${activity.price}</span><br>`;
        }
        
        let conditionsHTML = '';
        if (activity.conditions) {
            conditionsHTML = `<span><b>Modalités :&nbsp;</b>${activity.conditions}</span><br>`;
        }
        
        return `
            <div class="event-item ${cancelledClass}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_${activity.id}" aria-expanded="false" aria-controls="collapse_${activity.id}">
                    <table class="text-red">
                        <tr>
                            <td class="event-date-column">
                                ${dateString}<br>
                                <small>(${dayName}, ${fromNow})</small>
                            </td>
                            <td class="event-title-column">
                                <span style="${cancelledStyle}">${activity.title}</span>${cancelledBadge}<br>
                                <small>${timeString} - ${endTimeString}</small>
                            </td>
                        </tr>
                    </table>
                </button>
                <div class="collapse" id="collapse_${activity.id}">
                    <div class="card card-body" style="border:0">
                        ${descriptionHTML}
                        <span><b>Quand :&nbsp;</b><br class="small-screen-only">de ${timeString} à ${endTimeString}</span><br>
                        ${locationHTML}
                        ${priceHTML}
                        ${conditionsHTML}
                        <a href="#" class="calendar" id="calendar_link_${activity.id}" rel="noopener" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-plus" viewBox="0 0 16 16">
                                <path d="M8 7a.5.5 0 0 1 .5.5V9H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V10H6a.5.5 0 0 1 0-1h1.5V7.5A.5.5 0 0 1 8 7z"/>
                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                            </svg> &nbsp; Ajouter à mon agenda
                        </a>
                        <script>
                            createFile(
                                "${activity.id}", 
                                "${activity.date.format('YYYYMMDDTHHmmss')}", 
                                "${activity.endDate.format('YYYYMMDDTHHmmss')}", 
                                "${activity.title}", 
                                "${activity.description}", 
                                "${activity.location}"
                            );
                        </script>
                    </div>
                </div>
            </div>
            <div style="height:8px"></div>
        `;
    }).join('');
}

// Charger les activités immédiatement ou quand le DOM est prêt
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        loadActivities();
    });
} else {
    loadActivities();
} 