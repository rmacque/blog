<?php

namespace Blog\Contenu;

use Blog\Utilitaires\{
    Connexion_BD,
    Query_Builder
};
use Exception;

Class Render_Utilisateur extends Render
{
    protected string $nomTable = "utilisateur";
    private Utilisateur $user;

    public function __construct()
    {
        $this->pdo = Connexion_BD::connexion_BD();
    }

    public function recuperation(?string $condition)
    {
        //Recuperation
        $query = new Query_Builder;
        $query->select("*")
            ->from($this->nomTable)
            ->where($condition);
        $statement = $this->pdo->query($query->toSQL());

        $statement->setFetchMode(\PDO::FETCH_CLASS, Utilisateur::class);
        $b = $statement->fetch();

        if(!$b){
            return false;
        }else{
            $this->user = $b;
        }

        return $this;
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

    public function getUser(){
        return $this->user;
    }
}