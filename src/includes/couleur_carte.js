document.addEventListener('DOMContentLoaded', function() {

const inputNumber = document.querySelector('.input');
const btns = document.querySelectorAll('.btn');

// Fonction pour changer la couleur du texte en fonction du bouton cliqué
function changeTextColor(event) {
    const couleur = event.target.getAttribute('data-couleur'); // Récupérer la couleur du bouton

    // Changer la couleur du texte de l'input number en fonction de la couleur sélectionnée
    inputNumber.style.color = couleur;
}

// Ajout d'un écouteur d'événements pour chaque bouton
btns.forEach(btn => {
    btn.addEventListener('click', changeTextColor);
});
});