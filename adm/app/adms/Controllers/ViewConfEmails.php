<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Controller da página visualizar situação usuario
 */
class ViewConfEmails
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        if (!empty($id)) {
            $this->id = (int) $id;

            $viewConfEmails = new \App\adms\Models\AdmsViewConfEmails();
            $viewConfEmails->viewConfEmails($this->id);
            if ($viewConfEmails->getResult()) {
                $this->data['viewConfEmails'] = $viewConfEmails->getResultBd();
                $this->viewConfEmails();
            } else {
                $urlRedirect = URL . "list-conf-emails/index";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-success'>Erro: Configuração de email não encontrada!</p>";
            $urlRedirect = URL . "list-conf-emails/index";
            header("Location: $urlRedirect");
        }
    }

    private function viewConfEmails(): void
    {
        $button = [
            'list_conf_emails' => ['menu_controller' => 'add-conf-emails', 'menu_metodo' => 'index'],
            'view_conf_emails' => ['menu_controller' => 'view-conf-emails', 'menu_metodo' => 'index'],
            'edit_conf_emails' => ['menu_controller' => 'edit-conf-emails', 'menu_metodo' => 'index'],
            'edit_conf_emails_password' => ['menu_controller' => 'edit-conf-emails-password', 'menu_metodo' => 'index'],
            'delete_conf_emails' => ['menu_controller' => 'delete-conf-emails', 'menu_metodo' => 'index']
        ];
        $listButton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listButton->buttonPermission($button);
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $this->data["sidebarActive"] = "list-conf-emails";
        $loadView = new \Core\ConfigView("adms/Views/confEmails/viewConfEmails", $this->data);
        $loadView->loadView();
    }
}
