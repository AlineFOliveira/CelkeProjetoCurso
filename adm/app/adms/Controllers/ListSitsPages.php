<?php

namespace App\adms\Controllers;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class ListSitsPages
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    private array|null $dataForm;

    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;

    /** @var string|null $searchName Recebe o nome da situação */
    private string|null $searchSit;


    public function index(string|int|null $page = null): void //recebe o numero que tá vindo da url
    {
        $this->page = (int) $page ? $page : 1; // se não há numero, automaticamente sera considerado 1

        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $this->searchSit = filter_input(INPUT_GET, 'search_sit_pag', FILTER_DEFAULT);


        $listSitPage = new \App\adms\Models\AdmsListSitsPages();


        if (!empty($this->dataForm['SendSearchSitsPag'])) {
            $this->page = 1;
            $listSitPage->listSearchSits($this->page, $this->dataForm['search_sit_pag']);

            $this->data['form'] = $this->dataForm;
        } elseif (!empty($this->searchSit)) {
            $listSitPage->listSearchSits($this->page, $this->searchSit);

            $this->data['form']['search_sit'] = $this->searchSit;
        } else {

            $listSitPage->listSitsPages($this->page);
        }
        if ($listSitPage->getResult()) {
            $this->data['listSitsPages'] = $listSitPage->getResultBd();
            $this->data['pagination'] = $listSitPage->getResultPg();
        } else {
            $this->data['listSitsPages'] = [];
            $this->data['pagination'] = "";
        }


        $button = [
            'add_sits_pages' => ['menu_controller' => 'add-sits-pages', 'menu_metodo' => 'index'],
            'view_sits_pages' => ['menu_controller' => 'view-sits-pages', 'menu_metodo' => 'index'],
            'edit_sits_pages' => ['menu_controller' => 'edit-sits-pages', 'menu_metodo' => 'index'],
            'delete_sits_pages' => ['menu_controller' => 'delete-sits-pages', 'menu_metodo' => 'index']
        ];
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $listButton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listButton->buttonPermission($button);

        /**
         * Chama a funcão que está no configView e passa como parametro o caminho para a View certa
         */
        $this->data["sidebarActive"] = "list-sits-pages";
        $loadView = new \Core\ConfigView("adms/Views/sitsPages/listSitPages", $this->data);
        $loadView->loadView();
    }
}
