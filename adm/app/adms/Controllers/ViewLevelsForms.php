<?php

namespace App\adms\Controllers;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Controller da página visualizar o nivel de acesso para o formulário novo usuário napágina de login
 */
class ViewLevelsForms
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;


    /**
     * Visualiza o nivel de acesso para o formulário
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        $viewLevelsForm = new \App\adms\Models\AdmsLevelsForms();
        $viewLevelsForm->viewLevelsForm();
        if ($viewLevelsForm->getResult()) {
            $this->data['viewLevelsForm'] = $viewLevelsForm->getResultBd();
            $this->viewLevelsForm();
        } else {
            //$_SESSION['msg'] = "<p class='alert-success'>Erro: Página de configuração não encontrada!</p>";
            $urlRedirect = URL . "dashboard/index";
            header("Location: $urlRedirect");
        }
    }

    private function viewLevelsForm(): void
    {

        /* $button = [
            'edit_levels_forms' => ['menu_controller' => 'edit-levels-forms', 'menu_metodo' => 'index'],
        ];
        $listButton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listButton->buttonPermission($button); */
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $this->data["sidebarActive"] = "view-levels-forms";
        $loadView = new \Core\ConfigView("adms/Views/levelsForm/viewLevelsForms", $this->data);
        $loadView->loadView();
    }
}
