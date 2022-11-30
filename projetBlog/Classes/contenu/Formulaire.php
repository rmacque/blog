<?php

namespace Blog\Contenu;

use Valitron\Validator;

class Formulaire
{
    private Validator $regles;
    private string $html;
    private array $erreurs;

    /**
     * @param array $data le tableau à analyser par le validateur ($_POST en général)
     * @param string $adress La redirection aprèe l'envoi du formulaire
     * @param string $classes Les classes du formulaire (uniquement pour le CSS)
     * @param string $method La méthode d'envoi
     */
    public function __construct(array $data, string $adress = "", string $classes = "", string $method = "post",)
    {
        $this->regles = new Validator($data, [], "fr_perso");
        $this->html = "<form action = $adress method = $method class = $classes>";
    }

    /**
     * Créé un input de texte
     */
    public function input(string $name, string $label, ?string $default)
    {
        $this->html .= "<div><label>$label : <div><input type=\"text\" name=\"$name\" value=\"$default\" required></div></label></div>";
        //$this->regles->error($name, "bla");
        if(isset($this->erreurs[$name])){
            $this->html .= "<div>".$this->erreurs[$name][0]."</div>";
        }

        return ;
    }

    /**
     * Créé un composant textarea
     */
    public function textarea(string $name, string $label, ?string $default)
    {
        $this->html .= "<div><label>$label : <div><textarea name=\"$name\">$default</textarea></div></label></div>";
        if(isset($this->erreurs[$name])){
            $this->html .= "<div>".$this->erreurs[$name][0]."</div>";
        }
        return ;
    }

    /**
     * Créé un groupe de checkbox
     * @param array $options les possibilitées (ne pas oublier l'elément "checked" pour les cases pré-cochées)
     * @param string $nomGroupe Le nom du groupe des checkbox (pour retrouver les données dans le tableau envoyé)
     */
    public function checkGroup(array $options, string $nomGroupe)
    {
        $this->html .= "$nomGroupe : <div>";
        foreach($options as $value){
            $this->html .= "<label><input type=checkbox name=\"associations[]\" value=\"$value[id]\"";

            if(isset($value["checked"]) && $value["checked"]){
                $this->html .= " checked";
            }

            $this->html .= ">$value[nom]</label>";
        }
        $this->html .= "</div>";
        return ;
    }
    
    public function create(string $classes = "", string $value = "Envoyer"){
        $this->html .= "<button type = \"submit\" class = $classes>$value</button></form>";
        echo $this->html;
        return ;
    }

    //Méthodes liées au validateur

    public function validate(): bool
    {
        return $this->regles->validate();
    }

    public function errors(): array
    {
        return $this->erreurs = $this->regles->errors();
    }

    //Getters

    public function getRegles(){
        return $this->regles;
    }
}

?>