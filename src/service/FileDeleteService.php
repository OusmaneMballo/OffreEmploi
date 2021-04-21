<?php


namespace App\service;


class FileDeleteService
{
    public function __construct()
    {
    }

    function delete($file){
        if (file_exists($file)){
            return unlink($file);
        }
        return false;
    }
}