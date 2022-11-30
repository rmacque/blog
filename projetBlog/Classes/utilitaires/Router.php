<?php

namespace Blog\Utilitaires;

use AltoRouter;

class Router{

    private string $genPath;

    private AltoRouter $router;

    /**
     * @param string $genPath Le chemin vers les pages affichées sur le site
     */
    public function __construct(string $genPath)
    {
        $this->genPath = $genPath;
        $this->router = new AltoRouter();
    }

    public function get(string $url, string $nomPage, ?string $nomRoute = null):self
    {
        $this->router->map("GET", $url, $nomPage, $nomRoute);
        return $this;
    }

    public function post(string $url, string $nomPage, ?string $nomRoute = null):self
    {
        $this->router->map("POST", $url, $nomPage, $nomRoute);
        return $this;
    }

    public function match(string $url, string $nomPage, ?string $nomRoute = null):self
    {
        $this->router->map("POST|GET", $url, $nomPage, $nomRoute);
        return $this;
    }

    public function lien(string $nomRoute, string $contenu, ...$listClasses):self
    {
        $classes = implode(", ", $listClasses);
        echo "<a class=\"$classes\" href=".$this->router->generate($nomRoute).">$contenu</a>";
        return $this;
    }
    
    /**
     * @param array args les paramètres de l'url, entrer la clé du tableau avec la valeur
     */
    public function url(string $nomRoute, array $args = [])
    {
        return $this->router->generate($nomRoute, $args);
    }

    public function run():void
    {
        $reseau = $this->router->match();

        if(is_array($reseau)){
            $parametres = $reseau["params"];
            $router = $this;
            ob_start();
            require_once $this->genPath . $reseau["target"] . ".php";
            $contenu = ob_get_clean();
            require_once "../structures/defaut.php";
        }

        return;
    }
}