<?php

namespace Blog\Contenu;

class Article
{
    private int $idArticle;
    private string $nomArticle;
    private string $contenu;
    private string $date_creation;
    private array $categories;

    public function __construct(int $id, string $nom, string $contenu, string $creation, array $categories = [])
    {
        $this->idArticle = $id;
        $this->nomArticle = $nom;
        $this->contenu = $contenu;
        $this->date_creation = $creation;
        $this->categories = $categories;
    }
    
    /**
     * 
     */
    public function aperçu(string $routeDetails):void
    {
        echo "<div class=\"titre\">".htmlentities($this->nomArticle)."</div>";
        if(strlen($this->contenu) <= 15){
            echo "<div>".htmlentities($this->contenu)."<br><a class=\"lien\" href=".$routeDetails.">Détails</a></div>";
        }else{
            echo "<div>".htmlentities(substr($this->contenu, 0, 15))."...<br><a class=\"lien\" href=".$routeDetails.">Lire la suite</a></div>";
        }
        echo "<div class=\"date\">".$this->getDate()."</div>";
        foreach($this->categories as $categorie){
            echo "<a href=\"".$routeDetails."\">#".htmlentities($categorie->getNom())."</a> ";
        }
    }

    //Getters
    public function getId(){
        return $this->idArticle;
    }

    public function getNom(){
        return $this->nomArticle;
    }

    public function setNom(string $nom):self{
        $this->nomArticle = $nom;
        return $this;
    }

    public function getContenu(){
        return $this->contenu;
    }

    public function setContenu(string $contenu):self{
        $this->contenu = $contenu;
        return $this;
    }

    public function getDate(){
        return $this->date_creation;
    }

    public function setCreation(string $date){
        $this->date_creation = $date;
        return $this;
    }

    public function getCategories(){
        return $this->categories;
    }

    public function addCategorie(Categorie $cat){
        $this->categories[] = $cat;
    }
}
