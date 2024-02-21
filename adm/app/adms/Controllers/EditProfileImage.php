<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class EditProfileImage
{
    /**
     * Controller da página editar imagem do perfil
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
        if (!empty($this->dataForm['SendEditProfImage'])) {
            $this->editProfImage();
        } else { //quando for enviado o formulário
            $viewProfImg = new \App\adms\Models\AdmsEditProfileImage();
            $viewProfImg->viewProfile();
            if ($viewProfImg->getResult()) {
                $this->data['form'] = $viewProfImg->getResultBd();
                $this->viewEditProfImagem();
            } else {
                $urlRedirect = URL . "login/index";
                header("Location: $urlRedirect");
            }
        }
    }
    private function viewEditProfImagem(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        
        
        $loadView = new \Core\ConfigView("adms/Views/users/editProfileImage", $this->data);
        $loadView->loadView();
    }

    private function editProfImage(): void
    {
        if (!empty($this->dataForm['SendEditProfImage'])) {
            unset($this->dataForm['SendEditProfImage']);
            $this->dataForm['new_image'] = $_FILES['new_image'] ? $_FILES['new_image'] : null;
            $editProfImg = new \App\adms\Models\AdmsEditProfileImage();
            $editProfImg->update($this->dataForm);

            if ($editProfImg->getResult()) {
                $urlRedirect = URL . "view-profile/index" ;
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewEditProfImagem();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Perfil não encontrado! a</p>";
            $urlRedirect = URL . "login/index";
            header("Location: $urlRedirect");
        }
    }
}
