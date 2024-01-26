<?php
$nomBase = "breakthecode";
$adrServ = "localhost";
$portServ = 3306;
$userName = "root";
$userPWd = "";

try {
    $pdo = new PDO(
        "mysql:host=$adrServ;port=$portServ;dbname=$nomBase;charset=UTF8", $userName, $userPWd
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}

function last_insert_id(){
    global $pdo;
    return $pdo->lastInsertId();
}

function get_result($requete){
    global $pdo;
    $requete = $pdo -> query($requete);
    return $requete->fetch(PDO::FETCH_ASSOC);
}

function get_results($requete){
    global $pdo;
    $requete = $pdo -> query($requete);
    return $requete->fetchAll(PDO::FETCH_ASSOC);
}

function insertion($requete){
    global $pdo;
    $pdo->exec($requete);
    return $pdo->lastInsertId();
}

function suppression($requete){
    global $pdo;
    $pdo->exec($requete);
}
