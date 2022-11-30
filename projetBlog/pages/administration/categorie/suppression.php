<?php

use Blog\Contenu\Render_Categorie;

$id = (int)$parametres["id"];

$categorie = new Render_Categorie();
$categorie->delete($id);

header("Location: ". $router->url("administration") . "?deleteC=1");
exit();
?>