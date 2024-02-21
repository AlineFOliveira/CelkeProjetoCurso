<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class EditSitsPages
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
        if ((!empty($id)) and (empty($this->dataForm['SendEditSitPage']))) {
            $this->id = (int) $id;
            $viewSitPg = new \App\adms\Models\AdmsEditSitsPages();
            $viewSitPg->viewSitPg($this->id);
            if ($viewSitPg->getResult()) {
                $this->data['form'] = $viewSitPg->getResultBd();
                
                $this->viewEditSitPage();
            } else {

                $urlRedirect = URL . "list-sits-pages/index";
                header("Location: $urlRedirect");
            }
        } else {//quando for enviado o formulário
            $this->editSitPage();

            /* $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado! a</p>";
            $urlRedirect = URL . "list-users/index";
            header("Location: $urlRedirect"); */
        }
    }
    private function viewEditSitPage(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $listSelect = new \App\adms\Models\AdmsEditSitsPages();
        $this->data['select'] = $listSelect->listSelect();
        $this->data["sidebarActive"] = "list-sits-pages";
        $loadView = new \Core\ConfigView("adms/Views/sitsPages/editSitPages", $this->data);
        $loadView->loadView();
    }

    private function editSitPage(): void{
        if(!empty($this->dataForm['SendEditSitPage'])){
            unset($this->dataForm['SendEditSitPage']);
            $editSitPages = new \App\adms\Models\AdmsEditSitsPages();
            $editSitPages->update($this->dataForm);

            if($editSitPages->getResult()){
                $urlRedirect = URL . "view-sits-pages/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect"); 
            }else{
                $this->data['form'] = $this->dataForm;
                $this->viewEditSitPage();
            }
        }else{
            $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Situação de página não encontrado!</p>";
            $urlRedirect = URL . "list-sits-pages/index";
            header("Location: $urlRedirect"); 
        }
    }
}
