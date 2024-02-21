<?php

namespace App\adms\Controllers;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class ListTypesPages
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;
    public function index(string|int|null $page = null): void //recebe o numero que tá vindo da url
    {
        $this->page = (int) $page ? $page : 1; // se não há numero, automaticamente sera considerado 1

        $listTypesPages = new \App\adms\Models\AdmsListTypesPages();
        $listTypesPages->listTypesPages($this->page); //Chama a model
        if ($listTypesPages->getResult()) {
            $this->data['listTypesPages'] = $listTypesPages->getResultBd(); //Verifica o que está cadastrado no banco e salva ali
            $this->data['pagination'] = $listTypesPages->getResultPg();
        } else {
            $this->data['listTypesPages'] = [];
            $this->data['pagination'] = "";
        }

        /**
         * Chama a funcão que está no configView e passa como parametro o caminho para a View certa
         */
        $button = [
            'order_types_pages' => ['menu_controller' => 'add-types-pages', 'menu_metodo' => 'index'],
            'add_types_pages' => ['menu_controller' => 'add-types-pages', 'menu_metodo' => 'index'],
            'view_types_pages' => ['menu_controller' => 'view-types-pages', 'menu_metodo' => 'index'],
            'edit_types_pages' => ['menu_controller' => 'edit-types-pages', 'menu_metodo' => 'index'],
            'delete_types_pages' => ['menu_controller' => 'delete-types-pages', 'menu_metodo' => 'index']
        ];
        $listButton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listButton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $this->data['pag'] = $this->page;
        $this->data["sidebarActive"] = "list-types-pages";
        $loadView = new \Core\ConfigView("adms/Views/typesPages/listTypesPages", $this->data);
        $loadView->loadView();
    }
}
