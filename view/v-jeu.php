    <link rel="stylesheet" href="src/includes/style/styleJeu.css">
    <title>Partie</title>
    <div>
        <div class="zone_aide">
            <a href="regles" target="_blank">
                <img class="icone" src="../src/images/help.png">
                <h3>Aide</h3>
            </a>
        </div>
        <div>
            <!-- Bouton pour revenir à l'accueil -->
            <a href="lobby" class="accueil-button">
                <button type="button">Accueil</button>
            </a>
        </div>
        <div class="box row">
            <?php foreach($lstTuileBlanche as $uneTuile){ ?>
                <div class="col-1 tuile">
                    <?php if($uneTuile['numero'] == 5) : ?>
                        <div>
                            <p class="chiffre_vert"><?=$uneTuile['numero']?></p>
                        </div>
                    <?php else : ?>
                        <div>
                            <p class="chiffre_blanc"><?=$uneTuile['numero']?></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php } ?>
        </div>
        <div class="box row">
            <?php foreach($lstTuileNoire as $uneTuile){ ?>
                <div class="col-1 tuile">
                    <?php if($uneTuile['numero'] == 5) : ?>
                        <div>
                            <p class="chiffre_vert"><?=$uneTuile['numero']?></p>
                        </div>
                    <?php else : ?>
                        <div>
                            <p class="chiffre_noir"><?=$uneTuile['numero']?></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php } ?>
        </div>
        <!-- La zone des questions -->
    <div class="zone_question">
        <?php foreach ($lesquestions as $unequestion){ ?>
        <div class="question" data-id="<?= $unequestion['id'] ?>">
            <p><?=$unequestion['label']?></p>
            <?php
            ?>
        </div>
       <?php } ?>
    </div>
        <?php
        // Count the total number of cards in the database
        $remainingCards = 21 - count($_SESSION['usedQuestions']);
        ?>

        <br>
        <div class="remaining-cards">
            <p>Infos parties : Cartes question restantes : <?= $remainingCards ?> | Tour de : ~</p>
        </div>

    <!-- La zone de la combinaison du joueur -->
    <div class="zone_cartes">
    <div class="zone_combinaison">
        <?php foreach($lacombinaison as $combinaison){ ?>
            <div class="col-1 tuile">
                <?php
                $classeChiffre = '';
                switch ($combinaison['id_couleur']) {
                    case 1:
                        $classeChiffre = 'chiffre_noir';
                        break;
                    case 2:
                        $classeChiffre = 'chiffre_blanc';
                        break;
                    case 3:
                        $classeChiffre = 'chiffre_vert';
                        break;
                    default:
                        $classeChiffre = 'autre_classe';
                        break;
                }
                ?>
                <div style="background-color: <?=$uneTuile['code_couleur']?>;" class="<?=$classeChiffre?>">
                    <p><?=$combinaison['numero']?></p>
                </div>
            </div>
        <?php } ?>
    </div>

        <form method="POST">
    <!--La zone des combinaison à trouver  -->
        <div class="container_carte">
            <div style="display: flex">
                <input type="number" name="input1" min="0" max="9" step="1" class="input" id="numberInput">
                <select name="couleur1">
                    <option value="white">Blanc</option>
                    <option value="green">Vert</option>
                    <option value="black" selected>Noir</option>
                </select>
            </div>

            <div style="display: flex">
                <input type="number" name="input2" min="0" max="9" step="1" class="input">
                <select name="couleur2">
                    <option value="white">Blanc</option>
                    <option value="green">Vert</option>
                    <option value="black" selected>Noir</option>
                </select>
            </div>

            <div style="display: flex">
                <input type="number" name="input3" min="0" max="9" step="1" class="input">
                <select name="couleur3">
                    <option value="white">Blanc</option>
                    <option value="green">Vert</option>
                    <option value="black" selected>Noir</option>
                </select>
            </div>

            <div style="display: flex">
                <input type="number"  name="input4" min="0" max="9" step="1" class="input">
                <select name="couleur4">
                    <option value="white">Blanc</option>
                    <option value="green">Vert</option>
                    <option value="black" selected>Noir</option>
                </select>
            </div>

            <div style="display: flex">
                <input type="number" name="input5" min="0" max="9" step="1" class="input">
                <select name="couleur5">
                    <option value="white">Blanc</option>
                    <option value="green">Vert</option>
                    <option value="black" selected>Noir</option>
                </select>
            </div>
                <button type="submit" name="tenter" value="tenter" style="background-color: black; color: white; border: none; padding: 10px 20px; margin-left: 15px">Tenter</button>
            </form>
        </div>
    </div>
    <div class="abandonner-button-container">
        <form method="POST">
            <button type="submit" name="abandonner" value="abandonner" class="abandonner-button">Abandonner</button>
        </form>
    </div>

    <script src="../src/includes/croix.js"></script>
    <script src="../src/includes/carte.js"></script>

