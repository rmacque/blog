<?php

use Blog\Contenu\Formulaire;
use Blog\Contenu\Render_Utilisateur;

session_start();

$form = new Formulaire($_POST, $router->url("login"));
$form->getRegles()->rule("required", ["username", "password"]);

$rendu = new Render_Utilisateur();

if(!empty($_POST)){
    if($form->validate()){
        $recup = $rendu->recuperation("username = '$_POST[username]'");

        if($recup && password_verify($_POST["password"], $recup->getUser()->getPassword())){
            $_SESSION["auth"] = $_POST["username"];
            unset($_POST["password"]);
            unset($_POST["username"]);

            header("Location: ". $router->url("home") . "?login=1");
        }else{
            $msg = true;
        }
    }else{
        $erreurs = $form->errors();
    }
}

echo "<h1> se Connecter</h1>";

$form->input("username", "login", null);
$form->input("password", "password", null);
$form->create("", "Se connecter");

if(isset($msg)){
    echo "Login ou mot de passe incorrect";
}