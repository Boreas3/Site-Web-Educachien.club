// Système de composants pour header et footer

class ComponentLoader {

    constructor() {

        this.components = {};

    }



    // Générer le HTML du header directement avec Bootstrap

    generateHeaderHTML(selectedMenu = null) {

        const isActive = (menuId) => selectedMenu === menuId ? 'active' : '';

        

        return `

<header>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">

    <div class="container-fluid">

      <a class="navbar-brand" href="index.html" id="nav-item-home">

        <img src="assets/images/logo.png" class="nav-logo" alt="Logo" /> Educachien Engis-Fagnes

      </a>

      

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">

        <span class="navbar-toggler-icon"></span>

      </button>

      

      <div class="collapse navbar-collapse" id="navbarNavDropdown">

        <ul class="navbar-nav me-auto">

          <!-- Liens toujours visibles -->

          <li class="nav-item ${isActive('nav-item-about')}" id="nav-item-about">

            <a class="nav-link" href="club.html">Le club</a>

          </li>

          <li class="nav-item ${isActive('nav-item-cours')}" id="nav-item-cours">

            <a class="nav-link" href="cours.html">Cours</a>

          </li>

          <li class="nav-item ${isActive('nav-item-horaires')}" id="nav-item-horaires">

            <a class="nav-link" href="horaires.html">Horaires</a>

          </li>

          <li class="nav-item ${isActive('nav-item-activites')}" id="nav-item-activites">

            <a class="nav-link" href="activites.html">Activités</a>

          </li>

          

          <!-- Liens visibles en mobile et très grands écrans -->

          <li class="nav-item d-block d-lg-none d-xxl-block ${isActive('nav-item-documents')}" id="nav-item-documents">

            <a class="nav-link" href="documents.html">Documents</a>

          </li>

          <li class="nav-item d-block d-lg-none d-xxl-block ${isActive('nav-item-links')}" id="nav-item-links">

            <a class="nav-link" href="links.html">Liens</a>

          </li>

          <li class="nav-item d-block d-lg-none d-xxl-block ${isActive('nav-item-photos')}" id="nav-item-photos">

            <a class="nav-link" href="photos.html">Galeries photos</a>

          </li>



          <!-- Dropdown "Plus..." - apparaît seulement sur écrans moyens (pas en mobile) -->

          <li class="nav-item dropdown d-none d-lg-block d-xxl-none">

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

                <a class="dropdown-item" id="nav-item-photos" href="photos.html">Galeries photos</a>

              </li>

              <li><hr class="dropdown-divider"></li>

              <li>

                <a class="dropdown-item" href="https://www.facebook.com/groups/741063934986589/" target="_blank">

                  <img src="assets/images/fb_icon.png" class="fb" alt="Facebook" />

                </a>

              </li>

            </ul>

          </li>

        </ul>



        <ul class="navbar-nav">

          <li class="nav-item ${isActive('nav-item-contact')}" id="nav-item-contact">

            <a class="nav-link" href="contact.html">Contact</a>

          </li>

          <li class="nav-item d-block d-lg-none d-xxl-block" id="nav-item-facebook">

            <a class="nav-link" href="https://www.facebook.com/groups/741063934986589/" target="_blank">

              <img src="assets/images/fb_icon.png" class="fb" alt="Facebook" />

            </a>

          </li>

        </ul>

      </div>

    </div>

  </nav>

</header>`;

    }



    // Injecter le header immédiatement

    injectHeader(selectedMenu) {

        const headerContainer = document.getElementById('header-container');

        if (headerContainer) {

            headerContainer.innerHTML = this.generateHeaderHTML(selectedMenu);

            this.initNavigation();

        }

    }



    // Initialiser la navigation responsive (Bootstrap gère automatiquement le comportement)

    initNavigation() {

        // Bootstrap gère automatiquement le toggle mobile et les dropdowns

        // Pas besoin de code JavaScript supplémentaire

    }



    // Charger le footer depuis le fichier

    async loadFooter() {

        try {

            const response = await fetch('components/footer.html');

            if (response.ok) {

                const html = await response.text();

                const footerContainer = document.getElementById('footer-container');

                if (footerContainer) {

                    footerContainer.innerHTML = html;

                }

            }

        } catch (error) {

            console.error('Erreur lors du chargement du footer:', error);

        }

    }

}



// Instance globale

window.componentLoader = new ComponentLoader();



// Fonction principale pour initialiser une page

function initPage(selectedMenu) {

    // Injecter le header immédiatement

    componentLoader.injectHeader(selectedMenu);

    

    // Charger le footer en arrière-plan

    componentLoader.loadFooter();

} 