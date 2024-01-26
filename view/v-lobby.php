<link rel="stylesheet" href="src/includes/style/styleLobby.css">
<?php

?>
<div class="container">
    <div class="row">
        <div class="col-md-12 title">BREAK THE CODE</div>
        <div class="col-lg-7 left-section">
            <div class="description">
                <strong>But du jeu </strong> : mettez à l'épreuve vos compétences de déduction et de logique. Votre objectif est de décoder un message secret en utilisant des indices. Pour ce faire, vous devrez poser des questions à votre adversaire pour collecter des informations sur le code. Le premier joueur à décrypter le code adverse gagne la partie.
            </div>
            <form method="POST" style="padding: 10px">
            <?php
            if(isset($_SESSION['id'])) {
            $idUtilisateur = $_SESSION['id'];
            $idPartieQuery = "SELECT id, resultat FROM partie WHERE id_utilisateur = $idUtilisateur ORDER BY date_debut DESC LIMIT 1";
            $result = get_results($idPartieQuery);
            $partie = $result[0];

            if ($result && $partie['resultat']==0 && count($result) > 0) {
                ?>
                <button type="submit" name="jouer" class="launch-button" value="jouer">Reprendre</button>
                <?php
                }
            else{
                ?>
                <button type="submit" name="jouer" class="launch-button" value="jouer">Jouer</button>
                <?php
            }
            }
            else{
                ?>
                <button type="submit" name="jouer" class="launch-button" value="jouer">Connectez-vous pour jouer</button>
                <?php
            }
                ?>
            </form>
            <p class="description">Pour en savoir plus sur les règles <a href="regles"> cliquez ici </a> </p>
            <div class="hint"></div>
        </div>
        <!-- Partie de droite -->
        <div class="col-lg-5 right-section">
            <form method="post">
                <?php
                if (isset($_SESSION['id'])) {
                    // Si l'utilisateur est connecté, récupérer les statistiques depuis la base de données
                    $userId = $_SESSION['id'];

                    // Remplacez "votre_table_utilisateur" par le nom réel de votre table utilisateur
                    $query = "SELECT pseudo, date_naissance, nbPlay, nbWin FROM utilisateur WHERE id = $userId";
                    $user = get_result($query);

                    if ($user) {
                        echo '<h2><strong>Statistiques de l\'utilisateur</strong></h2>';
                        $dateNaissance = new DateTime($user['date_naissance']);
                        $aujourdHui = new DateTime();
                        $age = $dateNaissance->diff($aujourdHui)->y;

                        $pourcentageVictoires = ($user['nbPlay'] > 0) ? ($user['nbWin'] / $user['nbPlay']) * 100 : 0;
                        ?>
                        <div class="stats">
                            <?php
                            echo '<p><strong>Pseudo :</strong> ' . $user['pseudo'] . '</p>';
                            echo '<p><strong>Âge</strong> : ' . $age . ' ans</p>';
                            echo '<p><strong>Nombre de victoires :</strong> ' . $user['nbWin'] . '</p>';
                            echo '<p><strong>Nombre de parties jouées :</strong> ' . $user['nbPlay'] . '</p>';
                            echo '<p><strong>Pourcentage de victoires :</strong> ' . round($pourcentageVictoires, 2) . '%</p>';
                            ?>
                        </div>
                        <div class="divLogoutBtn">
                            <?php
                            echo '<form method="post">';
                            echo '<button class="buttonLogout" type="submit" name="logout">Se déconnecter</button>';
                            echo '</form>';
                            ?>
                        </div>
                        <?php
                    }
                } else {
                    // Si l'utilisateur n'est pas connecté, afficher le contenu actuel
                    echo '<h2>Pour afficher vos statistiques veuillez vous connecter</h2>';
                    echo '<div class="text-center">';
                    echo '<button type="button" class="text-center"> <a href="connexion">Se connecter</a></button>';
                    echo '</div>';
                    echo '<div class="text-center">';
                    echo '<p>Si vous n\'avez pas de compte <a href="inscription">inscrivez vous ici</a></p>';
                    echo '</div>';
                }
                ?>
            </form>
        </div>

    </div>
</div>

<script>
    var astuces = [
        "Pour décoder un code secret avec succès, soyez attentif aux détails et utilisez les indices à bon escient.",
        "La patience est une vertu. Choisissez des questions stratégiques pour collecter les indices dont vous avez besoin.",
        "Lorsque vous posez des questions à votre adversaire, soyez attentif aux réponses et notez-les.",
        "La déduction est la clé. Utilisez des techniques de logique pour résoudre le message secret plus rapidement.",
        "Même un petit indice peut faire la différence. Ne négligez aucune information que vous obtenez.",
        "Restez concentré sur votre objectif de décryptage. Le premier joueur à résoudre le code adverse est le vainqueur."
    ];

    // Fonction pour afficher une astuce aléatoire
    function afficherAstuceAleatoire() {
        var randomIndex = Math.floor(Math.random() * astuces.length);
        var astuce = astuces[randomIndex];
        document.querySelector(".hint").textContent = astuce;
    }

    afficherAstuceAleatoire();
</script>
