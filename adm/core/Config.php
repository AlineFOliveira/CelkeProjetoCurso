<?php

namespace Core;
if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}
abstract class Config //quer dizer que só pode ser herdada
{
    protected function configAdm()
    {
        define('URL', 'http://localhost/admCelke/adm/');

        define('CONTROLLER', 'Login');
        define('METODO', 'index');
        define('CONTROLLERERRO', 'Erro');

        define('HOST', 'localhost');
        define('USER', 'root');
        define('PASS', '');
        define('DBNAME', 'celke');
        define('PORT', '3306');
        
        
    }
}


?>