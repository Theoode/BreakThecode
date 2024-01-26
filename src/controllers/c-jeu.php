<?php
require_once('src/model.php');

function jeu()
{ ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <?php
    $menu['page'] = "jeu";
    //Traitement
    if(isset($_SESSION['id'])) {
        $idUtilisateur = $_SESSION['id'];
        $idPartieQuery = "SELECT id FROM partie WHERE id_utilisateur = $idUtilisateur ORDER BY date_debut DESC LIMIT 1";
        $result = get_results($idPartieQuery);

        if ($result && count($result) > 0) {
            $idPartie = $result[0]['id'];

            $lstTuileBlanche = get_results("SELECT * FROM tuile, couleur WHERE tuile.id_couleur = couleur.id AND (id_couleur = 1 OR id_couleur = 2 OR id_couleur = 3) GROUP BY numero ORDER BY numero ");
            $lstTuileNoire = get_results("SELECT * FROM tuile, couleur WHERE tuile.id_couleur = couleur.id AND (id_couleur = 3 OR id_couleur = 2) GROUP BY numero ORDER BY numero ");

            $lesquestions = get_results("SELECT q.id, q.label FROM questions q 
    INNER JOIN questionPartie qp ON q.id IN (qp.question1, qp.question2, qp.question3, qp.question4, qp.question5, qp.question6) 
    WHERE qp.id_partie = $idPartie 
    ORDER BY FIELD(q.id, qp.question1, qp.question2, qp.question3, qp.question4, qp.question5, qp.question6)");

            $lacombinaison = get_results("SELECT tuile.*, couleur.* 
                              FROM tuileJoueur 
                              INNER JOIN tuile ON tuileJoueur.tuile1 = tuile.id OR tuileJoueur.tuile2 = tuile.id OR tuileJoueur.tuile3 = tuile.id OR tuileJoueur.tuile4 = tuile.id OR tuileJoueur.tuile5 = tuile.id
                              INNER JOIN couleur ON tuile.id_couleur = couleur.id
                              WHERE tuileJoueur.id_partie = $idPartie
                              ORDER BY tuile.numero ASC");

            // Récupérez la combinaison stockée dans tuileIA
            $combinaisonIA = get_results("SELECT tuile.id, tuile.numero, tuile.id_couleur
                              FROM tuileIA 
                              INNER JOIN tuile ON tuileIA.tuile1 = tuile.id OR tuileIA.tuile2 = tuile.id OR tuileIA.tuile3 = tuile.id OR tuileIA.tuile4 = tuile.id OR tuileIA.tuile5 = tuile.id
                              WHERE tuileIA.id_partie = $idPartie
                              ORDER BY tuile.numero ASC");
        } else {
            // Gérer le cas où aucune partie n'est trouvée pour cet utilisateur
            echo "Aucune partie trouvée pour cet utilisateur.";
        }
    }
    else {
        header("Location: /connexion");
        exit();
    }

    if (isset($_POST['tenter'])) {
        // Récupérez les valeurs des tuiles et des couleurs
        $tuilesUtilisateur = array(
            $_POST['input1'], $_POST['input2'], $_POST['input3'], $_POST['input4'], $_POST['input5']
        );
        $couleursUtilisateur = array(
            $_POST['couleur1'], $_POST['couleur2'], $_POST['couleur3'], $_POST['couleur4'], $_POST['couleur5']
        );

        function convertirCouleur($couleur) {
            switch ($couleur) {
                case "white":
                    return 2; // Correspond à la couleur blanche dans la base de données
                case "green":
                    return 3; // Correspond à la couleur verte dans la base de données
                case "black":
                    return 1; // Correspond à la couleur noire dans la base de données
                default:
                    return 0; // Valeur par défaut (peut être ajustée selon votre base de données)
            }
        }

        // Convertir le tableau des couleurs utilisateur en un tableau d'entiers
        $couleursUtilisateurInt = array_map('convertirCouleur', $couleursUtilisateur);

// Comparaison des tuiles et couleurs utilisateur avec celles stockées dans tuileIA
        // Comparaison des tuiles et couleurs utilisateur avec celles stockées dans tuileIA
        $gagne = true;
        for ($i = 0; $i < 5; $i++) {
            $tuileIDA = intval($combinaisonIA[$i]['id']);
            $tuileNumeroIA = intval($combinaisonIA[$i]['numero']);
            $tuileCouleurIA = intval($combinaisonIA[$i]['id_couleur']);

            $tuileNumeroUtilisateur = intval($tuilesUtilisateur[$i]);
            $couleurUtilisateur = $couleursUtilisateurInt[$i];
            if ($tuileNumeroUtilisateur !== $tuileNumeroIA || $couleurUtilisateur !== $tuileCouleurIA) {
                $gagne = false;
                break;
            }
        }

        if($gagne){
            insertion(("UPDATE partie SET resultat = 1 WHERE id = $idPartie"));

            // Incrémenter nbWin et nbPlay dans la table utilisateur
            insertion(("UPDATE utilisateur SET nbWin = nbWin + 1, nbPlay = nbPlay + 1 WHERE id = $idUtilisateur"));
        }

// Affichage du résultat
        echo '<script>';
        if ($gagne) {
            echo 'alert("Vous avez gagné !");';
            echo 'window.location.href = "/";'; // Redirection après l'affichage de l'alerte
        } else {
            echo 'alert("Mauvaise combinaison !");';
        }
        echo '</script>';
    }

    if (isset($_POST['abandonner'])) {
        insertion(("UPDATE partie SET resultat = 1 WHERE id = $idPartie"));
        insertion(("UPDATE utilisateur SET nbPlay = nbPlay + 1 WHERE id = $idUtilisateur"));
        unset($_SESSION['usedQuestions']);
        header("Location: /");
        exit();
    }

    function processQuestionClick($idPartie, $questionId) {
        // Start or resume the session
        session_start();

        // Create an array to store used question IDs in the session
        if (!isset($_SESSION['usedQuestions'])) {
            $_SESSION['usedQuestions'] = [];
        }

        // Récupérer les questions utilisées
        $usedQuestions = $_SESSION['usedQuestions'];

        $fields = ['question1', 'question2', 'question3', 'question4', 'question5', 'question6'];

        // Enregistre l'id de la question utilisée
        $selectedField = null;
        foreach ($fields as $field) {
            $result = get_results("SELECT $field FROM questionPartie WHERE id_partie = $idPartie AND $field = $questionId");
            if ($result && count($result) > 0) {
                $selectedField = $field;
                break;
            }
        }

        if (!is_null($selectedField)) {
            // Enregistre l'id de la question utilisée
            $selectedFieldIndex = array_search($selectedField, $fields);

            // ajoute l'id de la question au tableau
            $usedQuestions[] = $questionId;

            $_SESSION['usedQuestions'] = $usedQuestions;

            // supprime la question utilisée
            suppression("UPDATE questionPartie SET $selectedField = NULL WHERE id_partie = $idPartie");

            // nouvelle question pas dans questionPartie ni dans les questions utilisées
            $newQuestion = get_results("SELECT id FROM questions WHERE id NOT IN (SELECT question1 FROM questionPartie WHERE id_partie = $idPartie UNION SELECT question2 FROM questionPartie WHERE id_partie = $idPartie UNION SELECT question3 FROM questionPartie WHERE id_partie = $idPartie UNION SELECT question4 FROM questionPartie WHERE id_partie = $idPartie UNION SELECT question5 FROM questionPartie WHERE id_partie = $idPartie UNION SELECT question6 FROM questionPartie WHERE id_partie = $idPartie) AND id NOT IN (" . implode(",", $usedQuestions) . ") ORDER BY RAND() LIMIT 1");

            if ($newQuestion && count($newQuestion) > 0) {
                $newQuestionId = $newQuestion[0]['id'];

                // Insert la nouvelle question dans questionPartie
                insertion("UPDATE questionPartie SET $selectedField = $newQuestionId WHERE id_partie = $idPartie");

                // Garde le meme ordre des question dans questionPartie
                $originalField = $fields[$selectedFieldIndex];
                $newField = "question" . ($selectedFieldIndex + 1);
                suppression("UPDATE questionPartie SET $originalField = NULL WHERE id_partie = $idPartie");
                insertion("UPDATE questionPartie SET $newField = $newQuestionId WHERE id_partie = $idPartie");
            }
        }
    }

    if (isset($_POST['questionId'])) {
        // Si la requête AJAX pour le clic sur la question est reçue
        $questionId = intval($_POST['questionId']);
        processQuestionClick($idPartie, $questionId);
        exit();
    }


    ?>
    <script>
        function getTuileEmplacement(index) {
            var emplacements = ['A', 'B', 'C', 'D', 'E'];
            return emplacements[index];
        }

        function handleQuestionClick(questionId, combinaisonIA) {
            if (questionId === '1') {
                var tuile3 = parseInt(combinaisonIA[2]['numero'], 10);
                var tuile4 = parseInt(combinaisonIA[3]['numero'], 10);
                var tuile5 = parseInt(combinaisonIA[4]['numero'], 10);
                var sommeTuiles = tuile3 + tuile4 + tuile5;
                alert('La somme des chiffres des trois tuiles de droite est : ' + sommeTuiles);
            }
            else if (questionId==='2'){
                var tuile1 = parseInt(combinaisonIA[0]['numero'], 10);
                var tuile2 = parseInt(combinaisonIA[1]['numero'], 10);
                var tuile3 = parseInt(combinaisonIA[2]['numero'], 10);
                var sommeTuiles = tuile1 + tuile2 + tuile3;
                alert('La somme des chiffres des trois tuiles de gauche est : ' + sommeTuiles);
            } else if (questionId === '3') {
                var sommeTuilesNoires = 0;
                for (var i = 0; i < combinaisonIA.length; i++) {
                    if(combinaisonIA[i]['id_couleur']==='1')
                    sommeTuilesNoires += parseInt(combinaisonIA[i]['numero'], 10);
                }
                alert('La somme des chiffres des tuiles noires est : ' + sommeTuilesNoires);
            }else if (questionId === '4') {
                var tuilesVoisinesParCouleur = {};

                for (var i = 0; i < combinaisonIA.length; i++) {
                    var tuileCourante = combinaisonIA[i];
                    var tuilePrecedente = (i > 0) ? combinaisonIA[i - 1] : null;

                    if (tuilePrecedente && tuilePrecedente['id_couleur'] === tuileCourante['id_couleur']) {
                        var emplacementCourant = getTuileEmplacement(i);
                        var emplacementPrecedent = getTuileEmplacement(i - 1);

                        if (!tuilesVoisinesParCouleur[tuileCourante['id_couleur']]) {
                            tuilesVoisinesParCouleur[tuileCourante['id_couleur']] = [];
                        }

                        tuilesVoisinesParCouleur[tuileCourante['id_couleur']].push(emplacementCourant, emplacementPrecedent);
                    }
                }

                for (var couleur in tuilesVoisinesParCouleur) {
                    if (tuilesVoisinesParCouleur.hasOwnProperty(couleur)) {
                        var emplacements = tuilesVoisinesParCouleur[couleur].join(', ');
                        alert('Tuiles voisines avec des chiffres de même couleur : ' + emplacements);
                    }
                }
            } else if (questionId === '5') {
                var sommeTuilesNoires = 0;
                for (var i = 0; i < combinaisonIA.length; i++) {
                    if(combinaisonIA[i]['id_couleur']==='2')
                        sommeTuilesNoires += parseInt(combinaisonIA[i]['numero'], 10);
                }
                alert('La somme des chiffres des tuiles noires est : ' + sommeTuilesNoires);
            } else if (questionId === '6') {
                var nbTuilesNoires = 0;
                for (var i = 0; i < combinaisonIA.length; i++) {
                    if(combinaisonIA[i]['id_couleur']==='1')
                        nbTuilesNoires += 1;
                }
                alert('Le nombre de tuile avec un chiffre noir est : ' + nbTuilesNoires);
            }else if (questionId === '7') {
                var chiffres = combinaisonIA.map(function (tuile) {
                    return parseInt(tuile['numero'], 10);
                });

                var maxChiffre = Math.max(...chiffres);
                var minChiffre = Math.min(...chiffres);

                var difference = maxChiffre - minChiffre;

                alert('La différence entre le plus grand et le plus petit chiffre est : ' + difference);
            } else if (questionId === '8') {
                var chiffresCount = {};  // Utilisez un objet pour stocker le nombre de occurrences de chaque chiffre

                for (var i = 0; i < combinaisonIA.length; i++) {
                    var chiffre = combinaisonIA[i]['numero'];

                    if (!chiffresCount[chiffre]) {
                        chiffresCount[chiffre] = 1;
                    } else {
                        chiffresCount[chiffre]++;
                    }
                }
                var tuilesAvecChiffresEgaux = Object.values(chiffresCount).filter(count => count > 1).length;
                alert('Le nombre de tuiles avec des chiffres égaux est : ' + tuilesAvecChiffresEgaux);
            } else if (questionId==='9'){
                var tuileC = parseInt(combinaisonIA[2]['numero'], 10);
                var reponse = "NON la tuile C n'est pas strictement supérieur a 4";
                if (tuileC > 4){
                    reponse = "OUI la tuile C est strictement supérieur a 4";
                }
                alert(reponse);
            } else if (questionId==='10'){
                var nbTuilePair = 0;
                for (var i = 0; i<combinaisonIA.length; i++ ){
                    var tuile = parseInt(combinaisonIA[i]['numero'],10);
                    if(tuile%2 === 0){
                        nbTuilePair += 1;
                    }
                }
                alert('Nombre de tuiles pair : ' + nbTuilePair);
            } else if (questionId==='11'){
                var nbTuileImpair = 0;
                for (var i = 0; i<combinaisonIA.length; i++ ){
                    var tuile = parseInt(combinaisonIA[i]['numero'],10);
                    if(tuile%2 !== 0){
                        nbTuileImpair += 1;
                    }
                }
                alert('Nombre de tuiles impair : ' + nbTuileImpair);
            } else if (questionId === '12'){
                var tuilesSeSuivent = [];

                for (var i = 1; i < combinaisonIA.length; i++) {
                    var tuileCourante = parseInt(combinaisonIA[i]['numero'], 10);
                    var tuilePrecedente = parseInt(combinaisonIA[i - 1]['numero'], 10);

                    if (tuileCourante - tuilePrecedente === 1) {
                        var emplacementCourant = getTuileEmplacement(i);
                        var emplacementPrecedent = getTuileEmplacement(i - 1);

                        tuilesSeSuivent.push(emplacementPrecedent, emplacementCourant);
                    }
                }
                alert('Tuiles dont les chiffres se suivent : ' + tuilesSeSuivent.join(', '));
            }else if (questionId === '13') {
                var tuiles1ou2 = [];

                for (var i = 0; i < combinaisonIA.length; i++) {
                    var numeroTuile = parseInt(combinaisonIA[i]['numero'], 10);

                    if (numeroTuile === 1 || numeroTuile === 2) {
                        var emplacement = getTuileEmplacement(i);
                        tuiles1ou2.push(emplacement);
                    }
                }
                alert('Tuiles 1 ou tuiles 2 : ' + tuiles1ou2.join(', '));
            } else if (questionId === '15') {
                var tuiles6ou7 = [];

                for (var i = 0; i < combinaisonIA.length; i++) {
                    var numeroTuile = parseInt(combinaisonIA[i]['numero'], 10);

                    if (numeroTuile === 6 || numeroTuile === 7) {
                        var emplacement = getTuileEmplacement(i);
                        tuiles6ou7.push(emplacement);
                    }
                }
                alert('Tuiles 6 ou tuiles 7 : ' + tuiles6ou7.join(', '));
            } else if (questionId === '16') {
                var tuiles5 = [];
                for (var i = 0; i < combinaisonIA.length; i++) {
                    var numeroTuile = parseInt(combinaisonIA[i]['numero'], 10);

                    if (numeroTuile === 5) {
                        var emplacement = getTuileEmplacement(i);
                        tuiles5.push(emplacement);
                    }
                }
                alert('Tuiles 5 : ' + tuiles5);
            } else if (questionId === '17') {
                var nbTuilesBlanches = 0;
                for (var i = 0; i < combinaisonIA.length; i++) {
                    if(combinaisonIA[i]['id_couleur']==='2')
                        nbTuilesBlanches += 1;
                }
                alert('Le nombre de tuile avec un chiffre blanc est : ' + nbTuilesBlanches);
            } else if (questionId === '18') {
                var tuiles3ou4 = [];

                for (var i = 0; i < combinaisonIA.length; i++) {
                    var numeroTuile = parseInt(combinaisonIA[i]['numero'], 10);

                    if (numeroTuile === 3 || numeroTuile === 4) {
                        var emplacement = getTuileEmplacement(i);
                        tuiles3ou4.push(emplacement);
                    }
                }
                alert('Tuiles 3 ou tuiles 4 : ' + tuiles3ou4.join(', '));
            } else if (questionId === '19') {
                var tuiles8ou9 = [];

                for (var i = 0; i < combinaisonIA.length; i++) {
                    var numeroTuile = parseInt(combinaisonIA[i]['numero'], 10);

                    if (numeroTuile === 8 || numeroTuile === 9) {
                        var emplacement = getTuileEmplacement(i);
                        tuiles8ou9.push(emplacement);
                    }
                }
                alert('Tuiles 8 ou tuiles 9 : ' + tuiles8ou9.join(', '));
            } else if (questionId==='20'){
                var tuile2 = parseInt(combinaisonIA[1]['numero'], 10);
                var tuile3 = parseInt(combinaisonIA[2]['numero'], 10);
                var tuile4 = parseInt(combinaisonIA[3]['numero'], 10);
                var sommeTuiles = tuile2 + tuile3 + tuile4;
                alert('La somme des chiffres des trois tuiles du centre est : ' + sommeTuiles);
            } else if (questionId === '21') {
                var tuiles0 = [];
                for (var i = 0; i < combinaisonIA.length; i++) {
                    var numeroTuile = parseInt(combinaisonIA[i]['numero'], 10);

                    if (numeroTuile === 0) {
                        var emplacement = getTuileEmplacement(i);
                        tuiles0.push(emplacement);
                    }
                }
                alert('Tuiles 0 : ' + tuiles0);
            } else if (questionId==='22'){
                var tuile1 = parseInt(combinaisonIA[0]['numero'], 10);
                var tuile2 = parseInt(combinaisonIA[1]['numero'], 10);
                var tuile3 = parseInt(combinaisonIA[2]['numero'], 10);
                var tuile4 = parseInt(combinaisonIA[3]['numero'], 10);
                var tuile5 = parseInt(combinaisonIA[4]['numero'], 10);
                var sommeTuiles = tuile1 + tuile2 + tuile3 + tuile4 + tuile5;
                alert('La somme de tout les chiffre est : ' + sommeTuiles);
            }
            var idPartie = <?php echo $idPartie; ?>;
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Déclencher le rechargement de la page après la résolution de la promesse
                    setTimeout(function () {
                        location.reload();
                    }, 500);
                }
            };
            xhr.send("idPartie=" + idPartie + "&questionId=" + questionId);
        }

        document.addEventListener('DOMContentLoaded', function () {
            var questions = document.querySelectorAll('.question');
            var combinaisonIA = <?php echo json_encode($combinaisonIA); ?>;
            console.log(combinaisonIA);
            questions.forEach(function (question) {
                question.addEventListener('click', function () {
                    var questionId = question.getAttribute('data-id');
                    handleQuestionClick(questionId, combinaisonIA);
                });
            });
        });
    </script>

    <?php
    include('view/inc/inc.head.php');
    include('view/v-jeu.php');
    include('view/inc/inc.footer.php');
}
