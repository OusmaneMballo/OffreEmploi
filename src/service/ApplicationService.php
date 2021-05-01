<?php

namespace App\service;

class ApplicationService
{
    public function __construct()
    {
    }

    /**
        La fonction retourne la taille d'un tableau d'objet
     **/
    public function count($listObject): int{
        $counter=0;
        foreach ($listObject as $object){
            $counter++;
        }
        return $counter;
    }
}