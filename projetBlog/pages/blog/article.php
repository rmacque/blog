<?php

session_start();

use Blog\Contenu\Render_Article;

require_once "../vendor/autoload.php";

$id = (int)$parametres["id"];

$title = "Article $id";

$render = new Render_Article();
$render->recuperation("idArticle = $id")
->remplissageCategories()
->affichage($router);