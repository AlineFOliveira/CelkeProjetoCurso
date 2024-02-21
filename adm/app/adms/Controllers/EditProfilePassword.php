<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class EditProfilePassword
{
    /**
     * Controller da página editar senha perfil
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




    public function index(): void
    {

        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($this->dataForm['SendEditProfPass'])) {
           $this->editProfPass();
        } else {
            $viewProfPass = new \App\adms\Models\AdmsEditProfilePassword();
            $viewProfPass->viewProfile();
            if ($viewProfPass->getResult()) {
                $this->data['form'] = $viewProfPass->getResultBd();
                $this->viewEditProfPass();
            } else {
                $urlRedirect = URL . "login/index";
                header("Location: $urlRedirect");
            }
        }
    }
    private function viewEditProfPass(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        
        $loadView = new \Core\ConfigView("adms/Views/users/editProfilePassword", $this->data);
        $loadView->loadView();
    }

    private function editProfPass(): void
    {
        if (!empty($this->dataForm['SendEditProfPass'])) {
            unset($this->dataForm['SendEditProfPass']);
            $editProfPass = new \App\adms\Models\AdmsEditProfilePassword();
            $editProfPass->update($this->dataForm);

            if ($editProfPass->getResult()) {
                $urlRedirect = URL . "view-profile/index" ;
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewEditProfPass();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Perfil não encontrado!</p>";
            $urlRedirect = URL . "login/index";
            header("Location: $urlRedirect");
        }
    }
}
