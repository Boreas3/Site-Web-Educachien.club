
const galleryBaseURL = "https://www.educachien.club/assets/scripts/photos/";

function createGalleryLink(gallery) {
    const div = document.createElement("div");
    div.className = "gallery-link";

    let anchor = document.createElement('a'); 
    anchor.appendChild(document.createTextNode(gallery.title));
    anchor.href = gallery.src;
    anchor.target = "_new";

    div.appendChild(anchor);
    document.getElementById("galleriesList").appendChild(div);
}

function createGalleries() {
    for (let i = 0; i < galleries.length; i++) {
        createGalleryLink(galleries[i]);
    }
}
