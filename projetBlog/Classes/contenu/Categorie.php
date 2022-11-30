<?php

namespace Blog\Contenu;

class Categorie
{
    private int $idCategorie;
    private string $nomCategorie;
    private array $articles = [];

    public function __construct(int $id, string $nom)
    {
        $this->idCategorie = $id;
        $this->nomCategorie = $nom;
    }

    public function aperçu(string $routeDetails){
        echo "<div><a class=\"lien\" href=".$routeDetails.">".htmlentities($this->nomCategorie)."</a></div>";
        
        foreach($this->articles as $article){
            echo "<a href=\"".$routeDetails."\">#".htmlentities($article->getNom())."</a> ";
        }    
    }

    public function addArticle(Article $art){
        $this->articles[] = $art;
    }

    public function getId(){
        return $this->idCategorie;
    }

    public function getNom(){
        return $this->nomCategorie;
    }

    public function setNom(string $nom){
        $this->nomCategorie = $nom;
        return;
    }

    public function getArticles(){
        return $this->articles;
    }

    public function setArticles(array $list){
        $this->articles = $list;
        return ;
    }
}