<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class NewUser
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



    public function index() //metodo?
    {

        ///Está criando um array com as informações do formulário enviado via post
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);


        if (!empty($this->dataForm['SendNewUser'])) {//se for diferente de vazio, ou seja, se existe algo
            unset($this->dataForm['SendNewUser']);
            //var_dump($this->dataForm);
            $createNewUser = new \App\adms\Models\AdmsNewUser();
            $createNewUser->create($this->dataForm);

            if ($createNewUser->getResult()) {
                $urlRedirect = URL;
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewNewUser();
            }
        } else {
            $this->viewNewUser();
        }


        //$this->data = null;
        
    }
    private function viewNewUser(): void
    {
        $loadView = new \Core\ConfigView("adms/Views/login/newUser", $this->data);
        $loadView->loadViewLogin();
    }
}
