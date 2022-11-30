<?php

use Blog\Contenu\{
    Formulaire,
    Render_Categorie,
};

session_start();

$title = "Edition";

$id = (int)$parametres["id"];

$rendu = new Render_Categorie();
$categorie = $rendu->recuperation("idCategorie = $id")->getCategories()[0];

$form = new Formulaire($_POST, $router->url("edition_Categorie"));

if(!empty($_POST)){
    $form->getRegles()->rule("lengthMin","nomCategorie", 5);
    //$form->getRegles()->rule("required", "contenu");
    if($form->validate()){
        htmlentities($_POST["nomCategorie"]);
        //htmlentities($_POST["contenu"]);
        
        $categorie->setNom($_POST["nomCategorie"]);
        //$categorie->setContenu($_POST["contenu"]);
        $rendu->update($categorie);
    }else{
        $erreurs = $form->errors();
    }
}

echo "<h1>Categorie : ".htmlentities($categorie->getNom())."</h1>";

$form->input("nomCategorie", "Titre", $categorie->getNom());
$form->create("", "Mettre à jour");
?>