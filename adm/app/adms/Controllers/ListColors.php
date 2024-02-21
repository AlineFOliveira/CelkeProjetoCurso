<?php

namespace App\adms\Controllers;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class ListColors
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;
    private array|null $dataForm;

    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;

    /** @var string|null $searchName Recebe o nome da cor*/
    private string|null $searchName;

    /** @var string|null $searchEmail Recebe o hexadecimal da cor*/
    private string|null $searchHex;

    public function index(string|int|null $page = null): void //recebe o numero que tá vindo da url
    {
        $this->page = (int) $page ? $page : 1;
        // se não há numero, automaticamente sera considerado 1

        $listColors = new \App\adms\Models\AdmsListColors();
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $this->searchName = filter_input(INPUT_GET, 'search_name', FILTER_DEFAULT);
        $this->searchHex = filter_input(INPUT_GET, 'search_hex', FILTER_DEFAULT);

        if (!empty($this->dataForm['SendSearchColors'])) {
            $this->page = 1;
            $listColors->listSearchColors($this->page, $this->dataForm['search_name'], $this->dataForm['search_hex']);
            $this->data['form'] = $this->dataForm;
        } elseif ((!empty($this->searchName)) or (!empty($this->searchHex))) {
            $listColors->listSearchColors($this->page, $this->searchName, $this->searchHex);

            $this->data['form']['search_name'] = $this->searchName;
            $this->data['form']['search_hex'] = $this->searchHex;
        } else {
            $listColors->listColors($this->page);
        }
        if ($listColors->getResult()) {
            $this->data['listColors'] = $listColors->getResultBd(); //Verifica o que está cadastrado no banco e salva ali
            $this->data['pagination'] = $listColors->getResultPg();
        } else {
            $this->data['listColors'] = [];
            $this->data['pagination'] = "";
        }


        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $button = [
            'add_colors' => ['menu_controller' => 'add-colors', 'menu_metodo' => 'index'],
            'view_colors' => ['menu_controller' => 'view-colors', 'menu_metodo' => 'index'],
            'edit_colors' => ['menu_controller' => 'edit-colors', 'menu_metodo' => 'index'],
            'delete_colors' => ['menu_controller' => 'delete-colors', 'menu_metodo' => 'index']
        ];
        $listButton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listButton->buttonPermission($button);
        /**
         * Chama a funcão que está no configView e passa como parametro o caminho para a View certa
         */
        $this->data["sidebarActive"] = "list-colors";
        $loadView = new \Core\ConfigView("adms/Views/colors/listColors", $this->data);
        $loadView->loadView();
    }
}
