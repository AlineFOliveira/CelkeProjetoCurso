<?php

namespace App\adms\Models\helper;

use PDO;
use PDOException;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

abstract class AdmsConn
{
    private string $host = HOST;
    private string $user = USER;
    private string $pass = PASS;
    private string $dbname = DBNAME;
    private int|string $port = PORT;
    private object $connect;

    protected function connectDb(): object
    {
        try{
            $this->connect = new PDO("mysql:host={$this->host};port={$this->port};dbname=".$this->dbname, $this->user, $this->pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4") );
            
            return $this->connect;
        }catch(PDOException $err){
            die("Ocorreu um erro");
        }
    } 

}


?>