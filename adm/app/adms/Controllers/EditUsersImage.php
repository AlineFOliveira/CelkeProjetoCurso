<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class EditUsersImage
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
        if ((!empty($id)) and (empty($this->dataForm['SendEditUserImage']))) {
            $this->id = (int) $id;
            $viewUser = new \App\adms\Models\AdmsEditUsersImage();
            $viewUser->viewUser($this->id);
            if ($viewUser->getResult()) {
                $this->data['form'] = $viewUser->getResultBd();
                
                $this->viewEditUserImage();
            } else {

                $urlRedirect = URL . "list-users/index";
                header("Location: $urlRedirect");
            }
        } else {//quando for enviado o formulário
            $this->editUserImage();

        }
    }
    private function viewEditUserImage(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $this->data["sidebarActive"] = "list-users";
        $loadView = new \Core\ConfigView("adms/Views/users/editUserImage", $this->data);
        $loadView->loadView();
    }

    private function editUserImage(): void{
        if(!empty($this->dataForm['SendEditUserImage'])){
            unset($this->dataForm['SendEditUserImage']);
            $this->dataForm['new_image'] = $_FILES['new_image'] ? $_FILES['new_image'] : null;//verifica se é verdadeiro
            $editUserImage = new \App\adms\Models\AdmsEditUsersImage();
            $editUserImage->update($this->dataForm);

            if($editUserImage->getResult()){
                $urlRedirect = URL . "view-users/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect"); 
            }else{
                $this->data['form'] = $this->dataForm;
                $this->viewEditUserImage();
            }
        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não encontrado! a</p>";
            $urlRedirect = URL . "list-users/index";
            header("Location: $urlRedirect"); 
        }
    }
}
