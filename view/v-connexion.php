<?php
require_once('src/model.php');
?>

<link rel="stylesheet" href="src/includes/style/styleConnexion.css">

<form method="POST">
    <h2>BREAK THE CODE</h2>

    <input type="email" id="email" name="email" placeholder="email" required><hr>

    <input type="password" id="mdp" name="password" placeholder="mot de passe" required><hr>

    <?php if(isset($messageError)): ?>
        <div class="alert alert-success" role="alert">
            <p class="error-message"><?php echo $messageError; ?></p>
        </div>
    <?php endif; ?>
    <?php if(isset($messageSuccess)): ?>
        <div class="alert alert-success" role="alert">
            <p class="success-message"><strong><?php echo $messageSuccess; ?></strong></p>
            <p class="success-message" style="color: black"><?php echo $redirect; ?></p>
        </div>
    <?php endif; ?>

    <div class="divBtnConnexion">
        <button type="submit" name="connexion" value="connexion">Connexion</button>
        <button type="submit" href="lobby">Accueil</button>
    </div>

    <p style="text-align: center ">Vous n'avez pas de compte ? <a href="inscription" style="color: #28c431;text-decoration: none;">Inscrivez-vous ici</a> </p>
</form>
