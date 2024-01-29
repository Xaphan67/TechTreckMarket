// Affiche l'onglet "Mes Informations"
function showInformations()
{
    const infos = document.getElementById("infos");
    const menuInfos = document.getElementById("menuInfos");
    const commandes = document.getElementById("commandes");
    const menuCommandes = document.getElementById("menuCommandes");
    const adresses = document.getElementById("adresses");
    const menuAdresses = document.getElementById("menuAdresses");
    const classMenu = "pc-actif";

    menuInfos.classList.add(classMenu)
    infos.classList.remove("sub-conteneur-invisible")
    infos.classList.add("sub-conteneur-visible")
    menuCommandes.classList.remove(classMenu)
    commandes.classList.remove("sub-conteneur-visible")
    commandes.classList.add("sub-conteneur-invisible")
    menuAdresses.classList.remove(classMenu)
    adresses.classList.remove("sub-conteneur-visible")
    adresses.classList.add("sub-conteneur-invisible")
}

// Affiche l'onglet "Mes commandes"
function showOrders()
{
    const infos = document.getElementById("infos");
    const menuInfos = document.getElementById("menuInfos");
    const commandes = document.getElementById("commandes");
    const menuCommandes = document.getElementById("menuCommandes");
    const adresses = document.getElementById("adresses");
    const menuAdresses = document.getElementById("menuAdresses");
    const classMenu = "pc-actif";

    menuInfos.classList.remove(classMenu)
    infos.classList.remove("sub-conteneur-visible")
    infos.classList.add("sub-conteneur-invisible")
    menuCommandes.classList.add(classMenu)
    commandes.classList.remove("sub-conteneur-invisible")
    commandes.classList.add("sub-conteneur-visible")
    menuAdresses.classList.remove(classMenu)
    adresses.classList.remove("sub-conteneur-visible")
    adresses.classList.add("sub-conteneur-invisible")
}

// Affiche l'onglet "Mes adresses"
function showAdresses()
{
    const infos = document.getElementById("infos");
    const menuInfos = document.getElementById("menuInfos");
    const commandes = document.getElementById("commandes");
    const menuCommandes = document.getElementById("menuCommandes");
    const adresses = document.getElementById("adresses");
    const menuAdresses = document.getElementById("menuAdresses");
    const classMenu = "pc-actif";

    menuInfos.classList.remove(classMenu)
    infos.classList.remove("sub-conteneur-visible")
    infos.classList.add("sub-conteneur-invisible")
    menuCommandes.classList.remove(classMenu)
    commandes.classList.remove("sub-conteneur-visible")
    commandes.classList.add("sub-conteneur-invisible")
    menuAdresses.classList.add(classMenu)
    adresses.classList.remove("sub-conteneur-invisible")
    adresses.classList.add("sub-conteneur-visible")
}
 
 // Afficher la confirmation de suppresion de compte
 function displayDeleteAccount()
 {
     const modal = document.getElementById("modal")
     const element = document.getElementById("suppression-compte-utilisateur")
     const stylesModal = window.getComputedStyle(modal)
     if (stylesModal.getPropertyValue("display") == "none") {
         element.style.display = "block";
         modal.style.display = "flex";
     }
 }

 // Affiche ou masque les détails d'une commande
 function toggleDetails(id)
 {
     const element = document.getElementById("details-commande-" + id)
     const chevron = document.getElementById("details-commande-chevron-mobile-" + id)
     const chevron2 = document.getElementById("details-commande-chevron-" + id)
     const styles = window.getComputedStyle(element)
     if (styles.getPropertyValue("display") == "none") {
         element.style.display = "block";
         chevron.classList.add("rotation-chevron")
         chevron2.classList.add("rotation-chevron")
     } else {
         element.style.display = "none";
         chevron.classList.remove("rotation-chevron")
         chevron2.classList.remove("rotation-chevron")
     }
 }

 // Affiche les formulaire d'ajout d'adresses
 function displayForms()
 {
     const modal = document.getElementById("modal")
     const element = document.getElementById("formulaires-adresses")
     const stylesModal = window.getComputedStyle(modal)
     if (stylesModal.getPropertyValue("display") == "none") {
         element.style.display = "block";
         modal.style.display = "flex";
     }
 }

 // Affiche le formulaire d'ajout d'adresse de facturation
 function displayFacturationForm()
 {
     const typeFacturation = document.getElementById("type-facturation")
     const typeLivraison = document.getElementById("type-livraison")
     const formulaireFacturation = document.getElementById("formulaire-facturation")
     const formulaireLivraison = document.getElementById("formulaire-livraison")

     typeFacturation.classList.add("type-facture-selected")
     typeLivraison.classList.remove("type-facture-selected")
     formulaireFacturation.classList.remove("formulaire-modal-invisible")
     formulaireLivraison.classList.add("formulaire-modal-invisible")
 }

 // Affiche le formulaire d'édidtion d'adresse de facturation
 function displayEditFacturationForm(id)
 {
     const modal = document.getElementById("modal")
     const element = document.getElementById("formulaire-edition-facturation-" + id)
     const stylesModal = window.getComputedStyle(modal)
     if (stylesModal.getPropertyValue("display") == "none") {
         element.style.display = "block";
         modal.style.display = "flex";
     }
 }

 // Affiche le formulaire d'ajout d'adresse de livraison
 function displayLivraisonForm()
 {
     const typeFacturation = document.getElementById("type-facturation")
     const typeLivraison = document.getElementById("type-livraison")
     const formulaireFacturation = document.getElementById("formulaire-facturation")
     const formulaireLivraison = document.getElementById("formulaire-livraison")

     typeFacturation.classList.remove("type-facture-selected")
     typeLivraison.classList.add("type-facture-selected")
     formulaireFacturation.classList.add("formulaire-modal-invisible")
     formulaireLivraison.classList.remove("formulaire-modal-invisible")
 }

 // Affiche le formulaire d'édidtion d'adresse de livraison
 function displayEditLivraisonForm(id)
 {
     const modal = document.getElementById("modal")
     const element = document.getElementById("formulaire-edition-livraison-" + id)
     const stylesModal = window.getComputedStyle(modal)
     if (stylesModal.getPropertyValue("display") == "none") {
         element.style.display = "block";
         modal.style.display = "flex";
     }
 }