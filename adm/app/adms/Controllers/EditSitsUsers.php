<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class EditSitsUsers
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
        if ((!empty($id)) and (empty($this->dataForm['SendEditSitUser']))) {
            $this->id = (int) $id;
            $viewSit = new \App\adms\Models\AdmsEditSitsUsers();
            $viewSit->viewSit($this->id);
            if ($viewSit->getResult()) {
                $this->data['form'] = $viewSit->getResultBd();
                
                $this->viewEditSitUser();
            } else {

                $urlRedirect = URL . "list-sits-users/index";
                header("Location: $urlRedirect");
            }
        } else {//quando for enviado o formulário
            $this->editSitUser();

            /* $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado! a</p>";
            $urlRedirect = URL . "list-users/index";
            header("Location: $urlRedirect"); */
        }
    }
    private function viewEditSitUser(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $listSelect = new \App\adms\Models\AdmsEditSitsUsers();
        $this->data['select'] = $listSelect->listSelect();
        $this->data["sidebarActive"] = "list-sits-users";
        $loadView = new \Core\ConfigView("adms/Views/sitsUser/editSitUser", $this->data);
        $loadView->loadView();
    }

    private function editSitUser(): void{
        if(!empty($this->dataForm['SendEditSitUser'])){
            unset($this->dataForm['SendEditSitUser']);
            $editSitUser = new \App\adms\Models\AdmsEditSitsUsers();
            $editSitUser->update($this->dataForm);

            if($editSitUser->getResult()){
                $urlRedirect = URL . "view-sits-users/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect"); 
            }else{
                $this->data['form'] = $this->dataForm;
                $this->viewEditSitUser();
            }
        }else{
            $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Situação não encontrado!</p>";
            $urlRedirect = URL . "list-sits-users/index";
            header("Location: $urlRedirect"); 
        }
    }
}
