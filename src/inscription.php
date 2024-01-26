<?php
require_once('model.php');
$errorMessage = "";
$success = "";

if (isset($_POST['inscription'])) {
$email = $_POST['email'];
$pseudo = $_POST['pseudo'];
$date_naissance = $_POST['date_naissance'];
$password = $_POST['password'];
$confirmationMotDePasse = $_POST['confirmationMotDePasse'];

// Vérification si l'email n'est pas déjà utilisé
$queryEmail = "SELECT email FROM utilisateur WHERE email = :email";
$paramsEmail = array(':email' => $email);
$resultEmail = get_result($queryEmail, $paramsEmail);

if ($resultEmail) {
$errorMessage = "L'email est déjà utilisé. Veuillez choisir un autre email.";
} else {
// Vérification si le pseudo n'est pas déjà utilisé
$queryPseudo = "SELECT pseudo FROM utilisateur WHERE pseudo = :pseudo";
$paramsPseudo = array(':pseudo' => $pseudo);
$resultPseudo = get_result($queryPseudo, $paramsPseudo);

if ($resultPseudo) {
$errorMessage = "Le pseudo est déjà utilisé. Veuillez en choisir un autre.";
} else {
// Vérification si les mots de passe correspondent
if ($password !== $confirmationMotDePasse) {
$errorMessage = "Les mots de passe ne correspondent pas. Veuillez réessayer.";
} else {
$query = "INSERT INTO utilisateur (email, pseudo, date_naissance, password) VALUES (:email, :pseudo, :date_naissance, :password)";
$params = array(
':email' => $email,
':pseudo' => $pseudo,
':date_naissance' => $date_naissance,
':password' => password_hash($password, PASSWORD_BCRYPT)
);

insertion($query, $params);
$errorMessage = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
}
}
}
}
echo $errorMessage;