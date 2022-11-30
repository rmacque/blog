<?php

namespace Blog\Utilitaires;

class URL
{
    /**
     * @param array $data les données de base ($_GET habituellemnt)
     * @param array $param les données à ajouter
     */
    public static function URL_builder(array $param): string    //Sert à ajouter des paramètres à l'URL
    {
        return http_build_query(array_merge($_GET, $param));
    }

    /**
     * Récupère un entier (positif ou négatif). Sert uniquement à récupérer des paramètres passés par la méthtode GET
     * @param array $name le nom du paramètre à récupérer dans $_GET
     * @param int $default la valeur par défaut si $name n'existe pas
     */
    public static function getInt(string $name, ?int $default = null): ?int
    {
        if(!isset($_GET[$name])){
            return $default;
        }

        if(!filter_var($_GET[$name], FILTER_VALIDATE_INT)){
            throw new \Exception("Le paramètre de page '$name' n'est pas valide");
        }
        return (int)$_GET[$name];
    }

    /**
     * Récupère un entier strictement positif. Sert uniquement à récupérer des paramètres passés par la méthtode GET
     * @param array $name le nom du paramètre à récupérer dans $_GET
     * @param array $default la valeur par défaut si $name n'existe pas
     */
    public static function getPositiveInt(string $name, ?int $default = null): ?int
    {
        if(!isset($_GET[$name])){
            return $default;
        }

        if(!filter_var($_GET[$name], FILTER_VALIDATE_INT) || $_GET[$name] < 0){
            throw new \Exception("Le paramètre de page '$name' n'est pas valide");
        }

        return (int)$_GET[$name];
    }
}