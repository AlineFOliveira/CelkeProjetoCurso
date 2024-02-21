<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class Login
{
    /**
     * Recebe os dados que devem ser enviados para a View
     *
     * @var [type]
     */
    private array|string|null $data = [];
    
    /**
     * Recebe os dados do formulário
     *
     * @var array|null
     */
    private array|null $dataForm;



    public function index()//metodo?
    {
        //Está criando um array com as ifnorpações do formulário enviado via post
        $this->dataForm= filter_input_array(INPUT_POST, FILTER_DEFAULT);


        if(!empty($this->dataForm['SendLogin'])){
            $valLogin = new \App\adms\Models\AdmsLogin();
            $valLogin->login($this->dataForm);
            
            if($valLogin->getResult()){
                $urlRedirect = URL . "dashboard/index";
                header("Location: $urlRedirect");
            }else{
                $this->data['form'] = $this->dataForm;
            }

        }
        
       
        /**
         * Chama a funcão que está no configView e passa como parametro o caminho para a View certa
         */
        $loadView = new \Core\ConfigView("adms/Views/login/login", $this->data);
        $loadView->loadViewLogin();

    }
}


?>