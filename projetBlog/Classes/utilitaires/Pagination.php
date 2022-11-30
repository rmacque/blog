<?php

namespace Blog\Utilitaires;

use PDO;

class Pagination
{
    /**
     * Etablit une pagination avec 2 liens qui dirigent vers la page suivante/précédente
     * @param int $nb_affiche le nombre d'eléments à afficher
     * @param int $total le nombre total d'eléments
     */
    public static function simple(int $nb_affiche, int $total)
    {
        //On affiche
        $currentPage = URL::getPositiveInt("page", 1);
        echo "<div class=\"pagination\">";
        if($currentPage > 1){
            echo "<a href=?".URL::URL_builder(array("page" => $currentPage - 1)).">Page précédente</a>";
        }
        if($currentPage < ($total/$nb_affiche)){
            echo "<a href=?".URL::URL_builder(array("page" => $currentPage + 1)).">Page suivante</a>";
        }
        echo "</div>";
    }

    /**
     * Etablit une pagination avec 2 liens qui dirigent vers la page suivante/précédente
     * @param PDO $pdo
     * @param string $comptage la requete de comptage (en SQL brut)
     * @param int $limit le nb d'eléments à afficher par page
     */
    public static function simple_BD(PDO $pdo, string $comptage, int $limit)
    {
        //On compte le nb total d'eléments
        $statement = $pdo->query($comptage);
        $total = (int)$statement->fetch()["COUNT(*)"];

        //On affiche
        $currentPage = URL::getPositiveInt("page", 1);
        echo "<div class=\"pagination\">";
        if($currentPage > 1){
            echo "<a href=?".URL::URL_builder(array("page" => $currentPage - 1)).">Page précédente</a>";
        }
        if($currentPage < ($total/$limit)){
            echo "<a href=?".URL::URL_builder(array("page" => $currentPage + 1)).">Page suivante</a>";
        }
        echo "</div>";
    }

    /**
     * Etablit une pagination avec une liste numérotée des pages
     * @param PDO $pdo
     * @param string $comptage la requete de comptage (en SQL brut)
     * @param int $limit le nb d'eléments à afficher par page
     */
    public function numérotée_BD(PDO $pdo, string $comptage, int $limit)
    {

    }

}