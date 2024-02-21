<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Controller da página visualizar situação usuario
 */
class ViewSitsUsers
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

            $viewUser = new \App\adms\Models\AdmsViewSitsUsers();
            $viewUser->viewSitUser($this->id);
            if ($viewUser->getResult()) {
                $this->data['viewSitUser'] = $viewUser->getResultBd();
                $this->viewSitUser();
            } else {
                $urlRedirect = URL . "list-sits-users/index";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-success'>Erro: Situação não encontrada!</p>";
            $urlRedirect = URL . "list-sits-users/index";
            header("Location: $urlRedirect");
        }
    }

    private function viewSitUser(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $button = ['list_sits_users' => ['menu_controller' => 'list-sits-users', 'menu_metodo' => 'index'],
        'edit_sits_users' => ['menu_controller' => 'edit-sits-users', 'menu_metodo' => 'index'],
        'delete_sits_users' => ['menu_controller' => 'delete-sits-users', 'menu_metodo' => 'index']];
        $listButton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listButton->buttonPermission($button);

        $this->data["sidebarActive"] = "list-sits-users";
        $loadView = new \Core\ConfigView("adms/Views/sitsUser/viewSitUser", $this->data);
        $loadView->loadView();
    }
}
