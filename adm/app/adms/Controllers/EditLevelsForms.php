<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class EditLevelsForms
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
        if ((!empty($id)) and (empty($this->dataForm['SendEditLevelsForms']))) {
            $this->id = (int) $id;
            $viewLevelsForms = new \App\adms\Models\AdmsEditLevelsForms();
            $viewLevelsForms->viewLevelsForms($this->id);
            if ($viewLevelsForms->getResult()) {
                $this->data['form'] = $viewLevelsForms->getResultBd();
                
                $this->viewEditLevelsForms();
            } else {

                $urlRedirect = URL . "view-levels-forms/index";
                header("Location: $urlRedirect");
            }
        } else {//quando for enviado o formulário
            $this->editLevelsForms();

            /* $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado! a</p>";
            $urlRedirect = URL . "list-users/index";
            header("Location: $urlRedirect"); */
        }
    }
    private function viewEditLevelsForms(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $listSelect = new \App\adms\Models\AdmsEditLevelsForms();
        $this->data['select'] = $listSelect->listSelect();
        $this->data["sidebarActive"] = "view-levels-forms";
        $loadView = new \Core\ConfigView("adms/Views/levelsForm/editLevelsForms", $this->data);
        $loadView->loadView();
    }

    private function editLevelsForms(): void{
        if(!empty($this->dataForm['SendEditLevelsForms'])){
            unset($this->dataForm['SendEditLevelsForms']);
            $editUser = new \App\adms\Models\AdmsEditLevelsForms();
            $editUser->update($this->dataForm);

            if($editUser->getResult()){
                $urlRedirect = URL . "view-levels-forms/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect"); 
            }else{
                $this->data['form'] = $this->dataForm;
                $this->viewEditLevelsForms();
            }
        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Configuração não encontrada! a</p>";
            $urlRedirect = URL . "view-levels-forms/index";
            header("Location: $urlRedirect"); 
        }
    }
}
