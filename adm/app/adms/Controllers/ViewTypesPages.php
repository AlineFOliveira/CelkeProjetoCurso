<?php

namespace App\adms\Controllers;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Controller da página visualizar situação usuario
 */
class ViewTypesPages
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

            $viewTypesPages = new \App\adms\Models\AdmsViewTypesPages();
            $viewTypesPages->typesPages($this->id);
            if ($viewTypesPages->getResult()) {
                $this->data['viewTypesPages'] = $viewTypesPages->getResultBd();
                $this->viewTypesPages();
            } else {
                $urlRedirect = URL . "list-types-pages/index";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Tipo de página não encontrado</p>";
            $urlRedirect = URL . "list-groups-pages/index";
            header("Location: $urlRedirect");
        }
    }

    private function viewTypesPages(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $button = [
            'add_types_pages' => ['menu_controller' => 'add-types-pages', 'menu_metodo' => 'index'],
            'add_types_pages' => ['menu_controller' => 'add-types-pages', 'menu_metodo' => 'index'],
            'view_types_pages' => ['menu_controller' => 'view-types-pages', 'menu_metodo' => 'index'],
            'edit_types_pages' => ['menu_controller' => 'edit-types-pages', 'menu_metodo' => 'index'],
            'delete_types_pages' => ['menu_controller' => 'delete-types-pages', 'menu_metodo' => 'index']
        ];
        $listButton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listButton->buttonPermission($button);

        $this->data["sidebarActive"] = "list-types-pages";
        $loadView = new \Core\ConfigView("adms/Views/typesPages/viewTypesPages", $this->data);
        $loadView->loadView();
    }
}
