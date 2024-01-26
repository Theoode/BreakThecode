<?php
require_once('src/model.php');
function lobby(){
    session_start();
?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<?php
    $menu['page'] = 'lobby';


    if (isset($_POST['jouer'])) {
        if (isset($_SESSION['id'])) {
            $idUtilisateur = $_SESSION['id'];

            // Vérifier si une partie non terminée existe déjà pour cet utilisateur
            $existingGame = "SELECT * FROM partie WHERE id_utilisateur = '$idUtilisateur' AND resultat = 0";
            $result = get_result($existingGame);
            if ($result) {
                // Une partie non terminée existe, rediriger vers la page de jeu
                header("Location: /jeu");
                exit();
            }

            // Si aucune partie non terminée n'existe, créer une nouvelle partie
            $premierJoueur = 1;
            $dateDebut = date('Y-m-d H:i:s');
            $dateFin = null;
            $resultat = 0;

            // Requête SQL pour insérer les données dans la table "partie"
            $insert = "INSERT INTO partie (id_utilisateur, premier_joueur, date_debut, date_fin, resultat) 
                    VALUES ('$idUtilisateur', '$premierJoueur', '$dateDebut', '$dateFin', '$resultat')";
            insertion($insert);

            $idPartie = last_insert_id();

            // Sélectionner 5 tuiles aléatoires pour le joueur
            $tilesPlayerQuery = "SELECT id FROM tuile ORDER BY RAND() LIMIT 5";
            $resultTilesPlayer = get_results($tilesPlayerQuery);

// Vérifier si des tuiles ont été récupérées
            if (!empty($resultTilesPlayer)) {
                $playerTileIds = implode(',', array_column($resultTilesPlayer, 'id'));

                // Sélectionner les tuiles triées pour le joueur
                $sortedPlayerTilesQuery = "SELECT id, numero FROM tuile WHERE id IN ($playerTileIds) ORDER BY numero";
                $resultSortedPlayerTiles = get_results($sortedPlayerTilesQuery);

                // Vérifier si des tuiles triées pour le joueur ont été récupérées
                if (!empty($resultSortedPlayerTiles)) {
                    // Insérer les tuiles triées dans une nouvelle ligne de la table tuileJoueur
                    $playerTilesInsertQuery = "INSERT INTO tuileJoueur (id_partie, tuile1, tuile2, tuile3, tuile4, tuile5) VALUES ('$idPartie'";
                    for ($i = 0; $i < 5; $i++) {
                        $playerTilesInsertQuery .= ", '{$resultSortedPlayerTiles[$i]['id']}'";
                    }
                    $playerTilesInsertQuery .= ")";
                    insertion($playerTilesInsertQuery);

                    // Sélectionner 5 tuiles distinctes pour l'IA
                    $iaTilesQuery = "SELECT id FROM tuile WHERE id NOT IN ($playerTileIds) ORDER BY RAND() LIMIT 5";
                    $resultIATiles = get_results($iaTilesQuery);

                    // Vérifier si des tuiles pour l'IA ont été récupérées
                    if (!empty($resultIATiles)) {
                        $iaTileIds = implode(',', array_column($resultIATiles, 'id'));

                        // Sélectionner les tuiles triées pour l'IA
                        $sortedIATilesQuery = "SELECT id, numero FROM tuile WHERE id IN ($iaTileIds) ORDER BY numero";
                        $resultSortedIATiles = get_results($sortedIATilesQuery);

                        // Vérifier si des tuiles triées pour l'IA ont été récupérées
                        if (!empty($resultSortedIATiles)) {
                            // Insérer les tuiles triées dans une nouvelle ligne de la table tuileIA
                            $iaTilesInsertQuery = "INSERT INTO tuileIA (id_partie, tuile1, tuile2, tuile3, tuile4, tuile5) VALUES ('$idPartie'";
                            for ($i = 0; $i < 5; $i++) {
                                $iaTilesInsertQuery .= ", '{$resultSortedIATiles[$i]['id']}'";
                            }
                            $iaTilesInsertQuery .= ")";
                            insertion($iaTilesInsertQuery);
                        }
                    }
                }
            }
            $questionPartie = "SELECT * FROM questions ORDER BY RAND() LIMIT 6";
            $resultQuestionPartie = get_results($questionPartie);

            // Insérer les tuiles sélectionnées dans une nouvelle ligne de la table tuileJoueur
            $insertQuestionPartie = "INSERT INTO questionPartie (id_partie, question1, question2, question3, question4, question5, question6) VALUES ('$idPartie','{$resultQuestionPartie[0]['id']}', '{$resultQuestionPartie[1]['id']}', '{$resultQuestionPartie[2]['id']}', '{$resultQuestionPartie[3]['id']}', '{$resultQuestionPartie[4]['id']}', '{$resultQuestionPartie[5]['id']}')";
            insertion($insertQuestionPartie);

            header("Location: /jeu");
        }
        else{
            header("Location: /connexion");
        }
        exit();
    }

        if (isset($_POST['logout'])) {
        // Destruction de la session
        session_destroy();
        session_unset();
        header("Location: /");
        exit();
    }

    require('view/inc/inc.head.php');
    require('view/v-lobby.php');
    require('view/inc/inc.footer.php');
}

