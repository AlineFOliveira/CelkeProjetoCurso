<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}


/**
 * Confirmar a chave atualizar senha e cadastrar nova senha.
 */
class UpdatePassword
{

    private string|null $key;
    private array|string|null $data = [];
    private array|null $dataForm;

    public function index(): void
    {
        $this->key = filter_input(INPUT_GET, "key", FILTER_DEFAULT);
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT); //isso virá do formulário da view

        if (!empty($this->key) and (empty($this->dataForm['SendUpPass']))) { //se existir a chave mas nada tiver sido passado no formulário
            $this->validateKey();
        } else {
            $this->updatePassword();
        }
    }

    private function validateKey(): void
    {
        $valKey = new \App\adms\Models\AdmsUpdatePassword();
        $valKey->valKey($this->key);
        if ($valKey->getResult()) {
            $this->viewUpdatePassword();
        } else {
            $urlRedirect = URL . "login/index";
            header("Location: $urlRedirect");
        }
    }

    private function updatePassword(): void
    {
        if (!empty($this->dataForm['SendUpPass'])) {
            unset($this->dataForm['SendUpPass']);
            $this->dataForm['key'] = $this->key;
            $upPassword = new \App\adms\Models\AdmsUpdatePassword();
            $upPassword->editPassword($this->dataForm);
            if ($upPassword->getResult()) {
                $urlRedirect = URL . "login/index";
                header("Location: $urlRedirect");
            } else {
                $this->viewUpdatePassword();
            }
        } else {
            $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Link inválido, solicite novo link <a href='" . URL . "recover-password/index'>clique aqui</a>!</p>";
            $urlRedirect = URL . "login/index";
            header("Location: $urlRedirect");
        }
    }

    private function viewUpdatePassword(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $loadView = new \Core\ConfigView("adms/Views/login/updatePassword", $this->data);
        $loadView->loadViewLogin();
    }
}
