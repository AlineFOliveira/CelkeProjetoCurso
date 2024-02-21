<?php

namespace App\adms\Controllers;

if (!defined('C8L6K7E')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller listar permissões
 * @author Cesar <cesar@celke.com.br>
 */
class ListPermission
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;
    private array|null $dataForm;
    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;
    /** @var string|int|null $level Recebe o id do número de acesso*/
    private string|int|null $level;
    /** @var string|null $searchName Recebe o nome do usuario */
    private string|null $searchName;
    /** @var string|null $searchEmail Recebe o email do usuario */
    private string|null $searchEmail;

    /**
     * Método listar permissões.
     * 
     * Instancia a MODELS responsável em buscar os registros no banco de dados.
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senão enviar o array de dados vazio.
     *
     * @return void
     */
    public function index(string|int|null $page = null)
    {
        $this->level = filter_input(INPUT_GET, 'level', FILTER_SANITIZE_NUMBER_INT);

        $this->page = (int) $page ? $page : 1;

        $listPermission = new \App\adms\Models\AdmsListPermission();
        $listPermission->listPermissions($this->page, $this->level);

        if ($listPermission->getResult()) {
            $this->data['listPermission'] = $listPermission->getResultBd(); // Corrigido o nome do índice do array
            $this->data['viewAccessLevel'] = $listPermission->getResultBdLevel();
            $this->data['pagination'] = $listPermission->getResultPg();
            $this->data['pag'] = $this->page;
            $this->viewPermission();
        } else {
            //$this->data['listPermission'] = [];
            //$this->data['pagination'] = null;
            
            $urlRedirect = URL . "list-access-levels/index";
            header("Location: $urlRedirect");
        }

        

    }

    private function viewPermission(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $this->data["sidebarActive"] = "list-access-levels";

        $loadView = new \Core\ConfigView("adms/Views/permission/listPermission", $this->data);
        $loadView->loadView();
    }
}
