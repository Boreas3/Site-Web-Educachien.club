// Configuration des galeries externes (Google Photos)
const externalGalleries = [
    // {title: 'Jeux sans frontières Challengers - 8 août 2023', src: "https://photos.app.goo.gl/VKRcwrsBcdEtRWmWA", date: '2023-08-08'},
    {title: 'Test de sociabilité - 30 juillet 2023', src: "https://photos.app.goo.gl/Ke4VNeAMGE2XS8M56", date: '2023-07-30'},
    {title: 'Anniversaire Aramis - 8 juillet 2023', src: "https://photos.app.goo.gl/HJfNjFiAT3jkQrEH8", date: '2023-07-08'},
    {title: 'Cours - 12 juillet 2023', src: "https://photos.app.goo.gl/ZAhsNDBN1fZ1yn5o9", date: '2023-07-12'},
    {title: 'Saint Nicolas - 2021', src: "https://photos.app.goo.gl/niZcTtNdaj8oqzC88", date: '2021-12-06'},
    {title: 'Ballade éducative - 11 juillet 2021', src: "https://photos.app.goo.gl/5cfBs2NmPVy86VGCA", date: '2021-07-11'},
    {title: 'Jeux & déguisements', src: "https://photos.app.goo.gl/dZtYQBeSJaRL5GnX8", date: '2021-05-20'},
    {title: 'Anniversaire surprise Jean - 70 ans', src: "https://photos.app.goo.gl/6V61RyHp6aY4xQo8A", date: '2021-03-15'}
];

// Fonction pour obtenir toutes les galeries triées par date
function getAllGalleriesSorted() {
  const galleries = [];
  
  // Ajouter les galeries locales
  for (const [id, config] of Object.entries(galleryConfigs)) {
    galleries.push({
      id: id,
      title: config.title,
      date: config.date,
      src: `${id}.php`,
      local: true
    });
  }
  
  // Ajouter les galeries externes
  externalGalleries.forEach(gallery => {
    galleries.push({
      id: gallery.src.split('/').pop(),
      title: gallery.title,
      date: gallery.date,
      src: gallery.src,
      local: false
    });
  });
  
  // Trier par date (plus récent en premier)
  return galleries.sort((a, b) => new Date(b.date) - new Date(a.date));
}

// Fonction pour créer les onglets Bootstrap
function createBootstrapTabs() {
  const galleriesByYear = getGalleriesByYear();
  const years = Object.keys(galleriesByYear).sort((a, b) => b - a); // Années décroissantes
  
  let tabsHTML = '<ul class="nav nav-tabs" id="yearTabs" role="tablist">';
  let contentHTML = '<div class="tab-content" id="yearTabContent">';
  
  years.forEach((year, index) => {
    const isActive = index === 0 ? 'active' : '';
    const galleries = galleriesByYear[year];
    
    // Onglet
    tabsHTML += `
      <li class="nav-item" role="presentation">
        <button class="nav-link ${isActive}" id="year-${year}-tab" data-bs-toggle="tab" data-bs-target="#year-${year}" type="button" role="tab" aria-controls="year-${year}" aria-selected="${index === 0 ? 'true' : 'false'}">
          ${year}
        </button>
      </li>
    `;
    
    // Contenu de l'onglet
    contentHTML += `
      <div class="tab-pane fade ${isActive === 'active' ? 'show active' : ''}" id="year-${year}" role="tabpanel" aria-labelledby="year-${year}-tab">
        <div class="mt-3">
    `;
    
    // Ajouter toutes les galeries de cette année (locales et externes)
    galleries.forEach(gallery => {
      const isExternal = !gallery.local;
      const targetAttr = isExternal ? 'target="_new"' : '';
      contentHTML += `
        <div class="gallery-item mb-2">
          <a href="${gallery.src}" ${targetAttr} class="text-decoration-none">${gallery.title}</a>
        </div>
      `;
    });
    
    contentHTML += `
        </div>
      </div>
    `;
  });
  
  tabsHTML += '</ul>';
  contentHTML += '</div>';
  
  return tabsHTML + contentHTML;
}

// Fonction pour créer les galeries
function createGalleries() {
  const galleriesList = document.getElementById('galleriesList');
  if (!galleriesList) return;
  
  // Remplacer le contenu par les onglets Bootstrap
  galleriesList.innerHTML = createBootstrapTabs();
}

// Fonction pour activer un onglet spécifique
function activateTabByYear(year) {
  const tabButton = document.getElementById(`year-${year}-tab`);
  const tabContent = document.getElementById(`year-${year}`);
  
  if (tabButton && tabContent) {
    // Désactiver tous les onglets
    document.querySelectorAll('.nav-link').forEach(link => {
      link.classList.remove('active');
      link.setAttribute('aria-selected', 'false');
    });
    document.querySelectorAll('.tab-pane').forEach(pane => {
      pane.classList.remove('show', 'active');
    });
    
    // Activer l'onglet spécifique
    tabButton.classList.add('active');
    tabButton.setAttribute('aria-selected', 'true');
    tabContent.classList.add('show', 'active');
  }
}

// Initialiser automatiquement
createGalleries();

// Détecter l'ancre dans l'URL et activer l'onglet correspondant
document.addEventListener('DOMContentLoaded', function() {
  const hash = window.location.hash;
  if (hash && hash.startsWith('#year-')) {
    const year = hash.replace('#year-', '');
    // Attendre que les onglets soient créés
    setTimeout(() => {
      activateTabByYear(year);
    }, 100);
  }
});