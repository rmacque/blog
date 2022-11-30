<?php

namespace Blog\Contenu;

use Blog\Utilitaires\{
    Connexion_BD,
    Pagination,
    Query_Builder,
};
use Exception;

Class Render_Article extends Render
{
    protected string $nomTable = "article";
    private array $articles;
    private int $nb_affiche;
    private int $totalite;

    public function __construct($nb_affiche = 10, $articles = [])
    {
        $this->pdo = Connexion_BD::connexion_BD();
        $this->articles = $articles;
        $this->nb_affiche = $nb_affiche;
        $this->totalite = count($this->articles);
    }

    public function recuperation(?string $condition, int $page = 1, bool $ordre = true): self
    {
        //Recuperation
        $query = new Query_Builder;
        $query->select("*")
            ->from($this->nomTable)
            ->where($condition)
            ->limit($this->nb_affiche)
            ->page($page)
            ->orderBy("date_creation", $ordre);
        $statement = $this->pdo->query($query->toSQL());

        $resultat = $statement->fetchAll();

        if(!$resultat){
            throw new \Exception("Aucun article ne correspond à cette condition");
        }
        foreach($resultat as $value){
            $this->articles[] = new Article($value["idArticle"], $value["nomArticle"], $value["contenu"], $value["date_creation"]);
        }
        return $this;
    }

    public function remplissageCategories()
    {
        $listeId = [];
        foreach($this->articles as $article){
            $listeId[] = $article->getId();
        }

        $queryCategories = "
        SELECT idArticle, idCategorie, nomCategorie
        FROM Categorie NATURAL JOIN répertorie
        WHERE idArticle IN (".implode(", ",$listeId).")";

        //listeCategories contient les catégories associées à chaque article dans l'objet
        $listeCategories = $this->pdo->query($queryCategories)->fetchAll();
        /*
        $listeCategories = $this->pdo->query($queryCategories)->fetchAll(\PDO::FETCH_CLASS, Categorie::class, [(int)"idCategorie", "nomCategorie"]);
        dd($listeCategories);
        */
        
        foreach($this->articles as $article){
            foreach($listeCategories as $value){
                if($article->getId() === $value["idArticle"]){
                    $article->addCategorie(new Categorie($value["idCategorie"], $value["nomCategorie"]));
                }
            }
        }

        return $this;
    }

    public function listAperçus($router)
    {
        foreach($this->articles as $article){
            echo "<span class=\"listing\">";
            $article->aperçu($router->url("article", ["id" => $article->getId()]));
            echo "</span>";
        }

        $query_total = new Query_Builder;
        $query_total->select("COUNT(*)")->from($this->nomTable);
        
        Pagination::simple($this->nb_affiche, $this->pdo->query($query_total->toSQL())->fetch()["COUNT(*)"]);
    }

    public function affichage($router):self
    {
        foreach($this->articles as $article){
            
            echo "<h1>".htmlentities($article->getNom())."</h1>";
            echo "<div class=\"listingCategories\">";
            foreach($article->getCategories() as $categorie){
                echo "<a href=\"".$router->url("categorie", ["id"=>$categorie->getId()])."\">#".htmlentities($categorie->getNom())."</a>";
            }
            echo "</div>";
            echo "<p>".htmlentities($article->getContenu())."</p>
            <p>Dernière modification le : ".$article->getDate()."</p>";

            return $this;
        }
    }

    public function delete(int $id){
        if(!$this->pdo->query("DELETE FROM {$this->nomTable} WHERE idArticle = $id")){
            throw new Exception("L'article n°$id n'a pas pu être supprimé");
        }
    }

    public function update(Article $article){
        $statement = $this->pdo->prepare("UPDATE {$this->nomTable} SET nomArticle = :nom, contenu = :contenu, date_creation = :date WHERE idArticle = :id");
        try{
           $statement->execute([
               "nom" => $article->getNom(), 
               "contenu" => $article->getContenu(), 
               "date" => $article->getDate(),
               "id" => $article->getId() 
            ]);
        }
        catch(Exception $e){
            echo "L'article n°{$article->getId()} n'a pas pu être modifié, erreur dans la base de données : $e";
        }
    }

    public function insert(Article $article){
        $statement = $this->pdo->prepare("INSERT INTO {$this->nomTable}(nomArticle, contenu, date_creation) VALUES (:nom, :contenu, :date)");
        try{
           $statement->execute([
               "nom" => $article->getNom(), 
               "contenu" => $article->getContenu(), 
               "date" => $article->getDate()
            ]);
            $statement = $this->pdo->prepare("SELECT idArticle FROM {$this->nomTable} WHERE date_creation = :date");
            $statement->execute(["date" => $article->getDate()]);
            return (int)$statement->fetch()["idArticle"];
        }catch(Exception $e){
            echo "L'article n'a pas pu être créé, erreur dans la base de données : <br>$e";
        }
        return;
    }

    public function assocUpdate(int $idArticle, array $idCategorie){
        foreach($idCategorie as $value){
            $data[] = "($idArticle, $value)";
        }
        $ajout = implode(",", $data ?? []);
        try{    
            $this->pdo->query("DELETE FROM répertorie WHERE idArticle = $idArticle");
            if($ajout !== ""){
                $this->pdo->query("INSERT INTO répertorie VALUES $ajout");
            }
        }catch(Exception $e){
            echo "la mise à jour n'a pas pu se faire: <br>$e";
        }
        return ;
    }

    public function getArticles(){
        return $this->articles;
    }
}