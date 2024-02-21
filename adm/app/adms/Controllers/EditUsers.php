<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class EditUsers
{
    /**
     * Controller da página editar usuário
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
        if ((!empty($id)) and (empty($this->dataForm['SendEditUser']))) {
            $this->id = (int) $id;
            $viewUser = new \App\adms\Models\AdmsEditUsers();
            $viewUser->viewUser($this->id);
            if ($viewUser->getResult()) {
                $this->data['form'] = $viewUser->getResultBd();
                
                $this->viewEditUser();
            } else {

                $urlRedirect = URL . "list-users/index";
                header("Location: $urlRedirect");
            }
        } else {//quando for enviado o formulário
            $this->editUser();

            /* $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado! a</p>";
            $urlRedirect = URL . "list-users/index";
            header("Location: $urlRedirect"); */
        }
    }
    private function viewEditUser(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $listSelect = new \App\adms\Models\AdmsEditUsers();
        $this->data['select'] = $listSelect->listSelect();
        $this->data["sidebarActive"] = "list-users";
        $loadView = new \Core\ConfigView("adms/Views/users/editUser", $this->data);
        $loadView->loadView();
    }

    private function editUser(): void{
        if(!empty($this->dataForm['SendEditUser'])){
            unset($this->dataForm['SendEditUser']);
            $editUser = new \App\adms\Models\AdmsEditUsers();
            $editUser->update($this->dataForm);

            if($editUser->getResult()){
                $urlRedirect = URL . "view-users/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect"); 
            }else{
                $this->data['form'] = $this->dataForm;
                $this->viewEditUser();
            }
        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não encontrado! a</p>";
            $urlRedirect = URL . "list-users/index";
            header("Location: $urlRedirect"); 
        }
    }
}
