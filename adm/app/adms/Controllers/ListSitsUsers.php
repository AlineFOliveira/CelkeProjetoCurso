<?php

namespace App\adms\Controllers;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class ListSitsUsers
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
        $this->searchSit = filter_input(INPUT_GET, 'search_sit', FILTER_DEFAULT);


        $listSitUsers = new \App\adms\Models\AdmsListSitsUsers();


        if (!empty($this->dataForm['SendSearchSits'])) {
            $this->page = 1;
            $listSitUsers->listSearchSits($this->page, $this->dataForm['search_sit'], $this->dataForm['search_sit']);

            $this->data['form'] = $this->dataForm;
        } elseif (!empty($this->searchSit)) {
            $listSitUsers->listSearchSits($this->page, $this->searchSit);

            $this->data['form']['search_sit'] = $this->searchSit;
        } else {

            $listSitUsers->listSitsUsers($this->page);
        }
        if ($listSitUsers->getResult()) {
            $this->data['listSitsUsers'] = $listSitUsers->getResultBd();
            $this->data['pagination'] = $listSitUsers->getResultPg();
        } else {
            $this->data['listSitsUsers'] = [];
            $this->data['pagination'] = "";
        }

        $button = ['add_sits_users' => ['menu_controller' => 'add-sits-users', 'menu_metodo' => 'index'],
        'view_sits_users' => ['menu_controller' => 'view-sits-users', 'menu_metodo' => 'index'],
        'edit_sits_users' => ['menu_controller' => 'edit-sits-users', 'menu_metodo' => 'index'],
        'delete_sits_users' => ['menu_controller' => 'delete-sits-users', 'menu_metodo' => 'index']];
        $listButton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listButton->buttonPermission($button);


        /* $listSitUsers->listSitsUsers($this->page); //Chama a model
        
        if ($listSitUsers->getResult()) {
            $this->data['listSitsUsers'] = $listSitUsers->getResultBd(); //Verifica o que está cadastrado no banco e salva ali
            $this->data['pagination'] = $listSitUsers->getResultPg();
        } else {
            $this->data['listSitUsers'] = [];
        } */

        /**
         * Chama a funcão que está no configView e passa como parametro o caminho para a View certa
         */
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $this->data["sidebarActive"] = "list-sits-users";
        $loadView = new \Core\ConfigView("adms/Views/sitsUser/listSitUser", $this->data);
        $loadView->loadView();
    }
}
