<?php

use Blog\Contenu\{
    Formulaire,
    Render_Article,
    Article
};

session_start();

$title = "Nouvel Article";

$rendu = new Render_Article();
$form = new Formulaire($_POST, $router->url("creation_Article"));

if(!empty($_POST)){
    $form->getRegles()->rule("lengthMin","nomArticle", 5);
    $form->getRegles()->rule("required", "contenu");
    if($form->validate()){
        
        $id = $rendu->insert(new Article(0, $_POST["nomArticle"], $_POST["contenu"], date("Y-m-d H:i:s"), $_POST["associations"]));
        $rendu->assocUpdate($id, $_POST["associations"] ?? []);

        header("Location: ". $router->url("administration") . "?insertA=1");
        exit();
    }else{
        $erreurs = $form->errors();
    }
}

foreach($rendu->allCategories() as $categorie){
    $listFinale[] = array(
        "id" => $categorie->getId() ,
        "nom" => $categorie->getNom(),
        "checked" => false
    );
}

echo "<h1>Créer un nouvel article</h1>";

$form->input("nomArticle", "Titre", null);
$form->textarea("contenu", "Contenu", null);
$form->checkGroup($listFinale, "Catégories associées");
$form->create("", "Créer l'article");
?>