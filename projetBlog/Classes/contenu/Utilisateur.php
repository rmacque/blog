<?php

namespace Blog\Contenu;

class Utilisateur
{
    private int $id;
    private string $username;
    private string $password;

    public function getUsername(){
        return $this->username;
    }

    public function setUsername(string $username){
        $this->username = $username;
    }

    public function getPassword(){
        return $this->password;
    }

    public function setPassword(string $password){
        $this->password = $password;
    }
}