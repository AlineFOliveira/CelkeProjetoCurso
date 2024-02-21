<?php
namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}
class NewConfEmail
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
        

        if(!empty($this->dataForm['SendNewConfEmail'])){
            unset($this->dataForm['SendNewConfEmail']);
            $newConfEmail = new \App\adms\Models\AdmsNewConfEmail;
            $newConfEmail->newConfEmail($this->dataForm);
            if($newConfEmail->getResult()){
                $urlRedirect = URL . "login/index";
                header("Location: $urlRedirect");
            }else{
                $this->data['form'] = $this->dataForm;
                $this->viewNewConfiEmail();
            }
        }else{
            $this->viewNewConfiEmail();
        }
    }

    private function viewNewConfiEmail(): void
    {
        $loadView = new \Core\ConfigView("adms/Views/login/newConfEmail", $this->data);
        $loadView->loadViewLogin();
    }

    
}


?>