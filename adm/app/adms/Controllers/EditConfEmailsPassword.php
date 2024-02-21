<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class EditConfEmailsPassword
{
    /**
     * Controller da página editar a senha do usuário
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

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;



    public function index(int|string|null $id = null)
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if ((!empty($id)) and (empty($this->dataForm['SendEditConfEmailsPass']))) {
            $this->id = (int) $id;
            $viewConfEmailsPass = new \App\adms\Models\AdmsEditConfEmailsPassword();
            $viewConfEmailsPass->viewConfEmails($this->id);
            if ($viewConfEmailsPass->getResult()) {
                $this->data['form'] = $viewConfEmailsPass->getResultBd();
                
                $this->viewEditConfEmailsPass();
            } else {

                $urlRedirect = URL . "list-conf-emails/index";
                header("Location: $urlRedirect");
            }
        } else {//quando for enviado o formulário
            $this->editConfEmailsPass();

            /* $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado! a</p>";
            $urlRedirect = URL . "list-ConfEmailss/index";
            header("Location: $urlRedirect"); */
        }
    }
    private function viewEditConfEmailsPass(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $this->data["sidebarActive"] = "list-conf-emails";
        $loadView = new \Core\ConfigView("adms/Views/confEmails/editConfEmailsPassword", $this->data);
        $loadView->loadView();
    }

    private function editConfEmailsPass(): void{
        if(!empty($this->dataForm['SendEditConfEmailsPass'])){
            unset($this->dataForm['SendEditConfEmailsPass']);
            $editConfEmailPass = new \App\adms\Models\AdmsEditConfEmailsPassword();
            $editConfEmailPass->update($this->dataForm);

            if($editConfEmailPass->getResult()){
                $urlRedirect = URL . "view-conf-emails/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect"); 
            }else{
                $this->data['form'] = $this->dataForm;
                $this->viewEditConfEmailsPass();
            }
        }else{
            $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Configuração de e-mail não encontrada!</p>";
            $urlRedirect = URL . "list-conf-emails/index";
            header("Location: $urlRedirect"); 
        }
    }
}
