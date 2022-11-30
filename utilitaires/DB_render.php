<?php

namespace Sites;

use \Exception;
use \PDO;

class DB_render{
    
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function render(string $nomTable, array $champs, array $alias = [], string $recherche = "", int $limit = 0):void
    {
        $query = new Query_Builder();   //la requête de selection
        $total = new Query_Builder();   //Sert pour la pagination
        $params = array();              //Stocke les options pour la sélection

        foreach($alias as $value){
            if(!is_string($value)){
                throw new Exception("alias incompatibles");
            }
        }

        $query->selectAs($champs, $alias)->from($nomTable)->limit($limit);
        $total->selectAs(["COUNT(*)"], ["total"])->from($nomTable);

        //Organisation
        $organisation = array( "Nom" => $_GET["champ"] ?? "", "Ordre" => $_GET["o"] ?? false);
        if(!in_array($organisation["Nom"], $alias)){     //Prévention contre les injections SQL
            $organisation["Nom"] = "";
        }
        $query->orderBy($organisation["Nom"], $organisation["Ordre"]);
        $chevron = "^";
        if($organisation["Ordre"]){
            $chevron = "v";
        }

        //Recherche
        if(!in_array($recherche, $champs)){
            throw new Exception("le champ de recherche n'existe pas");
        }
        if(!empty($_GET['q'])){
            $query->where("$recherche LIKE :nom");
            $total->where("$recherche LIKE :nom");          //Sauvegarde de la recherche pour la pagination

            $params['nom'] = '%' . $_GET['q'] . '%';           //sert à éviter les injections SQL
        }
        
        //Pagination
        $page = URL::getPositiveInt("page", 1);
        $query->page($page);

        //Exécution de la requête de comptage
        $statement = $this->pdo->prepare($total->toSQL());
        $statement->execute($params);
        $total = (int)$statement->fetch()["total"];

        //Exécution de la requête de recherche
        $statement = $this->pdo->prepare($query->ToSql());
        $statement->execute($params);
        $resultats = $statement->fetchAll();

        $envoi = htmlentities($_GET["q"] ?? null);

        echo <<<HTML
        <form action="" method="GET">
            <div class=recherche>
                <input type="text" name="q" value="$envoi" placeholder="Recherche">
                <button type=submit>Rechercher</button>
            </div>
        </form>
        HTML;
        if(empty($resultats[0])){
            echo "Aucun elément trouvé";
            return ;
        }

        //L'en-tête du tableau
        echo "<table>
                <thead>
                    <tr>";
        foreach ($resultats[0] as $key => $value) {
            if ($key === $organisation["Nom"]) {
                echo "<th><a href=\"?" . URL::URL_builder($_GET, array("champ" => $key, "o" => !$organisation["Ordre"])) . "\">" . $key . "</a>" . $chevron . "</th>";
            } else {
                echo "<th><a href=\"?" . URL::URL_builder($_GET, array("champ" => $key)) . "\">" . $key . "</a></th>";
            }
        }

        //Le corps du tableau
        echo "</tr>
                </thead>
                <tbody>";
        foreach($resultats as $value){
            echo "<tr>";
            foreach($value as $data){
                echo "<td>$data</td>";
            }
            echo "</tr>";
        }
        echo    "</tbody>
            </table>";

        //Pagination
        if($page > 1){
            echo "<a href=?".URL::URL_builder($_GET, array("page" => $page - 1)).">Page Précédente</a>";
        }
        if($limit !== 0 && $page < ($total/$limit)){
            echo "<a href=?".URL::URL_builder($_GET, array("page" => $page + 1)).">Page suivante</a>";
        }
    }
}