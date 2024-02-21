<?php
namespace App\adms\Controllers;
if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class ConfEmail
{
    private array|string|null $key;


    public function index():void
    {
        $this->key = filter_input(INPUT_GET, "key", FILTER_DEFAULT);


        if(!empty($this->key)){
            $this->valKey();
        }else{
            $_SESSION['msg'] = "<p style='color:red'>Erro: Necessário confirmar o email, solicite novo link <a href='".URL."new-conf-email/index'>Clique aqui</a>!</p>";
            $urlRedirect = URL . "dashboard/index";
            header("Location: $urlRedirect");
        }
    }

    private function valKey(): void
    {
        $confEmail = new \App\adms\Models\AdmsConfEmail();
        $confEmail->confEmail($this->key);
        if($confEmail->getResult()){
            $urlRedirect = URL . "login/index";
            header("Location: $urlRedirect");
        }else{
            $urlRedirect = URL . "login/index";
            header("Location: $urlRedirect");
        }
    }
}


?>