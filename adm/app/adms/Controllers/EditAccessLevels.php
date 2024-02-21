<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class EditAccessLevels
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
        if ((!empty($id)) and (empty($this->dataForm['SendEditAccessLevels']))) {
            $this->id = (int) $id;
            $viewAccessLvls = new \App\adms\Models\AdmsEditAccessLevels();
            $viewAccessLvls->viewAccessLvls($this->id);
            if ($viewAccessLvls->getResult()) {
                $this->data['form'] = $viewAccessLvls->getResultBd();
                
                $this->viewEditAccessLevels();
            } else {

                $urlRedirect = URL . "list-access-levels/index";
                header("Location: $urlRedirect");
            }
        } else {//quando for enviado o formulário
            $this->editAccessLevels();

            /* $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado! a</p>";
            $urlRedirect = URL . "list-users/index";
            header("Location: $urlRedirect"); */
        }
    }
    private function viewEditAccessLevels(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $this->data["sidebarActive"] = "list-access-levels";

        $loadView = new \Core\ConfigView("adms/Views/accessLevels/editAccessLevels", $this->data);
        $loadView->loadView();
    }

    private function editAccessLevels(): void{
        if(!empty($this->dataForm['SendEditAccessLevels'])){
            unset($this->dataForm['SendEditAccessLevels']);
            $editAccessLevels = new \App\adms\Models\AdmsEditAccessLevels();
            $editAccessLevels->update($this->dataForm);

            if($editAccessLevels->getResult()){
                $urlRedirect = URL . "view-access-levels/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect"); 
            }else{
                $this->data['form'] = $this->dataForm;
                $this->viewEditAccessLevels();
            }
        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nível de acesso não encontrado!</p>";
            $urlRedirect = URL . "list-access-levels/index";
            header("Location: $urlRedirect"); 
        }
    }
}
