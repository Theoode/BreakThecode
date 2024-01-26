<link rel="stylesheet" href="../src/includes/style/styleRegles.css">

<div>
    <div class="container">
        <div class="titre">REGLES DU JEU <span class="emoji loupe">📖</span></div>
        <div class="titre_but_du_jeu"><strong>BUT DU JEU</strong></div>
        <br>
        <div class="but_du_jeu">Votre but est de deviner les chiffres et les couleurs des tuiles de votre adversaire. Au centre de sont visible 6 cartes Question. À votre tour, choisissez en une pour poser la question inscrite dessus à votre adversaire. Apprenez ainsi un peu plus à chaque fois sur ces tuiles. Par déduction et par élimination, soyez le premier à trouver le code pour remporter la victoire.</div><br>
        <div class="titre_but_du_jeu"><strong>ELEMENTS DU JEU</strong></div>
        <div class="carte rectangle1">
            <img src="../src/images/fleche-courbe.png" class="icone-fleche" alt="Flèche" />
            <div style="font-weight: bold;">Cartes questions</div>
            <br>
            <div>Les cartes Question sont toutes différentes, et présentent une question en lien avec la valeur des tuiles, leur couleur ou leur position les unes par rapport aux autres. Elles servent à obtenir des informations sur les tuiles des autres joueurs.</div>
        </div>
        <div class="carte rectangle2">
            <img src="../src/images/fleche-courbe.png" class="icone-fleche" alt="Flèche" />
            <div style="font-weight: bold;">Note</div>
            <br>
            <div>Ces feuilles de notes facilitent votre réflexion. Notez-y, au fur et à mesure et comme bon vous semble, les indices récoltés. Les 20 chiffres en haut peuvent vous servir durant votre prise de notes. En bas à gauche, 5 cases sont réservées pour y inscrire votre réponse finale.</div>
        </div>
        <div class="carte rectangle3">
            <img src="../src/images/fleche-courbe.png" class="icone-fleche" alt="Flèche" />
            <div><strong>Tuiles chiffres</strong></div>
            <br>
            <div>Il existe 20 tuiles Chiffre. Chaque chiffre (entre 0 et 9) est présent sur 2 tuiles, une fois en noir et une fois en blanc, à l’exception des chiffres 5, qui sont uniquement présents en vert.</div>
            <br>
        </div>
        <div class="deroulement"><strong>DEROULEMENT DE LA PARTIE</strong></div>
        <br>
        <div class="point_deroulement">
            <div>1. Choisissez une question </div>
            <div>2. La réponse de l'adversaire apparaîtra sur la chat boxe </div>
            <div>3. Notez en conséquence sur votre partie Note les indices récoltés. </div>
            <div>4. C'est au tour de l'autre joueur</div>
        </div>
    </div>
</div>

<script>
    const cartes = document.querySelectorAll('.carte');

    cartes.forEach(carte => {
        carte.addEventListener('mouseover', () => {
            carte.style.backgroundColor = '#E0E0E0';
        });

        carte.addEventListener('mouseout', () => {
            carte.style.backgroundColor = '#F5F5F5';
        });
    });
</script>
