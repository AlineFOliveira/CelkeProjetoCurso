<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Controller da página visualizar situação usuario
 */
class ViewAccessLevels
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

            $viewAccessLevels = new \App\adms\Models\AdmsViewAccessLevels();
            $viewAccessLevels->accessLevels($this->id);
            if ($viewAccessLevels->getResult()) {
                $this->data['viewAccessLevels'] = $viewAccessLevels->getResultBd();
                $this->viewAccessLevels();
            } else {
                $urlRedirect = URL . "list-access-levels/index";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nível de acesso não encontrado</p>";
            $urlRedirect = URL . "list-access-levels/index";
            header("Location: $urlRedirect");
        }
    }

    private function viewAccessLevels(): void
    {
        $button = ['add_users' => ['menu_controller' => 'add-users', 'menu_metodo' => 'index'],
        'view_users' => ['menu_controller' => 'view-users', 'menu_metodo' => 'index'],
        'edit_users' => ['menu_controller' => 'edit-users', 'menu_metodo' => 'index'],
        'delete_users' => ['menu_controller' => 'delete-users', 'menu_metodo' => 'index']];
        $listButton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listButton->buttonPermission($button);
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $this->data["sidebarActive"] = "list-access-levels";
        $loadView = new \Core\ConfigView("adms/Views/accessLevels/viewAccessLevels", $this->data);
        $loadView->loadView();
    }
}
