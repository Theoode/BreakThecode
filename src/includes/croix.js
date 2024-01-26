document.addEventListener("DOMContentLoaded", function() {
    const tuiles = document.querySelectorAll('.tuile');

    tuiles.forEach(tuile => {
        tuile.addEventListener('click', function(event) {
            event.stopPropagation();

            const croixExiste = this.querySelector('.croix');

            if (!croixExiste) {
                let croix = document.createElement('div');
                croix.innerText = '✕';
                croix.classList.add('croix');

                croix.addEventListener('click', function(event) {
                    event.stopPropagation();
                    this.parentNode.removeChild(this);
                });

                this.appendChild(croix);
            }
        });
    });
});