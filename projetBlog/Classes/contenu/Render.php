<?php

namespace Blog\Contenu;

use PDO;

Class Render
{
    protected PDO $pdo;
    protected string $nomTable;
    
    public function allItems():array
    {
        return $this->pdo->query("SELECT * FROM {$this->nomTable}")->fetchAll();
    }

    public function allCategories():array
    {
        $res = $this->pdo->query("SELECT * FROM categorie")->fetchAll();
        foreach($res as $value){
            $categories[] = new Categorie($value["idCategorie"], $value["nomCategorie"]);
        }

        return $categories;
    }

    public function allArticles():array
    {
        $res = $this->pdo->query("SELECT * FROM article")->fetchAll();
        foreach($res as $value){
            $articles[] = new Article($value["idArticle"], $value["nomArticle"], $value["contenu"], $value["date_creation"]);
        }

        return $articles;
    }

    public function countItems():int
    {
        return (int)$this->pdo->query("SELECT COUNT(*) as total FROM {$this->nomTable}")->fetch()["total"];
    }
}