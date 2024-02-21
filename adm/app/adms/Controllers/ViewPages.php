<?php

namespace App\adms\Controllers;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Controller da página visualizar usuarios
 * @author Cesar <cesar@celke.com.br>
 */
class ViewPages
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

            $viewPages = new \App\adms\Models\AdmsViewPages();
            $viewPages->viewPages($this->id);
            if ($viewPages->getResult()) {
                $this->data['viewPages'] = $viewPages->getResultBd();
                $this->viewPages();
            } else {
                $urlRedirect = URL . "list-pages/index";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não encontrado!</p>";
            $urlRedirect = URL . "list-pages/index";
            header("Location: $urlRedirect");
        }
    }

    private function viewPages(): void
    {
        $button = [
            'list_pages' => ['menu_controller' => 'list-pages', 'menu_metodo' => 'index'],
            'view_pages' => ['menu_controller' => 'view-pages', 'menu_metodo' => 'index'],
            'edit_pages' => ['menu_controller' => 'edit-pages', 'menu_metodo' => 'index'],
            'delete_pages' => ['menu_controller' => 'delete-pages', 'menu_metodo' => 'index']
        ];
        $listButton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listButton->buttonPermission($button);
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $this->data["sidebarActive"] = "list-pages";
        $loadView = new \Core\ConfigView("adms/Views/pages/viewPages", $this->data);
        $loadView->loadView();
    }
}
