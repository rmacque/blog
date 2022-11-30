<html>
<head>
    <title><?=isset($title) ? $title: "Mon site"?></title>
    <link rel="stylesheet" href="/defaut.css">
</head>
<body>
    <nav class="navigation">
    <?php 
        $router->lien("home", "Accueil")->lien("liste_categorie", "Catégories");
        if(isset($_SESSION["auth"])){
            $router->lien("administration", "Administration")
                ->lien("logout", "Déconnexion");
        }else{
            $router->lien("login", "Connexion");
        }
    ?>
    </nav>
    <?php
    echo $contenu;
    ?>
    <footer><?="la page s'est chargé en ".round((microtime(true) - DEBUG_TIME) * 1000, 3)." ms"?></footer>
</body>
</html>