<?php

session_start();

use Blog\Contenu\Render_Article;
use Blog\Utilitaires\URL;

require_once "../vendor/autoload.php";

$title = "Accueil";
define("NB_AFFICHE", 4);

$vars = $_GET;
if(isset($vars["login"])){
    echo "<div class=\"info\">Connexion réussie</div>";
}else if(isset($vars["logout"])){
    echo "<div class=\"info\">Deconnexion réussie</div>";
}

$page = URL::getPositiveInt("page", 1);

echo "<h1>Accueil</h1>";

$render = new Render_Article(NB_AFFICHE);
$render->recuperation("", $page)
->remplissageCategories()
->listAperçus($router, NB_AFFICHE);