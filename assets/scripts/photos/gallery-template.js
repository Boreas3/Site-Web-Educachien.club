// Template pour les galeries photos locales
class PhotoGalleryTemplate {
  constructor(config) {
    this.config = config;
    this.init();
  }

  init() {
    // Initialiser la page avec le système de composants
    document.addEventListener('DOMContentLoaded', () => {
      // Injecter seulement le header (le footer est déjà intégré)
      componentLoader.injectHeader('nav-item-photos');
      
      // Initialiser la visionneuse photo
      this.initPhotoViewer();
    });
  }

  // Générer le HTML de la galerie
  generateGalleryHTML() {
    const { title, images, date } = this.config;
    
    // Extraire l'année de la date
    const year = date ? new Date(date).getFullYear() : null;
    const returnUrl = year ? `photos.php#year-${year}` : 'photos.php';
    
    let galleryHTML = `
      <div class="gallery-container">
        <a href="${returnUrl}" class="back-button">← Retour aux galeries</a>
        
        <h2 class="title text-center">${title}</h2>
        
        <div class="gallery-grid">
    `;

    images.forEach((image, index) => {
      galleryHTML += `
        <div class="gallery-item" data-index="${index}">
          <img src="${image.src}" alt="${image.alt}">
        </div>
      `;
    });

    galleryHTML += `
        </div>
      </div>

      <!-- Visionneuse photo modale -->
      <div class="photo-viewer" id="photoViewer">
        <div class="viewer-content">
          <button class="viewer-close" id="viewerClose">&times;</button>
          <img class="viewer-image" id="viewerImage" src="" alt="">
          <button class="viewer-nav viewer-prev" id="viewerPrev">&lt;</button>
          <button class="viewer-nav viewer-next" id="viewerNext">&gt;</button>
          <div class="viewer-counter" id="viewerCounter"></div>
        </div>
      </div>
    `;

    return galleryHTML;
  }

  // Visionneuse photo
  initPhotoViewer() {
    const viewer = document.getElementById('photoViewer');
    const viewerImage = document.getElementById('viewerImage');
    const viewerClose = document.getElementById('viewerClose');
    const viewerPrev = document.getElementById('viewerPrev');
    const viewerNext = document.getElementById('viewerNext');
    const viewerCounter = document.getElementById('viewerCounter');
    const galleryItems = document.querySelectorAll('.gallery-item');
    
    let currentIndex = 0;
    const images = this.config.images.map(img => img.src);

    // Ouvrir la visionneuse
    const openViewer = (index) => {
      currentIndex = index;
      viewerImage.src = images[currentIndex];
      updateCounter();
      viewer.classList.add('active');
      document.body.style.overflow = 'hidden';
    };

    // Fermer la visionneuse
    const closeViewer = () => {
      viewer.classList.remove('active');
      document.body.style.overflow = 'auto';
    };

    // Image précédente
    const prevImage = () => {
      currentIndex = (currentIndex - 1 + images.length) % images.length;
      viewerImage.src = images[currentIndex];
      updateCounter();
    };

    // Image suivante
    const nextImage = () => {
      currentIndex = (currentIndex + 1) % images.length;
      viewerImage.src = images[currentIndex];
      updateCounter();
    };

    // Mettre à jour le compteur
    const updateCounter = () => {
      viewerCounter.textContent = `${currentIndex + 1} / ${images.length}`;
    };

    // Événements de clic sur les images
    galleryItems.forEach((item, index) => {
      item.addEventListener('click', () => openViewer(index));
    });

    // Événements de la visionneuse
    viewerClose.addEventListener('click', closeViewer);
    viewerPrev.addEventListener('click', prevImage);
    viewerNext.addEventListener('click', nextImage);

    // Fermer avec la touche Échap
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && viewer.classList.contains('active')) {
        closeViewer();
      }
    });

    // Navigation avec les flèches
    document.addEventListener('keydown', (e) => {
      if (!viewer.classList.contains('active')) return;
      
      if (e.key === 'ArrowLeft') {
        prevImage();
      } else if (e.key === 'ArrowRight') {
        nextImage();
      }
    });

    // Fermer en cliquant sur l'arrière-plan
    viewer.addEventListener('click', (e) => {
      if (e.target === viewer) {
        closeViewer();
      }
    });

    // Support du swipe sur mobile
    let startX = 0;
    let startY = 0;
    let endX = 0;
    let endY = 0;

    // Détecter le début du swipe
    viewer.addEventListener('touchstart', (e) => {
      startX = e.touches[0].clientX;
      startY = e.touches[0].clientY;
    }, { passive: true });

    // Détecter la fin du swipe
    viewer.addEventListener('touchend', (e) => {
      endX = e.changedTouches[0].clientX;
      endY = e.changedTouches[0].clientY;
      
      const diffX = startX - endX;
      const diffY = startY - endY;
      
      // Seuil minimum pour considérer un swipe (50px)
      const minSwipeDistance = 50;
      
      // Vérifier si c'est un swipe horizontal (plus horizontal que vertical)
      if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > minSwipeDistance) {
        if (diffX > 0) {
          // Swipe vers la gauche = image suivante
          nextImage();
        } else {
          // Swipe vers la droite = image précédente
          prevImage();
        }
      }
    }, { passive: true });
  }
}

// Fonction utilitaire pour créer une galerie
function createPhotoGallery(config) {
  return new PhotoGalleryTemplate(config);
} 