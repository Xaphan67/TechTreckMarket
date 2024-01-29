const ongletDescriptif = document.getElementById("ongletDescriptif");
const sectionDescriptif = document.getElementById("sectionDescriptif");
const ongletFiche = document.getElementById("ongletFiche");
const sectionFiche = document.getElementById("sectionFiche");
const ongletAvis = document.getElementById("ongletAvis");
const sectionAvis = document.getElementById("sectionAvis");
const classMenu = "onglet-actif";

// Affiche l'onglet "Descriptif"
function showDescription() {
    ongletDescriptif.classList.add(classMenu);
    ongletFiche.classList.remove(classMenu);
    ongletAvis.classList.remove(classMenu);
    sectionDescriptif.classList.remove("sub-conteneur-invisible")
    sectionDescriptif.classList.add("sub-conteneur-visible-section-produits")
    sectionFiche.classList.remove("sub-conteneur-visible-section-produits")
    sectionFiche.classList.add("sub-conteneur-invisible")
    sectionAvis.classList.remove("sub-conteneur-visible-section-produits")
    sectionAvis.classList.add("sub-conteneur-invisible")
}

// Affiche l'onglet "Fiche technique"
function showTechnical() {
    ongletDescriptif.classList.remove(classMenu);
    ongletFiche.classList.add(classMenu);
    ongletAvis.classList.remove(classMenu);
    sectionDescriptif.classList.remove("sub-conteneur-visible-section-produits")
    sectionDescriptif.classList.add("sub-conteneur-invisible")
    sectionFiche.classList.remove("sub-conteneur-invisible")
    sectionFiche.classList.add("sub-conteneur-visible-section-produits")
    sectionAvis.classList.remove("sub-conteneur-visible-section-produits")
    sectionAvis.classList.add("sub-conteneur-invisible")
}

// Action lorsqu'on clique sur "Avis clients"
function showComments() {
    ongletDescriptif.classList.remove(classMenu);
    ongletFiche.classList.remove(classMenu);
    ongletAvis.classList.add(classMenu);
    sectionDescriptif.classList.remove("sub-conteneur-visible-section-produits")
    sectionDescriptif.classList.add("sub-conteneur-invisible")
    sectionFiche.classList.remove("sub-conteneur-visible-section-produits")
    sectionFiche.classList.add("sub-conteneur-invisible")
    sectionAvis.classList.remove("sub-conteneur-invisible")
    sectionAvis.classList.add("sub-conteneur-visible-section-produits")
}