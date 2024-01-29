// Affiche les filtres sur la liste des produits
function showFilters() {
    const filtresRecherche = document.getElementById("filtres-recherche");
    const chevron = document.getElementById("chevron");
    const styles = window.getComputedStyle(filtresRecherche)

    if (styles.getPropertyValue("display") == "none") {
        filtresRecherche.classList.add("sub-conteneur-visible-filtres");
        chevron.classList.add("chevron-fermer");
        filtresRecherche.classList.remove("formulaire-invisible");
    } else {
        filtresRecherche.classList.remove("sub-conteneur-visible-filtres");
        chevron.classList.remove("chevron-fermer");
        filtresRecherche.classList.add("formulaire-invisible");
    }
}