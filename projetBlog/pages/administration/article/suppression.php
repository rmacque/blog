<?php

use Blog\Contenu\Render_Article;

$id = (int)$parametres["id"];

$article = new Render_Article();
$article->delete($id);

header("Location: ". $router->url("administration") . "?deleteA=1");
exit();
?>