<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class EditProfile
{
    /**
     * Controller da página editar perfil
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




    public function index()
    {


        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (!empty($this->dataForm['SendEditProfile'])) {
            $this->editProfile();
        } else { //quando for enviado o formulário
            $viewProfile = new \App\adms\Models\AdmsEditProfile();
            $viewProfile->viewProfile();
            if ($viewProfile->getResult()) {
                $this->data['form'] = $viewProfile->getResultBd();
                $this->viewEditProfile();
            } else {
                $urlRedirect = URL . "login/index";
                header("Location: $urlRedirect");
            }
        }
    }
    private function viewEditProfile(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        
        $loadView = new \Core\ConfigView("adms/Views/users/editProfile", $this->data);
        $loadView->loadView();
    }

    private function editProfile(): void
    {
        if (!empty($this->dataForm['SendEditProfile'])) {
            unset($this->dataForm['SendEditProfile']);
            $editProfile = new \App\adms\Models\AdmsEditProfile();
            $editProfile->update($this->dataForm);

            if ($editProfile->getResult()) {
                $urlRedirect = URL . "view-profile/index" ;
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewEditProfile();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Perfil não encontrado! a</p>";
            $urlRedirect = URL . "login/index";
            header("Location: $urlRedirect");
        }
    }
}
