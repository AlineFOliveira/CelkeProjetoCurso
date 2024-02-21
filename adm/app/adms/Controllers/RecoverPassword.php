<?php
namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Controler da página recuperar senha
 */
class RecoverPassword
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

    public function index():void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if(!empty($this->dataForm['SendRecoverPass'])){
            unset($this->dataForm['SendRecoverPass']);

            $recoverPass = new \App\adms\Models\AdmsRecoverPassword($this->dataForm);
            $recoverPass->recoverPassword($this->dataForm);

            if($recoverPass->getResult()){
                $urlRedirect = URL . "login/index";
                header("Location: $urlRedirect");
            }else{
                $this->data['form'] = $this->dataForm;
                $this->viewRecoverPass();
            }
            
        }else{
            $this->viewRecoverPass();
        }
        
    }

    private function viewRecoverPass(): void
    {
        $loadView = new \Core\ConfigView("adms/Views/login/recoverPassword", $this->data);
        $loadView->loadViewLogin();
    }
}


?>