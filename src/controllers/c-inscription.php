<?php
require_once('src/model.php');

require_once('src/model.php');

function inscription() {
    $menu['page'] = 'inscription';
    $message = "";

    if (isset($_POST['inscription'])) {
        $email = $_POST['email'];
        $pseudo = $_POST['pseudo'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];
        $date_naissance = $_POST['date_naissance'];

        // Vérifier si l'e-mail est déjà utilisé
        $query = "SELECT email FROM utilisateur WHERE email = '$email'";
        $result = get_result($query);

        if ($result) {
            $message = "L'e-mail est déjà utilisé par un autre utilisateur.";
        } elseif ($password != $confirmPassword) {
            $message = "Les mots de passe ne correspondent pas.";
        } else {
            // Hasher le mot de passe avant de l'insérer dans la base de données
            $hashedPassword = hash('sha256', $password);

            $query = "INSERT INTO utilisateur (email, pseudo, date_naissance, password) VALUES ('$email', '$pseudo', '$date_naissance', '$hashedPassword')";
            insertion($query);
            $message = "Vous avez été inscrit avec succès $pseudo";
        }
    }

    require('view/inc/inc.head.php');
    require('view/v-inscription.php');
    require('view/inc/inc.footer.php');
}


