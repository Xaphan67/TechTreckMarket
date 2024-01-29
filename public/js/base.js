// Masquer les messages après 4 secondes (3 secondes d'attente + 1 seconde d'animation)
setTimeout(function() {
    // Récupère le 1er élément ayant la classe message
    message = document.getElementsByClassName('message')[0]
    if (message != undefined) {
        message.style.display = 'none';
    }
}, 3975); // 3975 au lieu de 4000 pour faire disparaître l'élément juste avant la fin de l'animation

// Menu hamburger mobile
function interactMenu() {
    const menu = document.getElementById("headerNavigation");
    const styles = window.getComputedStyle(menu)
    if (styles.getPropertyValue("display") == "none") {
        menu.style.display = "block";
    } else {
        menu.style.display = "none";
    }
}

// ferme une modale
function closeModale(id)
{
    const modal = document.getElementById("modal")
    const element = document.getElementById(id)
    element.style.display = "none";
    modal.style.display = "none";

    // Cas particulière du formulaire de commande : reset les pages et formulaires
    if (id="formulaire-commande") {
        displayNextPage(0, 1);
        if (newFacturationOpen) {
            hideFormFacturation();
        };
        if (newLivraisonOpen) {
            hideFormLivraison();
        };
    }
}