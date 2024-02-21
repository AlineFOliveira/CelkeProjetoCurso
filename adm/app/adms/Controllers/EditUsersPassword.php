<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class EditUsersPassword
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
        if ((!empty($id)) and (empty($this->dataForm['SendEditUserPass']))) {
            $this->id = (int) $id;
            $viewUserPass = new \App\adms\Models\AdmsEditUsersPassword();
            $viewUserPass->viewUser($this->id);
            if ($viewUserPass->getResult()) {
                $this->data['form'] = $viewUserPass->getResultBd();
                
                $this->viewEditUserPass();
            } else {

                $urlRedirect = URL . "list-users/index";
                header("Location: $urlRedirect");
            }
        } else {//quando for enviado o formulário
            $this->editUserPass();

            /* $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado! a</p>";
            $urlRedirect = URL . "list-users/index";
            header("Location: $urlRedirect"); */
        }
    }
    private function viewEditUserPass(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $this->data["sidebarActive"] = "list-users";
        $loadView = new \Core\ConfigView("adms/Views/users/editUserPass", $this->data);
        $loadView->loadView();
    }

    private function editUserPass(): void{
        if(!empty($this->dataForm['SendEditUserPass'])){
            unset($this->dataForm['SendEditUserPass']);
            $editUserPass = new \App\adms\Models\AdmsEditUsersPassword();
            $editUserPass->update($this->dataForm);

            if($editUserPass->getResult()){
                $urlRedirect = URL . "view-users/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect"); 
            }else{
                $this->data['form'] = $this->dataForm;
                $this->viewEditUserPass();
            }
        }else{
            $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado! a</p>";
            $urlRedirect = URL . "list-users/index";
            header("Location: $urlRedirect"); 
        }
    }
}
