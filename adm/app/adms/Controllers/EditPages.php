<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class EditPages
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
        if ((!empty($id)) and (empty($this->dataForm['SendEditPages']))) {
            $this->id = (int) $id;
            $viewPages = new \App\adms\Models\AdmsEditPages();
            $viewPages->viewPages($this->id);
            if ($viewPages->getResult()) {
                $this->data['form'] = $viewPages->getResultBd();
                //var_dump($this->data);
                $this->viewEditPages();
            } else {

                $urlRedirect = URL . "list-pages/index";
                header("Location: $urlRedirect");
            }
        } else {//quando for enviado o formulário
            $this->editPages();

            /* $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado! a</p>";
            $urlRedirect = URL . "list-pages/index";
            header("Location: $urlRedirect"); */
        }
    }
    private function viewEditPages(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $listSelect = new \App\adms\Models\AdmsEditPages();
        $this->data['select'] = $listSelect->listSelect();
        $this->data["sidebarActive"] = "list-pages";
        $loadView = new \Core\ConfigView("adms/Views/pages/editPages", $this->data);
        $loadView->loadView();
    }

    private function editPages(): void{
        if(!empty($this->dataForm['SendEditPages'])){
            unset($this->dataForm['SendEditPages']);
            $editPages = new \App\adms\Models\AdmsEditPages();
            $editPages->update($this->dataForm);

            if($editPages->getResult()){
                $urlRedirect = URL . "view-pages/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect"); 
            }else{
                $this->data['form'] = $this->dataForm;
                $this->viewEditPages();
            }
        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não encontrado! a</p>";
            $urlRedirect = URL . "list-pages/index";
            header("Location: $urlRedirect"); 
        }
    }
}
