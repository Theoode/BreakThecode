<?php
session_start();
require_once('src/model.php');

function connexion() {
    $menu['page'] = 'connexion';

    $message = "";

    if (isset($_POST['connexion'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Hasher le mot de passe entré
        $hashedPassword = hash('sha256', $password);

        $query = "SELECT * FROM utilisateur WHERE email='$email' AND password='$hashedPassword'";
        $result = get_result($query);

        if ($result) {
            $messageSuccess = "Vous êtes maintenant connecté.";
            $_SESSION['email'] = $result['email'];
            $_SESSION['pseudo'] = $result['pseudo'];
            $_SESSION['nbWin'] = $result['nbWin'];
            $_SESSION['nbPlay'] = $result['nbPlay'];
            $_SESSION['date_naissance'] = $result['date_naissance'];
            $_SESSION['id'] = $result['id'];
            session_write_close();
            header("refresh:2;url=/");
            $redirect = "Connecté avec succès redirection vers l'accueil...";
        } else {
            $messageError = "E-mail ou mot de passe incorrect.";
        }
    }

    require('view/inc/inc.head.php');
    require('view/v-connexion.php');
    require('view/inc/inc.footer.php');
}