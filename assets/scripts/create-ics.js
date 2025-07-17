function makeIcsFile(

  eventId,

  eventStart,

  eventEnd,

  eventTitle,

  eventDescription,

  eventLocation

) {

  let lf = "%0D%0A";

  // Échapper les caractères spéciaux pour l'ICS
  const escapeIcsText = (text) => {
    if (!text) return '';
    return text
      .replace(/\\/g, '\\\\')
      .replace(/;/g, '\\;')
      .replace(/,/g, '\\,')
      .replace(/\n/g, '\\n')
      .replace(/\r/g, '\\r');
  };

  let ics =

    "BEGIN:VCALENDAR" +

    lf +

    "CALSCALE:GREGORIAN" +

    lf +

    "METHOD:PUBLISH" +

    lf +

    "PRODID:-//Educachien Cal//FR" +

    lf +

    "VERSION:2.0" +

    lf +

    "BEGIN:VEVENT" +

    lf +

    "UID:" +

    eventId +

    lf +

    "DTSTART;TZID=Europe/Brussels:" +

    eventStart +

    lf +

    "DTSTAMP:" +

    eventStart +

    lf +

    "DTEND;TZID=Europe/Brussels:" + 

    eventEnd + 

    lf +

    "SUMMARY:" +

    escapeIcsText(eventTitle) +

    lf +

    "DESCRIPTION:" +

    escapeIcsText(eventDescription) +

    lf +

    "LOCATION:" +

    escapeIcsText(eventLocation) +

    lf +

    "TZID:Europe/Brussels" +

    lf +

    "END:VEVENT" +

    lf +

    "END:VCALENDAR";

  return ics;

}



function createFile(

  eventId,

  eventStart,

  eventEnd,

  eventTitle,

  eventDescription,

  eventLocation

) {

 let link = document.querySelector("#calendar_link_" + eventId);

  if (link) {
    const icsData = makeIcsFile(
      eventId,
      eventStart,
      eventEnd,
      eventTitle,
      eventDescription,
      eventLocation
    );
    
    // Créer un lien qui ouvre directement le calendrier par défaut
    link.href = "data:text/calendar;charset=utf-8," + encodeURIComponent(icsData);
    link.download = eventId + ".ics";
  }
}

// Fonction pour initialiser tous les liens d'agenda après le chargement des activités
function initializeCalendarLinks() {
  // Attendre que le DOM soit complètement chargé
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeCalendarLinks);
    return;
  }
  
  // Trouver tous les liens d'agenda
  const calendarLinks = document.querySelectorAll('a[href="#"].calendar');
  
  calendarLinks.forEach(link => {
    const eventId = link.id.replace('calendar_link_', '');
    
    // Trouver l'activité correspondante dans la liste globale
    const activity = window.activities ? window.activities.find(a => a.id === eventId) : null;
    
    if (activity) {
      const icsData = makeIcsFile(
        activity.id,
        activity.date.format('YYYYMMDDTHHmmss'),
        activity.endDate.format('YYYYMMDDTHHmmss'),
        activity.title,
        activity.description || '',
        activity.location || ''
      );
      
      // Ajouter un gestionnaire d'événements pour gérer le clic
      link.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Essayer d'abord d'ouvrir directement avec le protocole data:
        try {
          const dataUrl = "data:text/calendar;charset=utf-8," + encodeURIComponent(icsData);
          window.open(dataUrl, '_blank');
          
          // Afficher un message de confirmation
          showCalendarConfirmation(activity.title);
        } catch (error) {
          // Fallback : télécharger le fichier
          const blob = new Blob([icsData], { type: 'text/calendar' });
          const url = URL.createObjectURL(blob);
          
          const tempLink = document.createElement('a');
          tempLink.href = url;
          tempLink.download = activity.id + '.ics';
          tempLink.style.display = 'none';
          document.body.appendChild(tempLink);
          tempLink.click();
          
          // Nettoyer
          document.body.removeChild(tempLink);
          URL.revokeObjectURL(url);
          
          // Afficher un message de fallback
          showCalendarFallback(activity.title);
        }
      });
      
      // Garder le href pour la compatibilité
      link.href = "#";
    }
  });
}

// Fonction pour afficher une confirmation après l'ajout au calendrier
function showCalendarConfirmation(eventTitle) {
  // Créer une notification temporaire
  const notification = document.createElement('div');
  notification.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    background: #28a745;
    color: white;
    padding: 15px 20px;
    border-radius: 5px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    z-index: 10000;
    font-size: 14px;
    max-width: 300px;
    animation: slideIn 0.3s ease-out;
  `;
  
  notification.innerHTML = `
    <div style="display: flex; align-items: center;">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" style="margin-right: 10px;" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
        <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
      </svg>
      <div>
        <strong>Événement ouvert !</strong><br>
        <small>"${eventTitle}" s'ouvre dans votre application calendrier par défaut.</small>
      </div>
    </div>
  `;
  
  // Ajouter l'animation CSS
  const style = document.createElement('style');
  style.textContent = `
    @keyframes slideIn {
      from { transform: translateX(100%); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }
  `;
  document.head.appendChild(style);
  
  document.body.appendChild(notification);
  
  // Supprimer la notification après 4 secondes
  setTimeout(() => {
    if (notification.parentNode) {
      notification.style.animation = 'slideOut 0.3s ease-in';
      setTimeout(() => {
        if (notification.parentNode) {
          document.body.removeChild(notification);
        }
      }, 300);
    }
  }, 4000);
}

// Fonction pour afficher un message de fallback
function showCalendarFallback(eventTitle) {
  // Créer une notification temporaire
  const notification = document.createElement('div');
  notification.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    background: #ffc107;
    color: #212529;
    padding: 15px 20px;
    border-radius: 5px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    z-index: 10000;
    font-size: 14px;
    max-width: 300px;
    animation: slideIn 0.3s ease-out;
  `;
  
  notification.innerHTML = `
    <div style="display: flex; align-items: center;">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" style="margin-right: 10px;" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
        <path d="M8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
      </svg>
      <div>
        <strong>Fichier téléchargé</strong><br>
        <small>"${eventTitle}" a été téléchargé. Double-cliquez sur le fichier pour l'ouvrir dans votre calendrier.</small>
      </div>
    </div>
  `;
  
  // Ajouter l'animation CSS
  const style = document.createElement('style');
  style.textContent = `
    @keyframes slideIn {
      from { transform: translateX(100%); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }
  `;
  document.head.appendChild(style);
  
  document.body.appendChild(notification);
  
  // Supprimer la notification après 6 secondes
  setTimeout(() => {
    if (notification.parentNode) {
      notification.style.animation = 'slideOut 0.3s ease-in';
      setTimeout(() => {
        if (notification.parentNode) {
          document.body.removeChild(notification);
        }
      }, 300);
    }
  }, 6000);
}

// Exposer la fonction globalement pour qu'elle soit accessible depuis load-activites.js
window.initializeCalendarLinks = initializeCalendarLinks;