<?php

namespace Blog\Utilitaires;

use \PDO;
use \Exception;

class Connexion_BD{

    public static function connexion_BD(): PDO{
        $dbname = "mysql:host=localhost:3306;dbname=blog;charset=utf8";
        $user = "Rémi";
        $password = "fauxmotdepasse";

        try{
            $pdo = new PDO($dbname, $user ,$password, [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            return $pdo;
        }catch(Exception $e){
            die("Erreur de connexion à la Base de données:<br>".$e->getMessage());
        }
    }
}

function aaa(){
    echo"aaa";
}

?>