<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class EditConfEmails
{
    /**
     * Controller da página editar situação
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
        if ((!empty($id)) and (empty($this->dataForm['SendEditConfEmails']))) {
            $this->id = (int) $id;
            $viewConfEmails = new \App\adms\Models\AdmsEditConfEmails();
            $viewConfEmails->viewConfEmails($this->id);
            if ($viewConfEmails->getResult()) {
                $this->data['form'] = $viewConfEmails->getResultBd();
                
                $this->viewEditConfEmails();
            } else {

                $urlRedirect = URL . "list-conf-emails/index";
                header("Location: $urlRedirect");
            }
        } else {//quando for enviado o formulário
            $this->editConfEmails();

            /* $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado! a</p>";
            $urlRedirect = URL . "list-users/index";
            header("Location: $urlRedirect"); */
        }
    }
    private function viewEditConfEmails(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();

        $this->data["sidebarActive"] = "list-conf-emails";
        $loadView = new \Core\ConfigView("adms/Views/confEmails/editConfEmails", $this->data);
        $loadView->loadView();
    }

    private function editConfEmails(): void{
        if(!empty($this->dataForm['SendEditConfEmails'])){
            unset($this->dataForm['SendEditConfEmails']);
            $editConfEmails = new \App\adms\Models\AdmsEditConfEmails();
            $editConfEmails->update($this->dataForm);

            if($editConfEmails->getResult()){
                $urlRedirect = URL . "view-conf-emails/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect"); 
            }else{
                $this->data['form'] = $this->dataForm;
                $this->viewEditConfEmails();
            }
        }else{
            $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Configuração de e-mail não encontrado!</p>";
            $urlRedirect = URL . "list-conf-emails/index";
            header("Location: $urlRedirect"); 
        }
    }
}
