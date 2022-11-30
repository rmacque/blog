<?php

use Blog\Utilitaires\Router;

require_once "../vendor/autoload.php";
require_once "../../vendor/autoload.php";

define("DEBUG_TIME", microtime(true));

if(isset($_GET["page"]) && $_GET["page"] === "1"){
    $url = explode("?", $_SERVER["REQUEST_URI"])[0];
    $get = $_GET;
    unset($get["page"]);
    $uri = http_build_query($get);
    if(!empty($uri)){
        $url = $url . "?" . $uri;
    }
    http_response_code(301);
    header("Location: ".$url);
    exit();
}

$router = new Router("../pages/");

    //Liens des en-têtes
$router->get("/", "blog/accueil", "home")
    ->get("/categories","blog/allCategories","liste_categorie")
    ->match("/admin","administration/admin", "administration")
    //liens d'affichage(utilisateurs)
    ->get("/blog/article-[i:id]", "blog/article", "article")
    ->get("/blog/categorie-[i:id]", "blog/categorie", "categorie")
    //Liens de gestion des articles(administration)
    ->match("/admin/article/edition-[i:id]","administration/article/edition", "edition_Article")
    ->match("/admin/article/creation","administration/article/nouveau", "creation_Article")
    ->post("/admin/article/suppression-[i:id]","administration/article/suppression", "suppression_Article")
    //Liens de gestion des catégories(administration)
    ->match("/admin/categorie/edition-[i:id]","administration/categorie/edition", "edition_Categorie")
    ->match("/admin/categorie/creation","administration/categorie/nouveau", "creation_Categorie")
    ->post("/admin/categorie/suppression-[i:id]","administration/categorie/suppression", "suppression_Categorie")

    //Connexion
    ->match("/login", "administration/login", "login")
    ->match("/logout", "administration/logout", "logout")

    ->run();