<?php

session_start();

use Blog\Contenu\{
    Article,
    Formulaire,
    Render_Article,
};

$title = "Edition";

$id = (int)$parametres["id"];

$rendu = new Render_Article();

$form = new Formulaire($_POST, $router->url("edition_Article", ["id" => $id]));
$form->getRegles()->rule("lengthMin","nomArticle", 5);
$form->getRegles()->rule("required", "contenu");

if(!empty($_POST)){
    if($form->validate()){

        $rendu->update(new Article($id, $_POST["nomArticle"], $_POST["contenu"], date("Y-m-d H:i:s")));
        $rendu->assocUpdate($id, $_POST["associations"] ?? []);
        
        header("Location: ". $router->url("administration") . "?updateA=1");
        exit();
    }else{
        $erreurs = $form->errors();
    }
}

$article = $rendu->recuperation("idArticle = $id")->remplissageCategories()->getArticles()[0];

foreach($article->getCategories() as $categorie){
    $categorieA[] = $categorie->getNom();
}

foreach($rendu->allCategories() as $categorie){
    $listFinale[] = array(
        "id" => $categorie->getId() ,
        "nom" => $categorie->getNom(),
        "checked" => in_array($categorie->getNom(), $categorieA ?? [])
    );
}

echo "<h1>Article : ".htmlentities($article->getNom())."</h1>";

$form->input("nomArticle", "Titre", $article->getNom());
$form->checkGroup($listFinale, "Catégories associées");
$form->textarea("contenu", "Contenu", $article->getNom());
$form->create("", "Mettre à jour");
