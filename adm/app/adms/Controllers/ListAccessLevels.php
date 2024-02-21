<?php

namespace App\adms\Controllers;

if (!defined('C8L6K7E')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller listar nivel de acesso
 * @author Cesar <cesar@celke.com.br>
 */
class ListAccessLevels
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;

    private array|null $dataForm;
    private string|null $searchLvl;
    /**
     * Método listar niveis de acesso.
     * 
     * Instancia a MODELS responsável em buscar os registros no banco de dados.
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senão enviar o array de dados vazio.
     *
     * @return void
     */
    public function index(string|int|null $page = null): void
    {
        $this->page = (int) $page ? $page : 1;
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $this->searchLvl = filter_input(INPUT_GET, 'search_lvl', FILTER_DEFAULT);
        $listAccessLevels = new \App\adms\Models\AdmsListAccessLevels();


        if (!empty($this->dataForm['SendSearchLvls'])) {
            $this->page = 1;

            $listAccessLevels->listSearchLvls($this->page, $this->dataForm['search_lvl']);

            $this->data['form'] = $this->dataForm;
            
        } elseif (!empty($this->searchLvl)) {
            $listAccessLevels->listSearchLvls($this->page, $this->searchLvl);

            $this->data['form']['search_lvl'] = $this->searchLvl;
        } else {

            $listAccessLevels->listAccessLevels($this->page);
        }
        if ($listAccessLevels->getResult()) {
            $this->data['listAccessLevels'] = $listAccessLevels->getResultBd();
            $this->data['pagination'] = $listAccessLevels->getResultPg();
        } else {
            $this->data['listAccessLevels'] = [];
            $this->data['pagination'] = "";
        }
        /* $listAccessLevels = new \App\adms\Models\AdmsListAccessLevels();

        
        $listAccessLevels->listAccessLevels($this->page);
        if ($listAccessLevels->getResult()) {
            $this->data['listAccessLevels'] = $listAccessLevels->getResultBd();
            $this->data['pagination'] = $listAccessLevels->getResultPg();
        } else {
            $this->data['listAccessLevels'] = [];
        } */
        $button = ['add_access_levels' => ['menu_controller' => 'add-access-levels', 'menu_metodo' => 'index'],
        'order_access_levels' => ['menu_controller' => 'order-access-levels', 'menu_metodo' => 'index'],
        'sync_pages_levels' => ['menu_controller' => 'sync-pages-levels', 'menu_metodo' => 'index'],
        'list_permission' => ['menu_controller' => 'list-permission', 'menu_metodo' => 'index'],
        'view_access_levels' => ['menu_controller' => 'view-access-levels', 'menu_metodo' => 'index'],
        'edit_access_levels' => ['menu_controller' => 'edit-access-levels', 'menu_metodo' => 'index'],
        'delete_access_levels' => ['menu_controller' => 'delete-access-levels', 'menu_metodo' => 'index']];
        $listButton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listButton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $this->data['pag'] = $this->page;
        $this->data['sidebarActive'] = "list-access-levels";
        $loadView = new \Core\ConfigView("adms/Views/accessLevels/listAccessLevels", $this->data);
        $loadView->loadView();
    }
}
