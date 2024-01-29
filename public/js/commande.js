// Affiche le formulaire de validation de la commande
function displayValidateForm() {
    const modal = document.getElementById("modal")
    const element = document.getElementById("formulaire-commande")
    const stylesModal = window.getComputedStyle(modal)
    const page2 = document.getElementById("commander-page-2")
    const page3 = document.getElementById("commander-page-3")
    const page4 = document.getElementById("commander-page-4")
    if (stylesModal.getPropertyValue("display") == "none") {
        element.style.display = "block";
        modal.style.display = "flex";
        page2.classList.add("formulaire-modal-invisible");
        page3.classList.add("formulaire-modal-invisible");
        page4.classList.add("formulaire-modal-invisible");
    }
}

// Affiche une page spécifique du formulaire de validation de la commande
function displayNextPage(idCurrent, idNext) {
    const page1 = document.getElementById("commander-page-1")
    const page2 = document.getElementById("commander-page-2")
    const page3 = document.getElementById("commander-page-3")
    const page4 = document.getElementById("commander-page-4")
    const nextPage = document.getElementById("commander-page-" + idNext);

    const adresseFacturation = document.getElementById("commande_adresseFacturation");
    const numeroFacturation = document.getElementById("commande_numeroFacturation");
    const typeRueFacturation = document.getElementById("commande_typeRueFacturation");
    const rueFacturation = document.getElementById("commande_rueFacturation");
    const codePostalFacturation = document.getElementById("commande_codePostalFacturation");
    const villeFacturation = document.getElementById("commande_villeFacturation");
    const enregistrerFacturation = document.getElementById("commande_enregistrerFacturation");

    const adresseLivraison  = document.getElementById("commande_adresseLivraison");
    const numeroLivraison = document.getElementById("commande_numeroLivraison");
    const typeRueLivraison = document.getElementById("commande_typeRueLivraison");
    const rueLivraison = document.getElementById("commande_rueLivraison");
    const codePostalLivraison = document.getElementById("commande_codePostalLivraison");
    const villeLivraison = document.getElementById("commande_villeLivraison");
    const enregistrerLivraison = document.getElementById("commande_enregistrerLivraison");

    let newFacturationOpen = false;
    let newLivraisonOpen = false;

    if (idCurrent === 0) {
        page1.classList.remove("formulaire-modal-invisible");
        page2.classList.remove("formulaire-modal-invisible");
        page3.classList.remove("formulaire-modal-invisible");
        page4.classList.remove("formulaire-modal-invisible");
    } else if (idCurrent === 1) {
        const civilite0 = document.getElementById("commande_civilite_0");
        const civilite1 = document.getElementById("commande_civilite_1");
        const nom = document.getElementById("commande_nom");
        const prenom = document.getElementById("commande_prenom");
        if ((civilite0.checked || civilite1.checked) && nom.validity.valid && prenom.validity.valid) {
            page1.classList.add("formulaire-modal-invisible");
            nextPage.classList.remove("formulaire-modal-invisible");
            if (idNext > idCurrent) {
                document.getElementById("commande-resume-civilite").textContent = `${civilite0.checked ? civilite0.value.charAt(0).toUpperCase() + civilite0.value.slice(1) : civilite1.value.charAt(0).toUpperCase() + civilite1.value.slice(1)}`;
                document.getElementById("commande-resume-prenom").textContent = `${prenom.value}`;
                document.getElementById("commande-resume-nom").textContent = `${nom.value}`;
            }
        }
    } else if (idCurrent === 2) {
        if (idNext > idCurrent) {
            if ((numeroFacturation.value.length == 0 && typeRueFacturation.value.length == 0
            && rueFacturation.value.length == 0 && codePostalFacturation.value.length == 0
            && villeFacturation.value.length == 0 && !enregistrerFacturation.checked && !newFacturationOpen) ||
            (numeroFacturation.value.length != 0 && typeRueFacturation.value.length != 0
            && rueFacturation.value.length != 0 && codePostalFacturation.value.length != 0
            && villeFacturation.value.length != 0)) {
                if (numeroFacturation.validity.valid && typeRueFacturation.validity.valid && rueFacturation.validity.valid && codePostalFacturation.validity.valid && villeFacturation.validity.valid && enregistrerFacturation.validity.valid) {
                    page2.classList.add("formulaire-modal-invisible");
                    nextPage.classList.remove("formulaire-modal-invisible");
                    if (!newFacturationOpen) {
                        document.getElementById("commande-resume-adresseFacturation-choix").classList.remove("formulaire-modal-invisible");
                        document.getElementById("commande-resume-adresseFacturation-nouveau").classList.add("formulaire-modal-invisible");
                        document.getElementById("commande-resume-adresseFacturation-0").textContent  = `${adresseFacturation.options[adresseFacturation.selectedIndex].innerText.split('-')[0]}`;
                        document.getElementById("commande-resume-adresseFacturation-1").textContent  = `${adresseFacturation.options[adresseFacturation.selectedIndex].innerText.split('-')[1]}`;
                    } else {
                        document.getElementById("commande-resume-adresseFacturation-choix").classList.add("formulaire-modal-invisible");
                        document.getElementById("commande-resume-adresseFacturation-nouveau").classList.remove("formulaire-modal-invisible");
                        document.getElementById("commande-resume-adresseFacturation-numero").textContent  = `${numeroFacturation.value}`;
                        document.getElementById("commande-resume-adresseFacturation-typerue").textContent  = `${typeRueFacturation.value}`;
                        document.getElementById("commande-resume-adresseFacturation-rue").textContent  = `${rueFacturation.value}`;
                        document.getElementById("commande-resume-adresseFacturation-codepostal").textContent  = `${codePostalFacturation.value}`;
                        document.getElementById("commande-resume-adresseFacturation-ville").textContent  = `${villeFacturation.value}`;
                    }
                }
            }
        } else {
            page2.classList.add("formulaire-modal-invisible");
            nextPage.classList.remove("formulaire-modal-invisible");
        }
    } else if (idCurrent === 3) {
        if (idNext > idCurrent) {
            if ((numeroLivraison.value.length == 0 && typeRueLivraison.value.length == 0
            && rueLivraison.value.length == 0 && codePostalLivraison.value.length == 0
            && villeLivraison.value.length == 0 && !enregistrerLivraison.checked && !newLivraisonOpen) ||
            (numeroLivraison.value.length != 0 && typeRueLivraison.value.length != 0
            && rueLivraison.value.length != 0 && codePostalLivraison.value.length != 0
            && villeLivraison.value.length != 0)) {
                if (numeroLivraison.validity.valid && typeRueLivraison.validity.valid && rueLivraison.validity.valid && codePostalLivraison.validity.valid && villeLivraison.validity.valid && enregistrerLivraison.validity.valid) {
                    page3.classList.add("formulaire-modal-invisible");
                    nextPage.classList.remove("formulaire-modal-invisible");
                    if (!newLivraisonOpen) {
                        document.getElementById("commande-resume-adresseLivraison-choix").classList.remove("formulaire-modal-invisible");
                        document.getElementById("commande-resume-adresseLivraison-nouveau").classList.add("formulaire-modal-invisible");
                        document.getElementById("commande-resume-adresseLivraison-0").textContent  = `${adresseLivraison.options[adresseLivraison.selectedIndex].innerText.split('-')[0]}`;
                        document.getElementById("commande-resume-adresseLivraison-1").textContent  = `${adresseLivraison.options[adresseLivraison.selectedIndex].innerText.split('-')[1]}`;
                    } else {
                        document.getElementById("commande-resume-adresseLivraison-choix").classList.add("formulaire-modal-invisible");
                        document.getElementById("commande-resume-adresseLivraison-nouveau").classList.remove("formulaire-modal-invisible");
                        document.getElementById("commande-resume-adresseLivraison-numero").textContent  = `${numeroLivraison.value}`;
                        document.getElementById("commande-resume-adresseLivraison-typerue").textContent  = `${typeRueLivraison.value}`;
                        document.getElementById("commande-resume-adresseLivraison-rue").textContent  = `${rueLivraison.value}`;
                        document.getElementById("commande-resume-adresseLivraison-codepostal").textContent  = `${codePostalLivraison.value}`;
                        document.getElementById("commande-resume-adresseLivraison-ville").textContent  = `${villeLivraison.value}`;
                    }
                }
            }
        } else {
            page3.classList.add("formulaire-modal-invisible");
            nextPage.classList.remove("formulaire-modal-invisible");
        }
    } else if (idCurrent === 4) {
        page4.classList.add("formulaire-modal-invisible");
        nextPage.classList.remove("formulaire-modal-invisible");
    }
}

// Affiche la section du formulaire de validation de la commande pour ajouter une adresse de facturation
function showFormFacturation() {
    const choix = document.getElementById("choix-ajout-facturation");
    const form = document.getElementById("formulaire-ajout-facturation");
    choix.classList.add("formulaire-modal-invisible");
    form.classList.remove("formulaire-modal-invisible");
    newFacturationOpen = true;
}

// Cache la section du formulaire de validation de la commande pour ajouter une adresse de facturation
function hideFormFacturation() {
    const choix = document.getElementById("choix-ajout-facturation");
    const form = document.getElementById("formulaire-ajout-facturation");
    const numeroFacturation = document.getElementById("commande_numeroFacturation");
    const typeRueFacturation = document.getElementById("commande_typeRueFacturation");
    const rueFacturation = document.getElementById("commande_rueFacturation");
    const codePostalFacturation = document.getElementById("commande_codePostalFacturation");
    const villeFacturation = document.getElementById("commande_villeFacturation");
    choix.classList.remove("formulaire-modal-invisible");
    form.classList.add("formulaire-modal-invisible");
    numeroFacturation.value = '';
    typeRueFacturation.value = '';
    rueFacturation.value = '';
    codePostalFacturation.value = '';
    villeFacturation.value = '';
    newFacturationOpen = false;
}

// Affiche la section du formulaire de validation de la commande pour ajouter une adresse de livraison
function showFormLivraison() {
    const choix = document.getElementById("choix-ajout-livraison");
    const form = document.getElementById("formulaire-ajout-livraison");
    choix.classList.add("formulaire-modal-invisible");
    form.classList.remove("formulaire-modal-invisible");
    newLivraisonOpen = true;
}

// Cache la section du formulaire de validation de la commande pour ajouter une adresse de livraison
function hideFormLivraison() {
    const choix = document.getElementById("choix-ajout-livraison");
    const form = document.getElementById("formulaire-ajout-livraison");
    const numeroLivraison = document.getElementById("commande_numeroLivraison");
    const typeRueLivraison = document.getElementById("commande_typeRueLivraison");
    const rueLivraison = document.getElementById("commande_rueLivraison");
    const codePostalLivraison = document.getElementById("commande_codePostalLivraison");
    const villeLivraison = document.getElementById("commande_villeLivraison");
    choix.classList.remove("formulaire-modal-invisible");
    form.classList.add("formulaire-modal-invisible");
    numeroLivraison.value = '';
    typeRueLivraison.value = '';
    rueLivraison.value = '';
    codePostalLivraison.value = '';
    villeLivraison.value = '';
    newLivraisonOpen = false;
}