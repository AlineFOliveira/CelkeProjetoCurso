<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Controller da página visualizar situação usuario
 */
class ViewColors
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

            $viewColors = new \App\adms\Models\AdmsViewColors();
            $viewColors->viewColors($this->id);
            if ($viewColors->getResult()) {
                $this->data['viewColors'] = $viewColors->getResultBd();
                $this->viewColors();
            } else {
                $urlRedirect = URL . "list-colors/index";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-success'>Erro: Cor não encontrada!</p>";
            $urlRedirect = URL . "list-colors/index";
            header("Location: $urlRedirect");
        }
    }

    private function viewColors(): void
    {

        $button = [
            'list_colors' => ['menu_controller' => 'list-colors', 'menu_metodo' => 'index'],
            'view_colors' => ['menu_controller' => 'view-colors', 'menu_metodo' => 'index'],
            'edit_colors' => ['menu_controller' => 'edit-colors', 'menu_metodo' => 'index'],
            'delete_colors' => ['menu_controller' => 'delete-colors', 'menu_metodo' => 'index']
        ];
        $listButton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listButton->buttonPermission($button);
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $this->data["sidebarActive"] = "list-colors";
        $loadView = new \Core\ConfigView("adms/Views/colors/viewColors", $this->data);
        $loadView->loadView();
    }
}
