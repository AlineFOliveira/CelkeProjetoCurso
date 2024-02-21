<?php

namespace App\adms\Controllers;

if (!defined('C8L6K7E')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller listar usuários
 * @author Cesar <cesar@celke.com.br>
 */
class ListPages
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;
    private array|null $dataForm;
    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;
    /** @var string|null $searchName Recebe o nome da página */
    private string|null $searchName;
    /** @var string|null $searchType Recebe o tipo da página */
    private string|null $searchType;

    /**
     * Método listar usuários.
     * 
     * Instancia a MODELS responsável em buscar os registros no banco de dados.
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senão enviar o array de dados vazio.
     *
     * @return void
     */
    public function index(string|int|null $page = null)
    {
        $this->page = (int) $page ? $page : 1;

        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $this->searchName = filter_input(INPUT_GET, 'search_name', FILTER_DEFAULT);
        $this->searchType = filter_input(INPUT_GET, 'search_type', FILTER_DEFAULT);

        $listPages = new \App\adms\Models\AdmsListPages();
        if (!empty($this->dataForm['SendSearchPag'])) {
            $this->page = 1;
            $listPages->listSearchPages($this->page, $this->dataForm['search_name']);

            $this->data['form'] = $this->dataForm;
        } elseif (!empty($this->searchName)) {
            $listPages->listSearchPages($this->page, $this->searchName);

            $this->data['form']['search_name'] = $this->searchName;
        } else {

            $listPages->listPages($this->page);
        }
        if ($listPages->getResult()) {
            $this->data['listPages'] = $listPages->getResultBd();
            $this->data['pagination'] = $listPages->getResultPg();
        } else {
            $this->data['listPages'] = [];
            $this->data['pagination'] = "";
        }

        $button = [
            'sync_pages_levels' => ['menu_controller' => 'sync-pages-levels', 'menu_metodo' => 'index'],
            'add_pages' => ['menu_controller' => 'add-pages', 'menu_metodo' => 'index'],
            'view_pages' => ['menu_controller' => 'view-pages', 'menu_metodo' => 'index'],
            'edit_pages' => ['menu_controller' => 'edit-pages', 'menu_metodo' => 'index'],
            'delete_pages' => ['menu_controller' => 'delete-pages', 'menu_metodo' => 'index']
        ];
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $listButton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listButton->buttonPermission($button);


        $this->data['sidebarActive'] = "list-pages";

        $loadView = new \Core\ConfigView("adms/Views/pages/listPages", $this->data);
        $loadView->loadView();
    }
}
