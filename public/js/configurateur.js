// Affiche le récapitulatif du configurateur
function showSummary() {
    const recapitulatif = document.getElementById("recapitulatif");
    const chevron = document.getElementById("chevron");
    const styles = window.getComputedStyle(recapitulatif)
    
    if (styles.getPropertyValue("display") == "none") {
        recapitulatif.classList.add("sub-conteneur-visible-filtres");
        chevron.classList.add("chevron-fermer");
        recapitulatif.classList.remove("formulaire-invisible");
    } else {
        recapitulatif.classList.remove("sub-conteneur-visible-filtres");
        chevron.classList.remove("chevron-fermer");
        recapitulatif.classList.add("formulaire-invisible");
    }
}

// Afficher le formulaire de sauvegarde de configuration
function displaySaveBuildForm()
{
    const modal = document.getElementById("modal")
    const element = document.getElementById("sauvegarder-configuration")
    const stylesModal = window.getComputedStyle(modal)
    if (stylesModal.getPropertyValue("display") == "none") {
        element.style.display = "block";
        modal.style.display = "flex";
    }
}

// Afficher la selection de configurations
function displaySavedBuildsSelection()
{
    const modal = document.getElementById("modal")
    const element = document.getElementById("charger-configuration")
    const stylesModal = window.getComputedStyle(modal)
    if (stylesModal.getPropertyValue("display") == "none") {
        element.style.display = "block";
        modal.style.display = "flex";
    }
}

// Afiche l'avertissement de réinitialisation
function displayResetWarning()
{
    const modal = document.getElementById("modal")
    const element = document.getElementById("reset-configuration")
    const stylesModal = window.getComputedStyle(modal)
    if (stylesModal.getPropertyValue("display") == "none") {
        element.style.display = "block";
        modal.style.display = "flex";
    }
}

// Afficher l'avertissement de panier non vide
function displayBarketWarning()
{
    const modal = document.getElementById("modal")
    const element = document.getElementById("panier-non-vide")
    const stylesModal = window.getComputedStyle(modal)
    if (stylesModal.getPropertyValue("display") == "none") {
        element.style.display = "block";
        modal.style.display = "flex";
    }
}

// Afiche l'avertissement de réinitialisation
function displayResetWarning()
{
    const modal = document.getElementById("modal")
    const element = document.getElementById("reset-configuration")
    const stylesModal = window.getComputedStyle(modal)
    if (stylesModal.getPropertyValue("display") == "none") {
        element.style.display = "block";
        modal.style.display = "flex";
    }
}