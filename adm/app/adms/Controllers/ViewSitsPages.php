<?php

namespace App\adms\Controllers;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Controller da página visualizar situação usuario
 */
class ViewSitsPages
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
            $viewPages = new \App\adms\Models\AdmsViewSitsPages();
            $viewPages->viewSitPages($this->id);
            if ($viewPages->getResult()) {
                $this->data['viewSitPages'] = $viewPages->getResultBd();
                $this->viewSitPages();
            } else {
                $urlRedirect = URL . "list-sit-pages/index";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Situação não encontrada!</p>";
            $urlRedirect = URL . "list-sit-pages/index";
            header("Location: $urlRedirect");
        }
    }

    private function viewSitPages(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $button = [
            'list_sits_pages' => ['menu_controller' => 'list-sits-pages', 'menu_metodo' => 'index'],
            'add_sits_pages' => ['menu_controller' => 'add-sits-pages', 'menu_metodo' => 'index'],
            'view_sits_pages' => ['menu_controller' => 'view-sits-pages', 'menu_metodo' => 'index'],
            'edit_sits_pages' => ['menu_controller' => 'edit-sits-pages', 'menu_metodo' => 'index'],
            'delete_sits_pages' => ['menu_controller' => 'delete-sits-pages', 'menu_metodo' => 'index']
        ];
        $listButton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listButton->buttonPermission($button);

        $this->data["sidebarActive"] = "list-sits-pages";
        $loadView = new \Core\ConfigView("adms/Views/sitsPages/viewSitPages", $this->data);
        $loadView->loadView();
    }
}
