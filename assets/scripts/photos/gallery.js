
const galleryBaseURL = "https://www.educachien.club/assets/scripts/photos/";

function createGalleryLink(gallery) {
    const div = document.createElement("div");
    div.className = "gallery-link";

    let anchor = document.createElement('a'); 
    anchor.appendChild(document.createTextNode(gallery.title));
    
    // Gérer les galeries locales vs externes
    if (gallery.local) {
        anchor.href = gallery.src; // Page locale
    } else {
        anchor.href = gallery.src; // Lien externe
        anchor.target = "_new";
    }

    div.appendChild(anchor);
    document.getElementById("galleriesList").appendChild(div);
}

function createGalleries() {
    for (let i = 0; i < galleries.length; i++) {
        createGalleryLink(galleries[i]);
    }
}
