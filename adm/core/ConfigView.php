<?php

namespace Core;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}
/**
 * Carregar págins da View
 */
class ConfigView
{
    private $nameView;
    private $data;
    /**
     * vai receber o nome da view e "construir o terreno"
     *
     * @param  $nameView
     */
    public function __construct($nameView, $data)
    {
        $this->nameView = $nameView;
        $this->data = $data;
    }

    public function loadView():void
    {
        if(file_exists('app/' .$this->nameView. '.php')){
            include 'app/adms/Views/include/head.php';
            include 'app/adms/Views/include/navbar.php';
            include 'app/adms/Views/include/menu.php';
            include 'app/' .$this->nameView. '.php';
            include 'app/adms/Views/include/footer.php';

        }else{
            die('Erro: Por favor tente novamente a');
        }
    }

    public function loadViewLogin():void
    {
        if(file_exists('app/' .$this->nameView. '.php')){
            include 'app/adms/Views/include/head_login.php';
            include 'app/' .$this->nameView. '.php';
            include 'app/adms/Views/include/footer_login.php';

        }else{
            die('Erro: Por favor tente novamente');
        }
    }

    
}


?>