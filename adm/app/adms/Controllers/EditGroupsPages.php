<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class EditGroupsPages
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
        if ((!empty($id)) and (empty($this->dataForm['SendEditGroupsPages']))) {
            $this->id = (int) $id;
            $viewGroupsPages = new \App\adms\Models\AdmsEditGroupsPages();
            $viewGroupsPages->viewGroupsPages($this->id);
            if ($viewGroupsPages->getResult()) {
                $this->data['form'] = $viewGroupsPages->getResultBd();
                $this->viewEditGroupsPages();
            } else {

                $urlRedirect = URL . "list-groups-pages/index";
                header("Location: $urlRedirect");
            }
        } else {//quando for enviado o formulário
            $this->editGroupsPages();

            /* $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado! a</p>";
            $urlRedirect = URL . "list-users/index";
            header("Location: $urlRedirect"); */
        }
    }
    private function viewEditGroupsPages(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $this->data["sidebarActive"] = "list-groups-pages";

        $loadView = new \Core\ConfigView("adms/Views/groupsPages/editGroupsPages", $this->data);
        $loadView->loadView();
    }

    private function editGroupsPages(): void{
        if(!empty($this->dataForm['SendEditGroupsPages'])){
            unset($this->dataForm['SendEditGroupsPages']);
            $editGroupsPages = new \App\adms\Models\AdmsEditGroupsPages();
            $editGroupsPages->update($this->dataForm);

            if($editGroupsPages->getResult()){
                $urlRedirect = URL . "view-groups-pages/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect"); 
            }else{
                $this->data['form'] = $this->dataForm;
                $this->viewEditGroupsPages();
            }
        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Grupo de página não encontrado!</p>";
            $urlRedirect = URL . "list-groups-pages/index";
            header("Location: $urlRedirect"); 
        }
    }
}
