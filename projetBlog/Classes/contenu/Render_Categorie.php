<?php

namespace Blog\Contenu;

use Blog\Utilitaires\{
    Connexion_BD,
    Pagination,
    Query_Builder,
};
use Exception;

Class Render_Categorie extends Render
{
    protected string $nomTable = "categorie";
    private array $categories;

    public function __construct($categories = [])
    {
        $this->pdo = Connexion_BD::connexion_BD();
        $this->categories = $categories;
    }

    public function recuperation(?string $condition, int $page = 1): self
    {
        //Recuperation
        $query = new Query_Builder;
        $query->select("*")
            ->from($this->nomTable)
            ->where($condition)
            ->page($page);
        $statement = $this->pdo->query($query->toSQL());

        $resultat = $statement->fetchAll();

        if(!$resultat){
            throw new \Exception("Aucune catégorie ne correspond à cette condition");
        }
        foreach($resultat as $value){
            $this->categories[] = new Categorie($value["idCategorie"], $value["nomCategorie"]);
        }
        return $this;
    }

    public function remplissageArticles()
    {
        $listeId = [];
        foreach($this->categories as $categorie){
            $listeId[] = $categorie->getId();
        }

        $queryArticles = "
        SELECT *
        FROM répertorie NATURAL JOIN Article
        WHERE idCategorie IN (".implode(", ",$listeId).")";

        //listeArticles contient les articles associées à chaque catégorie dans l'objet
        $listeArticles = $this->pdo->query($queryArticles)->fetchAll();

        foreach($this->categories as $categorie){
            foreach($listeArticles as $value){
                if($categorie->getId() === $value["idCategorie"]){
                    $categorie->addArticle(new Article($value["idArticle"], $value["nomArticle"], $value["contenu"], $value["date_creation"]));
                }
            }
        }

        return $this;
    }

    public function aperçus($router)
    {
        foreach($this->categories as $categorie){
            echo "<div class=\"listing\">";
            $categorie->aperçu($router->url("categorie", ["id" => $categorie->getId()]));
            
            echo "</div>";
        }

        $query_total = new Query_Builder;
        $query_total->select("COUNT(*)")->from($this->nomTable);
        Pagination::simple_BD($this->pdo, $query_total->toSQL(), NB_AFFICHE);
    }

    public function affichage($router):self
    {
        foreach($this->categories as $categorie){
            
            echo "<h1>{$categorie->getNom()}</h1>";
            
            $render = new Render_Article(50, $categorie->getArticles());
            $render->listAperçus($router, 4);
            
        }

        return $this;
    }

    public function delete(int $id){
        echo "Catégorie n°$id supprimée (en théorie)";
        if(!$this->pdo->query("DELETE FROM {$this->nomTable} WHERE idCategorie = $id")){
            throw new Exception("La Catégorie n°$id n'a pas pu être supprimée");
        }
        
    }

    public function update(Categorie $categorie){
        $statement = $this->pdo->prepare("UPDATE {$this->nomTable} SET nomCategorie = :nom WHERE idCategorie = :id");
        try{
           $statement->execute([
               "nom" => $categorie->getNom(), 
               "id" => $categorie->getId()
            ]);
        }
        catch(Exception $e){
            echo "La catégorie n°{$categorie->getId()} n'a pas pu être modifié, erreur dans la base de données : <br>$e";
        }
    }

    public function insert(Categorie $categorie){
        $statement = $this->pdo->prepare("INSERT INTO {$this->nomTable}(nomCategorie) VALUES (:nom)");
        try{
           $statement->execute([
               "nom" => $categorie->getNom(),
            ]);
        }catch(Exception $e){
            echo "La catégorie n'a pas pu être créée, erreur dans la base de données : <br>$e";
        }
    }

    public function getCategories(){
        return $this->categories;
    }
}