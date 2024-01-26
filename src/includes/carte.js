// Sélection de tous les éléments input et select
const inputs = document.querySelectorAll('.input');
const selects = document.querySelectorAll('select');

// Fonction pour changer la couleur du texte en fonction de l'option sélectionnée pour chaque paire
function changeTextColor(event) {
    const couleur = event.target.value; // Récupérer la valeur de l'option sélectionnée

    // Trouver l'input associé au select
    const parentDiv = event.target.parentElement;
    const input = parentDiv.querySelector('.input');

    // Changer la couleur du texte de l'input en fonction de la couleur sélectionnée
    input.style.color = couleur;

    // Ajouter le texte-shadow si la couleur est blanche
    if (couleur === 'white') {
        input.style.textShadow = '-1px -1px 0 black, 1px -1px 0 black, -1px 1px 0 black, 1px 1px 0 black';
    } else {
        // Si une autre couleur est sélectionnée, enlever le texte-shadow
        input.style.textShadow = 'none';
    }
}

// Ajout d'un écouteur d'événements pour chaque select
selects.forEach(select => {
    select.addEventListener('change', changeTextColor);
});
