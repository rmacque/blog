<?php

session_start();

use Blog\Contenu\Render_Categorie;

require_once "../vendor/autoload.php";

$id = (int)$parametres["id"];

$title = "Categorie $id";

$render = new Render_Categorie();

$render->recuperation("idCategorie = $id", 1)
->remplissageArticles()
->affichage($router);