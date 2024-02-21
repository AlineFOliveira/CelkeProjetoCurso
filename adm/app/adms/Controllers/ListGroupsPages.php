<?php

namespace App\adms\Controllers;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class ListGroupsPages
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;

    private array|null $dataForm;

    /** @var string|null $searchGroup Recebe o nome do grupo */
    private string|null $searchGroup;

    public function index(string|int|null $page = null): void //recebe o numero que tá vindo da url
    {
        $this->page = (int) $page ? $page : 1; // se não há numero, automaticamente sera considerado 1

        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $this->searchGroup = filter_input(INPUT_GET, 'search_group_pag', FILTER_DEFAULT);
        $listGroupsPages = new \App\adms\Models\AdmsListGroupsPages();

        if (!empty($this->dataForm['SendSearchGroupsPag'])) {
            $this->page = 1;
            $listGroupsPages->listSearchGroups($this->page, $this->dataForm['search_group_pag']);

            $this->data['form'] = $this->dataForm;
        } elseif (!empty($this->searchGroup)) {
            $listGroupsPages->listSearchGroups($this->page, $this->searchGroup);

            $this->data['form']['search_group'] = $this->searchGroup;
        } else {

            $listGroupsPages->listGroupsPages($this->page);
        }
        if ($listGroupsPages->getResult()) {
            $this->data['listGroupsPages'] = $listGroupsPages->getResultBd();
            $this->data['pagination'] = $listGroupsPages->getResultPg();
        } else {
            $this->data['listGroupsPages'] = [];
            $this->data['pagination'] = "";
        }

        /**
         * Chama a funcão que está no configView e passa como parametro o caminho para a View certa
         */

        $button = [
            'order_groups_pages' => ['menu_controller' => 'order-groups-pages', 'menu_metodo' => 'index'],
            'add_groups_pages' => ['menu_controller' => 'add-groups-pages', 'menu_metodo' => 'index'],
            'view_groups_pages' => ['menu_controller' => 'view-groups-pages', 'menu_metodo' => 'index'],
            'edit_groups_pages' => ['menu_controller' => 'edit-groups-pages', 'menu_metodo' => 'index'],
            'delete_groups_pages' => ['menu_controller' => 'delete-groups-pages', 'menu_metodo' => 'index']
        ];
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $listButton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listButton->buttonPermission($button);

        $this->data['pag'] = $this->page;
        $this->data["sidebarActive"] = "list-groups-pages";
        $loadView = new \Core\ConfigView("adms/Views/groupsPages/listGroupsPages", $this->data);
        $loadView->loadView();
    }
}
