<?php

namespace Sites;

function convert(string $str):string{
    return htmlspecialchars(htmlentities($str));
}

function affichage_json(array $tab, string $espacement = "")// ne pas initialiser $espacement
{
    echo"{<br>";
    foreach($tab as $key => $value){
        if(is_array($value)){
            echo $espacement."$key => ";
            echo affichage_json($value, $espacement."/>>>")."<br>";
        }else{
            echo $espacement."\"$key\" : $value<br>";
        }
    }
    echo $espacement."}";
}

function remaniement_resultats(array $tab):array
{
    if(empty($tab)){
        return array();
    }
    
    $rendu = array();

    foreach($tab as $key => $value){
        foreach($value as $key2 => $element){
            $rendu[$key2][] = $element;
        }
    }
    
    return $rendu;
}
