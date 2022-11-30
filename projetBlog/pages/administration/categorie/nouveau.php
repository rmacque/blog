<?php

use Blog\Contenu\{
    Formulaire,
    Render_Categorie,
    Categorie
};

session_start();

$title = "Nouvelle Categorie";

$rendu = new Render_Categorie();
$form = new Formulaire($_POST, $router->url("creation_Categorie"));

if(!empty($_POST)){
    $form->getRegles()->rule("lengthMin","nomCategorie", 5);
    if($form->validate()){
        htmlentities($_POST["nomCategorie"]);
        
        $rendu->insert(new Categorie(0, $_POST["nomCategorie"]));
    }else{
        $erreurs = $form->errors();
    }
}

?>

<h1>Créer un nouvelle categorie</h1>

<?php
$form->input("nomCategorie", "Titre", null);
$form->create("", "Créer");
?>