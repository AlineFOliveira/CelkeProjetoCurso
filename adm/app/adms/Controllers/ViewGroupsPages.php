<?php

namespace App\adms\Controllers;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Controller da página visualizar situação usuario
 */
class ViewGroupsPages
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

            $viewGroupsPages = new \App\adms\Models\AdmsViewGroupsPages();
            $viewGroupsPages->groupsPages($this->id);
            if ($viewGroupsPages->getResult()) {
                $this->data['viewGroupsPages'] = $viewGroupsPages->getResultBd();
                $this->viewGroupsPages();
            } else {
                $urlRedirect = URL . "list-groups-pages/index";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Grupo de página não encontrado</p>";
            $urlRedirect = URL . "list-groups-pages/index";
            header("Location: $urlRedirect");
        }
    }

    private function viewGroupsPages(): void
    {
        $button = [
            'list_groups_pages' => ['menu_controller' => 'list-groups-pages', 'menu_metodo' => 'index'],
            'add_groups_pages' => ['menu_controller' => 'add-groups-pages', 'menu_metodo' => 'index'],
            'view_groups_pages' => ['menu_controller' => 'view-groups-pages', 'menu_metodo' => 'index'],
            'edit_groups_pages' => ['menu_controller' => 'edit-groups-pages', 'menu_metodo' => 'index'],
            'delete_groups_pages' => ['menu_controller' => 'delete-groups-pages', 'menu_metodo' => 'index']
        ];
        $listButton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listButton->buttonPermission($button);
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $this->data["sidebarActive"] = "list-groups-pages";
        $loadView = new \Core\ConfigView("adms/Views/groupsPages/viewGroupsPages", $this->data);
        $loadView->loadView();
    }
}
