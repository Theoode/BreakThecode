document.addEventListener("DOMContentLoaded", function () {
    const buttons = document.querySelectorAll('.bouton_couleurs input[type="button"]');

    buttons.forEach(button => {
        button.addEventListener("click", function () {
            const input = this.parentNode.previousElementSibling.querySelector('.input');
            const couleur = this.dataset.couleur;
            input.style.color = couleur;
        });
    });
});
