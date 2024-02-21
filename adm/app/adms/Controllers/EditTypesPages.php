<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class EditTypesPages
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
        if ((!empty($id)) and (empty($this->dataForm['SendEditTypesPages']))) {
            $this->id = (int) $id;
            $viewTypesPages = new \App\adms\Models\AdmsEditTypesPages();
            $viewTypesPages->viewTypesPages($this->id);
            if ($viewTypesPages->getResult()) {
                $this->data['form'] = $viewTypesPages->getResultBd();
                $this->viewEditTypesPages();
            } else {

                $urlRedirect = URL . "list-types-pages/index";
                header("Location: $urlRedirect");
            }
        } else {//quando for enviado o formulário
            $this->editTypesPages();

            /* $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado! a</p>";
            $urlRedirect = URL . "list-users/index";
            header("Location: $urlRedirect"); */
        }
    }
    private function viewEditTypesPages(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $this->data["sidebarActive"] = "list-types-pages";

        $loadView = new \Core\ConfigView("adms/Views/typesPages/editTypesPages", $this->data);
        $loadView->loadView();
    }

    private function editTypesPages(): void{
        if(!empty($this->dataForm['SendEditTypesPages'])){
            unset($this->dataForm['SendEditTypesPages']);
            $editTypesPages = new \App\adms\Models\AdmsEditTypesPages();
            $editTypesPages->update($this->dataForm);

            if($editTypesPages->getResult()){
                $urlRedirect = URL . "view-types-pages/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect"); 
            }else{
                $this->data['form'] = $this->dataForm;
                $this->viewEditTypesPages();
            }
        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Tipo de página não encontrado!</p>";
            $urlRedirect = URL . "list-types-pages/index";
            header("Location: $urlRedirect"); 
        }
    }
}
