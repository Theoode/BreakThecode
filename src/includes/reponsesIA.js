// jeu_script.js

document.addEventListener('DOMContentLoaded', function() {
    var questions = document.querySelectorAll('.question');

    questions.forEach(function(question) {
        question.addEventListener('click', function() {
            var questionId = question.getAttribute('data-id');

            // AJAX request...
            // (Remplacez cette partie par votre logique d'interaction avec le serveur)

            // Exemple pour montrer l'utilisation de l'ID dans une alerte
            alert('Question ID: ' + questionId);
        });
    });
});
