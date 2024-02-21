<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Controller da página visualizar usuarios
 * @author Cesar <cesar@celke.com.br>
 */
class ViewUsers
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Instantiar a classe responsável em carregar a View e enviar os dados para View.
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        if (!empty($id)) {
            $this->id = (int) $id;

            $viewUser = new \App\adms\Models\AdmsViewUsers();
            $viewUser->viewUser($this->id);
            if ($viewUser->getResult()) {
                $this->data['viewUser'] = $viewUser->getResultBd();
                $this->viewUser();
            } else {
                $urlRedirect = URL . "list-users/index";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não encontrado!</p>";
            $urlRedirect = URL . "list-users/index";
            header("Location: $urlRedirect");
        }
    }

    private function viewUser(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $button = ['list_users' => ['menu_controller' => 'list-users', 'menu_metodo' => 'index'],
        'edit_users' => ['menu_controller' => 'edit-users', 'menu_metodo' => 'index'],
        'edit_users_password' => ['menu_controller' => 'edit-users-password', 'menu_metodo' => 'index'],
        'edit_users_image' => ['menu_controller' => 'edit-users-image', 'menu_metodo' => 'index'],
        'delete_users' => ['menu_controller' => 'delete-users', 'menu_metodo' => 'index']];
        $listButton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listButton->buttonPermission($button);


        $this->data["sidebarActive"] = "list-users";
        $loadView = new \Core\ConfigView("adms/Views/users/viewUser", $this->data);
        $loadView->loadView();
    }
}
