<?php
require_once('src/model.php');
?>

<link rel="stylesheet" href="src/includes/style/styleInscription.css">

<form method="POST">
    <h2>BREAK THE CODE</h2>

    <input type="email" id="email" name="email" placeholder="Email" required><hr>

    <input type="text" id="pseudo" name="pseudo" placeholder="Pseudo" required><hr>

    <input type="date" id="date_naissance" name="date_naissance" placeholder="Date de naissance : JJ/MM/AAAA" required><hr>

    <input type="password" id="password" name="password" placeholder="Mot de passe" required><hr>

    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirmer le mot de passe" required><hr>

    <?php if(isset($message)): ?>
        <div class="alert alert-success" role="alert">
            <p class="error-message"><?php echo $message; ?></p>
        </div>
    <?php endif; ?>

    <div class="divBtnInscription">
        <button type="submit" name="inscription" value="Inscription">S'inscrire</button>
        <button type="button" onclick="window.location.href='lobby'">Accueil</button>
    </div>
</form>
