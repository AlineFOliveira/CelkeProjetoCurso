<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class EditColors
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
        if ((!empty($id)) and (empty($this->dataForm['SendEditColors']))) {
            $this->id = (int) $id;
            $viewCol = new \App\adms\Models\AdmsEditColors();
            $viewCol->viewCol($this->id);
            if ($viewCol->getResult()) {
                $this->data['form'] = $viewCol->getResultBd();
                
                $this->viewEditColors();
            } else {

                $urlRedirect = URL . "list-colors/index";
                header("Location: $urlRedirect");
            }
        } else {//quando for enviado o formulário
            $this->editColors();

            /* $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado! a</p>";
            $urlRedirect = URL . "list-users/index";
            header("Location: $urlRedirect"); */
        }
    }
    private function viewEditColors(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $this->data["sidebarActive"] = "list-colors";

        $loadView = new \Core\ConfigView("adms/Views/colors/editColors", $this->data);
        $loadView->loadView();
    }

    private function editColors(): void{
        if(!empty($this->dataForm['SendEditColors'])){
            unset($this->dataForm['SendEditColors']);
            $editColors = new \App\adms\Models\AdmsEditColors();
            $editColors->update($this->dataForm);

            if($editColors->getResult()){
                $urlRedirect = URL . "view-colors/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect"); 
            }else{
                $this->data['form'] = $this->dataForm;
                $this->viewEditColors();
            }
        }else{
            $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Cor não encontrado!</p>";
            $urlRedirect = URL . "list-colors/index";
            header("Location: $urlRedirect"); 
        }
    }
}
