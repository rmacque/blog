<?php

namespace Blog\Utilitaires;

class Query_Builder {

    private array $select;
    private string $from;
    private string $where;
    private string $groupBy;
    private string $having;
    private array $order;
    private int $limit;
    private int $offset;

    public function __construct()
    {
        $this->select = array();
        $this->from = "";
        $this->where= "";
        $this->order = array();
        $this->limit = 0;
        $this->offset = 0;
    }

    public function select(mixed ...$champs):self
    {
        if(is_array($champs[0])){
            foreach($champs[0] as $value){
                $this->select[] = array($value, false);
            }
        }else{
            foreach($champs as $value){
                $this->select[] = array($value, false);
            }
        }

        return $this;
    }

    /**
     * @param array la liste des champs à sélectionner
     * @param array la liste des alias, leurs positions détermine les champs qu'ils prennent en charge
    */
    public function selectAs(array $champs, array $alias = []):self
    {
        if($champs[0] === "*"){
            $this->select[] = array("*");
            return $this;
        }
        $lchamps = count($champs);
        $lalias = count($alias);
        if($lchamps === $lalias){
            for($i=0 ;$i<$lalias ;$i++){
                $this->select[] = array($champs[$i], $alias[$i]);
            }
        }else if($lchamps > $lalias){
            for($i=0 ;$i<$lalias ;$i++){
                $this->select[] = array($champs[$i], $alias[$i]);
                unset($champs[$i]);
            }
            foreach($champs as $value){
                $this->select[] = array($value, false);
            }
        }else{
            throw new \Exception("Trop d'alias ont été entrés");
        }
        return $this;
    }

    public function from(string $nomTable, string $alias = ""):self
    {
        $this->from .= "$nomTable";
        if(!empty($alias)){
            $this->from .= " $alias";
        }
        return $this;
    }

    public function count():self
    {
        return $this;
    }

    public function where(string $condition):self
    {
        $this->where .= "$condition";
        return $this;
    }

    public function setParam():self
    {
        return $this;
    }

    public function page(int $page):self
    {
        return $this->offset($this->limit * ($page-1));
    }

    public function orderBy(string $nomChamp, bool $ordre = false):self
    {
        if($nomChamp !== ""){
            $ordre = strtoupper($ordre);
            if($ordre){
                $this->order[] = array($nomChamp, "DESC");
            }else{
                $this->order[] = array($nomChamp, "ASC");
            }
        }
        return $this;
    }

    public function limit(int $limit):self
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset):self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Recupere les infos de l'objet pour les transformer en SQL prêt pour l'execution
     * @return string La requête SQL construite selon les attributs
     */
    public function toSQL():string
    {
        $sql = "";

        if(empty($this->select)){
            $sql .= "SELECT *";
        }else{
            $sql .= "SELECT ";
            foreach($this->select as $value){
                if(is_string($value[1])){               //Un alias a été défini
                    $sql .= "$value[0] AS $value[1], ";
                }else{                                  //Pas d'alias
                    $sql .= "$value[0], ";
                }
            }
            $sql = substr($sql, 0, -2);
        }

        if(!empty($this->from)){
            $sql .= " FROM {$this->from}";
        }
        if(!empty($this->where)){
            $sql .= " WHERE {$this->where}";
        }

        if(!empty($this->order)){
            $sql .= " ORDER BY";
            foreach($this->order as $value){
                $sql .= " $value[0] $value[1],";
            }
            $sql = substr($sql, 0, -1);
        }

        if(!empty($this->limit)){
            $sql .= " LIMIT {$this->limit}";
            if($this->offset !== 0){
                $sql .= " OFFSET {$this->offset}";
            }
        }

        return $sql;
    }

    public function fetch(){
        return $this;
    }
}