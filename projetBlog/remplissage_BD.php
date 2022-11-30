<?php

use function Sites\connexion_BD;

require_once "../utilitaires/Connexions_BD.php";

$pdo = connexion_BD("blog");

$requête = "INSERT INTO categorie(nomCategorie, slugCategorie) VALUES ";
$categories =  ["Voyage", "Vie", "Bricolage", "Electronique", "Passion"];

for($i = 0; $i<5; $i++){
    $requête .= "('$categories[$i]', 'cat-".substr($categories[$i], 0, 2)."'),";
}

$requête = substr($requête, 0, -1);
$pdo->query($requête);

$requête = "INSERT INTO utilisateur(username, password) VALUES ";
$utilisateurs =  ["a", "b", "c"];
$mdp = ["aaa", "bbb", "ccc"];

for($i = 0; $i<3; $i++){
    $requête .= "('$utilisateurs[$i]', '".password_hash($mdp[$i], PASSWORD_DEFAULT)."'),";
}

$requête = substr($requête, 0, -1);
$pdo->query($requête);

$requête = "INSERT INTO article(nomArticle, slugArticle, contenu, date_creation) VALUES ";

$noms = ["Auvergne", "Bretagne", "marcher", "courir", "lego", "kapla", "ecran", "souris", "costumes", "théâtres"];
$contenus = ["aaa", "bbb", "ccc", "ddd", "eee", "fff", "ggg", "hhh", "iii", "jjj"];

for($i = 0; $i<10; $i++){
    $requête .= "('$noms[$i]', 'n-".substr($noms[$i],0, 4)."', '$contenus[$i]', '2022-03-14'),";
}

$requête = substr($requête, 0, -1);
$pdo->query($requête);

?>