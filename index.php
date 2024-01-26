<?php
require_once ('src/controllers/c-connexion.php');
require_once ('src/controllers/c-lobby.php');
require_once ('src/controllers/c-inscription.php');
require_once ('src/controllers/c-regles.php');
require_once ('src/controllers/c-jeu.php');

if(isset($_GET['url']) && $_GET['url']) {
    $url = rtrim($_GET['url'], '/');
    if ($url) {
        switch ($url) {
            case 'connexion':
                connexion();
                break;
            case 'inscription':
                inscription();
                break;
            case 'regles':
                regles();
                break;
            case 'jeu':
                jeu();
                break;
            default :
                lobby();
                break;
        }
    } else lobby();
}else lobby();

