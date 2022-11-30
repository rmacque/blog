<?php

session_start();

use Blog\Contenu\Render_Categorie;
use Blog\Utilitaires\URL;

require_once "../vendor/autoload.php";

$title = "Toutes les Catégories";
define("NB_AFFICHE", 4);

$page = URL::getPositiveInt("page", 1);

echo "<h1>Catégories</h1>";

$render = new Render_Categorie();
$render->recuperation("", $page)
->remplissageArticles()
->aperçus($router, NB_AFFICHE);