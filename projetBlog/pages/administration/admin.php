<?php
session_start();
if(!isset($_SESSION["auth"])){
    header("Location: " . $router->url("login"));
}

use Blog\Contenu\Render_Article;
use Blog\Contenu\Render_Categorie;
use Blog\Utilitaires\URL;

$title = "Administration";

$renderA = new Render_Article(50);
$articles = $renderA->recuperation("", 1)->getArticles();

$renderC = new Render_Categorie();
$categories = $renderC->recuperation("")->getCategories();

if(URL::getInt("deleteA", 0) === 1){
    echo "l'article a bien été supprimé";
}
if(URL::getInt("deleteC", 0) === 1){
    echo "la catégorie a bien été supprimée";
}

echo "<h1>Administration</h1>";

?>
<div class="table_container">
    <table>
        <thead>
            <tr>
                <td colspan = 4><h3>Articles</h3></td>
            </tr>
            <tr>
                <td>#</td>
                <td>Titre</td>
                <td>Dernière modification</td>
                <td><a href=<?=$router->url("creation_Article")?>>Créer un article</a></td>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($articles as $article){
                echo "<tr>
                <td>#</td>
                <td><a href=\"{$router->url("article", ["id" => $article->getId()])}\">".htmlentities($article->getNom())."</a></td>
                <td>".$article->getDate()."</td>
                <td>
                    <a href=\"{$router->url("edition_Article", ["id" => $article->getId()])}\">Editer</a>

                    <form action =\"{$router->url("suppression_Article", ["id" => $article->getId()])}\" method =\"POST\">
                        <button type=\"submit\">Suppression</button>
                    </form>
                </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
    <table>
        <thead>
            <tr>
                <td colspan = 3><h3>Catégories</h3></td>
            </tr>
            <tr>
                <td>#</td>
                <td>Nom</td>
                <td><a href=<?=$router->url("creation_Categorie")?>>Créer une categorie</a></td>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach($categories as $categorie){
                echo "<tr>
                <td>#</td>
                <td><a href=\"{$router->url("categorie", ["id" => $categorie->getId()])}\">".htmlentities($categorie->getNom())."</a></td>
                <td>
                    <a href=\"{$router->url("edition_Categorie", ["id" => $categorie->getId()])}\">Editer</a>

                    <form action =\"{$router->url("suppression_Categorie", ["id" => $categorie->getId()])}\" method =\"POST\">
                        <button type=\"submit\">Suppression</button>
                    </form>
                </td>
                </tr>";
            }
        ?>
        </tbody>
    </table>
</div>